@extends('layouts.app')

@section('title', 'Giả lập Thanh toán MoMo')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <!-- Simulated MoMo App Interface -->
            <div class="card border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
                <div class="card-header border-0 py-4 text-center" style="background: linear-gradient(135deg, #ae2070, #d82d8b); color: white;">
                    <img src="https://developers.momo.vn/v2/images/logo.png" alt="MoMo Logo" style="height: 50px; margin-bottom: 10px; background: white; padding: 5px; border-radius: 8px;">
                    <h4 class="mb-0 fw-bold">Ví MoMo</h4>
                    <p class="mb-0 opacity-75 small">Cổng thanh toán giả lập (Mock Mode)</p>
                </div>
                
                <div class="card-body p-4 bg-light">
                    <!-- Price Card -->
                    <div class="bg-white p-4 mb-4 text-center shadow-sm" style="border-radius: 15px;">
                        <p class="text-muted mb-1 small uppercase tracking-wider">TỔNG THANH TOÁN</p>
                        <h2 class="fw-bold mb-0" style="color: #ae2070;">{{ number_format($booking->total_price, 0, ',', '.') }} đ</h2>
                    </div>

                    <div class="mb-4">
                        <label class="text-muted small mb-2 d-block">THÔNG TIN ĐƠN HÀNG</label>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Mã đơn hàng:</span>
                            <span class="fw-bold">#{{ $booking->booking_id }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Dịch vụ:</span>
                            <span class="fw-bold">VietTour Booking Center</span>
                        </div>
                    </div>

                    <div class="alert alert-info border-0 shadow-sm mb-4" style="background-color: #fce4ec; border-radius: 12px; color: #880e4f;">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-info-circle me-3 fs-3"></i>
                            <div>
                                <h6 class="mb-0 fw-bold">Chế độ giả lập</h6>
                                <p class="mb-0 small opacity-75">Bạn đang sử dụng môi trường test để hoàn thiện dự án.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Button -->
                    <form action="{{ route('payment.momo.return') }}" method="GET">
                        <!-- Simulated MoMo Response Data -->
                        <input type="hidden" name="partnerCode" value="MOMOBKUN20180529">
                        <input type="hidden" name="orderId" value="{{ $booking->booking_id }}_{{ time() }}">
                        <input type="hidden" name="requestId" value="{{ time() }}">
                        <input type="hidden" name="amount" value="{{ $booking->total_price }}">
                        <input type="hidden" name="orderInfo" value="Thanh toan VietTour #{{ $booking->booking_id }}">
                        <input type="hidden" name="orderType" value="momo_wallet">
                        <input type="hidden" name="transId" value="{{ time() . rand(100, 999) }}">
                        <input type="hidden" name="resultCode" value="0">
                        <input type="hidden" name="message" value="Successful.">
                        <input type="hidden" name="payType" value="qr">
                        <input type="hidden" name="responseTime" value="{{ date('Y-m-d H:i:s') }}">
                        <input type="hidden" name="extraData" value="">
                        <input type="hidden" name="signature" value="mock">
                        
                        <button type="submit" class="btn btn-lg w-100 py-3 mb-3 fw-bold text-white shadow-sm" style="background: #ae2070; border-radius: 12px; transition: all 0.3s;">
                            XÁC NHẬN THANH TOÁN
                        </button>
                    </form>

                    <a href="{{ route('payment.choose', $booking->booking_id) }}" class="btn btn-link w-100 text-muted text-decoration-none small">
                        Hủy giao dịch
                    </a>
                </div>
            </div>

            <div class="text-center mt-4 text-muted small">
                © 2024 Momo Payment Service. Tất cả quyền được bảo lưu.
            </div>
        </div>
    </div>
</div>

<style>
    .btn:hover {
        transform: translateY(-2px);
        filter: brightness(1.1);
    }
    body {
        background-color: #f8f9fa;
    }
</style>
@endsection
