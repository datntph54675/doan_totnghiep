<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Services\VnpayService;
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

            if ($booking && $vnp_ResponseCode === '00') {
                // Thanh toán thành công
                $booking->update([
                    'payment_status' => 'paid',
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

        $booking->update([
            'payment_status' => 'paid',
            'payment_method' => 'vietqr',
        ]);

        return redirect()->route('user.booking.success', $bookingId)
            ->with('success', 'Xác nhận chuyển khoản thành công! Admin sẽ kiểm tra và xác nhận đơn hàng của bạn.');
    }
}
