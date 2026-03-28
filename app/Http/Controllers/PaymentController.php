<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Services\VnpayService;
use App\Services\MomoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    /**
     * Hiển thị trang chọn phương thức thanh toán.
     */
    public function chooseMethod($bookingId)
    {
        $booking = Booking::with(['tour', 'schedule', 'customer'])->findOrFail($bookingId);

        // Chỉ chủ sở hữu booking mới được thanh toán
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        if ($booking->isCancelled()) {
            return redirect()->to(route('user.profile') . '#bookings')
                ->with('error', 'Booking này đã bị hủy nên không thể thanh toán.');
        }

        // Nếu đã thanh toán rồi thì chuyển thẳng trang thành công
        if ($booking->payment_status === 'paid') {
            return redirect()->route('user.booking.success', $bookingId);
        }

        return view('user.payment-methods', compact('booking'));
    }

    /**
     * Xử lý thanh toán qua VNPAY.
     */
    public function payVnpay(Request $request, $bookingId)
    {
        $booking = Booking::findOrFail($bookingId);

        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        if ($booking->isCancelled()) {
            return redirect()->to(route('user.profile') . '#bookings')
                ->with('error', 'Booking này đã bị hủy nên không thể thanh toán.');
        }

        // Cập nhật phương thức thanh toán
        $booking->update(['payment_method' => 'vnpay']);

        $vnpayService = new VnpayService();

        $paymentUrl = $vnpayService->createPaymentUrl([
            'booking_id' => $booking->booking_id,
            'amount' => (int) $booking->total_price,
            'ip_address' => $request->ip(),
        ]);

        return redirect()->away($paymentUrl);
    }

    /**
     * VNPAY Return URL - Xử lý kết quả thanh toán trả về.
     */
    public function vnpayReturn(Request $request)
    {
        $inputData = $request->all();

        $vnpayService = new VnpayService();

        if ($vnpayService->validateSignature($inputData)) {
            $vnp_ResponseCode = $inputData['vnp_ResponseCode'] ?? '';
            $vnp_TxnRef = $inputData['vnp_TxnRef'] ?? '';
            $vnp_TransactionNo = $inputData['vnp_TransactionNo'] ?? '';

            // Lấy booking_id từ vnp_TxnRef (format: bookingId_timestamp)
            $bookingId = explode('_', $vnp_TxnRef)[0];
            $booking = Booking::find($bookingId);

            if ($booking && $booking->isCancelled()) {
                return redirect()->route('home')
                    ->with('error', 'Booking đã bị hủy nên giao dịch này không còn hợp lệ.');
            }

            if ($booking && $vnp_ResponseCode === '00') {
                // Thanh toán thành công
                $booking->update([
                    'payment_status' => 'paid',
                    'admin_confirmed' => false,
                    'payment_method' => 'vnpay',
                    'vnp_transaction_no' => $vnp_TransactionNo,
                ]);

                return redirect()->route('user.booking.success', $bookingId)
                    ->with('success', 'Thanh toán VNPAY thành công!');
            } else {
                // Thanh toán thất bại
                return redirect()->route('payment.choose', $bookingId)
                    ->with('error', 'Thanh toán VNPAY thất bại. Mã lỗi: ' . $vnp_ResponseCode);
            }
        }

        return redirect()->route('home')->with('error', 'Chữ ký không hợp lệ.');
    }

    /**
     * Hiển thị trang thanh toán VietQR.
     */
    public function payVietqr($bookingId)
    {
        $booking = Booking::with(['tour', 'schedule', 'customer'])->findOrFail($bookingId);

        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        if ($booking->isCancelled()) {
            return redirect()->to(route('user.profile') . '#bookings')
                ->with('error', 'Booking này đã bị hủy nên không thể thanh toán.');
        }

        // Cập nhật phương thức thanh toán
        $booking->update(['payment_method' => 'vietqr']);

        $bankInfo = [
            'bank_id' => env('VIETQR_BANK_ID', 'MB'),
            'account_no' => env('VIETQR_ACCOUNT_NO', '0383282605'),
            'account_name' => env('VIETQR_ACCOUNT_NAME', 'HOANG DUC TIEN'),
        ];

        $transferContent = 'VIETTOUR ' . $booking->booking_id;
        $amount = (int) $booking->total_price;

        // Tạo URL QR từ VietQR API
        $qrUrl = "https://img.vietqr.io/image/{$bankInfo['bank_id']}-{$bankInfo['account_no']}-compact2.png"
            . "?amount={$amount}"
            . "&addInfo=" . urlencode($transferContent)
            . "&accountName=" . urlencode($bankInfo['account_name']);

        return view('user.payment-vietqr', compact('booking', 'bankInfo', 'qrUrl', 'transferContent', 'amount'));
    }

    /**
     * Xác nhận đã chuyển khoản VietQR.
     */
    public function vietqrConfirm($bookingId)
    {
        $booking = Booking::findOrFail($bookingId);

        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        if ($booking->isCancelled()) {
            return redirect()->to(route('user.profile') . '#bookings')
                ->with('error', 'Booking này đã bị hủy nên không thể xác nhận thanh toán.');
        }

        $booking->update([
            'payment_status' => 'paid',
            'admin_confirmed' => false,
            'payment_method' => 'vietqr',
        ]);

        return redirect()->route('user.booking.success', $bookingId)
            ->with('success', 'Xác nhận chuyển khoản thành công! Admin sẽ kiểm tra và xác nhận đơn hàng của bạn.');
    }

    /**
     * Khởi tạo quá trình thanh toán MoMo API.
     */
    public function payMomo(Request $request, $bookingId)
    {
        $booking = Booking::findOrFail($bookingId);

        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        if ($booking->isCancelled()) {
            return redirect()->to(route('user.profile') . '#bookings')
                ->with('error', 'Booking này đã bị hủy nên không thể thanh toán.');
        }

        // Đánh dấu là chọn MoMo
        $booking->update(['payment_method' => 'momo']);

        $momoService = new MomoService();

        $paymentUrl = $momoService->createPaymentUrl([
            'booking_id' => $booking->booking_id,
            'amount' => (int) $booking->total_price,
            'ip_address' => $request->ip(),
        ]);

        if ($paymentUrl) {
            return redirect()->away($paymentUrl);
        }

        return redirect()->back()->with('error', 'Không thể khởi tạo thanh toán MoMo lúc này. Vui lòng thử lại.');
    }

    /**
     * MoMo Return URL - Xử lý redirect khi thanh toán xong trên cổng MoMo.
     * Vì dev k dùng Ngrok IPN, ta sẽ check kết quả ở đây luôn.
     */
    public function momoReturn(Request $request)
    {
        $inputData = $request->all();

        // Neu request khong co du lieu orderId
        if (!isset($inputData['orderId'])) {
            return redirect()->route('home')->with('error', 'Dữ liệu không hợp lệ từ MoMo.');
        }

        $momoService = new MomoService();

        // 1. Kiểm tra tính toàn vẹn chữ ký MoMo gửi về (validate hash)
        if ($momoService->validateSignature($inputData)) {

            $resultCode = $inputData['resultCode'] ?? '';
            $orderId = $inputData['orderId'] ?? '';

            // Format orderId gửi lên là: {booking_id}_{timestamp}
            $bookingId = explode('_', $orderId)[0];
            $booking = Booking::find($bookingId);

            if ($booking) {
                if ($booking->isCancelled()) {
                    return redirect()->route('home')
                        ->with('error', 'Booking đã bị hủy nên giao dịch này không còn hợp lệ.');
                }

                // resultCode == 0 nghĩa là giao dịch thành công theo chuẩn MoMo
                if ($resultCode == 0) {
                    // Thanh toán thành công -> confirm trên DB
                    $booking->update([
                        'payment_status' => 'paid',
                        'admin_confirmed' => false,
                        'payment_method' => 'momo',
                        // Mượn cột vnp_transaction_no để lưu mã gd MoMo (transId)
                        'vnp_transaction_no' => $inputData['transId'] ?? null,
                    ]);

                    return redirect()->route('user.booking.success', $bookingId)
                        ->with('success', 'Thanh toán MoMo tự động thành công!');
                } else {
                    return redirect()->route('payment.choose', $bookingId)
                        ->with('error', 'Thanh toán MoMo thất bại/huỷ bỏ. Mã: ' . $resultCode . ' - ' . ($inputData['message'] ?? ''));
                }
            }
        }

        // Chữ ký sai lệch
        return redirect()->route('home')->with('error', 'Cảnh báo: Chữ ký xác thực MoMo không hợp lệ!');
    }

    /**
     * Hiển thị trang giả lập thanh toán MoMo (Mock Mode)
     */
    public function momoMock($booking_id)
    {
        $booking = \App\Models\Booking::findOrFail($booking_id);
        return view('user.payment-momo-mock', compact('booking'));
    }
}
