@extends('layouts.user')

@section('title', 'Chọn phương thức thanh toán - VietTour')

@push('styles')
<style>
    .payment-page {
        background: #f8fafc;
        min-height: calc(100vh - 80px);
        padding: 50px 0;
    }
    .payment-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 0 20px;
    }
    .payment-header {
        text-align: center;
        margin-bottom: 40px;
    }
    .payment-header h1 {
        font-size: 28px;
        font-weight: 800;
        color: #1a1a2e;
        margin-bottom: 10px;
    }
    .payment-header p {
        color: #636e72;
        font-size: 15px;
    }

    .order-summary {
        background: #fff;
        border-radius: 16px;
        padding: 25px 30px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.04);
        margin-bottom: 35px;
        border: 1px solid #e8ecf1;
    }
    .order-summary h3 {
        font-size: 16px;
        font-weight: 700;
        color: #1a1a2e;
        margin-bottom: 18px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .order-summary h3 i { color: #0066cc; }
    .summary-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }
    .summary-item {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        font-size: 14px;
    }
    .summary-item .label { color: #636e72; }
    .summary-item .value { font-weight: 600; color: #1a1a2e; }
    .total-row {
        border-top: 2px solid #f1f2f6;
        margin-top: 15px;
        padding-top: 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .total-row .label {
        font-size: 16px;
        font-weight: 700;
        color: #1a1a2e;
    }
    .total-row .value {
        font-size: 24px;
        font-weight: 800;
        color: #0066cc;
    }

    .methods-grid {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 25px;
        margin-bottom: 30px;
    }

    .method-card {
        background: #fff;
        border-radius: 20px;
        padding: 35px 30px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.04);
        border: 2px solid #e8ecf1;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    .method-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 4px;
        background: transparent;
        transition: background 0.3s;
    }
    .method-card:hover {
        border-color: #0066cc;
        transform: translateY(-4px);
        box-shadow: 0 15px 35px rgba(0,102,204,0.12);
    }
    .method-card:hover::before {
        background: linear-gradient(90deg, #0066cc, #00aaff);
    }

    .method-icon {
        width: 80px;
        height: 80px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        font-size: 36px;
    }
    .method-icon.vnpay {
        background: linear-gradient(135deg, #ee2624, #ff6b35);
        color: #fff;
    }
    .method-icon.vietqr {
        background: linear-gradient(135deg, #0066cc, #00aaff);
        color: #fff;
    }
    .method-icon.momo {
        background: linear-gradient(135deg, #ae2070, #d82d8b);
        color: #fff;
    }

    .method-name {
        font-size: 20px;
        font-weight: 800;
        color: #1a1a2e;
        margin-bottom: 10px;
    }
    .method-desc {
        font-size: 14px;
        color: #636e72;
        line-height: 1.6;
        margin-bottom: 25px;
    }
    .method-features {
        list-style: none;
        padding: 0;
        text-align: left;
        margin-bottom: 25px;
    }
    .method-features li {
        padding: 6px 0;
        font-size: 13px;
        color: #4a5568;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .method-features li i {
        color: #00b894;
        font-size: 12px;
    }

    .btn-pay {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        width: 100%;
        padding: 15px 25px;
        border-radius: 14px;
        font-size: 15px;
        font-weight: 700;
        border: none;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
        color: #fff;
    }
    .btn-pay.vnpay-btn {
        background: linear-gradient(135deg, #ee2624, #ff6b35);
    }
    .btn-pay.vnpay-btn:hover {
        box-shadow: 0 8px 20px rgba(238,38,36,0.3);
        transform: translateY(-2px);
    }
    .btn-pay.vietqr-btn {
        background: linear-gradient(135deg, #0066cc, #00aaff);
    }
    .btn-pay.vietqr-btn:hover {
        box-shadow: 0 8px 20px rgba(0,102,204,0.3);
        transform: translateY(-2px);
    }
    .btn-pay.momo-btn {
        background: linear-gradient(135deg, #ae2070, #d82d8b);
    }
    .btn-pay.momo-btn:hover {
        box-shadow: 0 8px 20px rgba(174,32,112,0.3);
        transform: translateY(-2px);
    }

    .security-badge {
        text-align: center;
        color: #b2bec3;
        font-size: 13px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }
    .security-badge i { color: #00b894; }

    @media (max-width: 992px) {
        .methods-grid { grid-template-columns: 1fr 1fr; }
    }
    @media (max-width: 768px) {
        .methods-grid { grid-template-columns: 1fr; }
        .summary-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
<div class="payment-page">
    <div class="payment-container">
        <div class="payment-header">
            <h1>💳 Chọn phương thức thanh toán</h1>
            <p>Hoàn tất thanh toán để xác nhận đặt tour của bạn</p>
        </div>

        @if(session('error'))
            <div style="background: #fff5f5; color: #c53030; border: 1px solid #feb2b2; padding: 15px 20px; border-radius: 14px; margin-bottom: 25px; display: flex; align-items: center; gap: 12px; font-size: 14px;">
                <i class="fa-solid fa-circle-exclamation"></i>
                {{ session('error') }}
            </div>
        @endif

        <!-- Tóm tắt Đơn hàng -->
        <div class="order-summary">
            <h3><i class="fa-solid fa-receipt"></i> Tóm tắt đơn hàng #{{ $booking->booking_id }}</h3>
            <div class="summary-grid">
                <div class="summary-item">
                    <span class="label">Tour</span>
                    <span class="value">{{ $booking->tour->name }}</span>
                </div>
                <div class="summary-item">
                    <span class="label">Ngày khởi hành</span>
                    <span class="value">{{ $booking->schedule->start_date->format('d/m/Y') }}</span>
                </div>
                <div class="summary-item">
                    <span class="label">Số khách</span>
                    <span class="value">{{ $booking->num_people }} người</span>
                </div>
                <div class="summary-item">
                    <span class="label">Khách hàng</span>
                    <span class="value">{{ $booking->customer->fullname }}</span>
                </div>
            </div>
            <div class="total-row">
                <span class="label">Tổng thanh toán</span>
                <span class="value">{{ number_format($booking->total_price, 0, ',', '.') }} ₫</span>
            </div>
        </div>

        <!-- Phương thức thanh toán -->
        <div class="methods-grid">
            <!-- VNPAY -->
            <div class="method-card">
                <div class="method-icon vnpay">
                    <i class="fa-solid fa-credit-card"></i>
                </div>
                <div class="method-name">VNPAY</div>
                <div class="method-desc">Thanh toán qua cổng VNPAY - Hỗ trợ thẻ ATM, Visa, MasterCard và QR Pay</div>
                <ul class="method-features">
                    <li><i class="fa-solid fa-check"></i> Xử lý tự động & Xác nhận tức thì</li>
                    <li><i class="fa-solid fa-check"></i> Hỗ trợ 40+ ngân hàng Việt Nam</li>
                    <li><i class="fa-solid fa-check"></i> Bảo mật tiêu chuẩn PCI DSS</li>
                </ul>
                <form action="{{ route('payment.vnpay', $booking->booking_id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-pay vnpay-btn">
                        <i class="fa-solid fa-lock"></i>
                        Thanh toán VNPAY
                    </button>
                </form>
            </div>

            <!-- VietQR -->
            <div class="method-card">
                <div class="method-icon vietqr">
                    <i class="fa-solid fa-qrcode"></i>
                </div>
                <div class="method-name">VietQR</div>
                <div class="method-desc">Chuyển khoản ngân hàng qua mã QR - Nhanh chóng với mọi ứng dụng ngân hàng</div>
                <ul class="method-features">
                    <li><i class="fa-solid fa-check"></i> Quét QR bằng app ngân hàng bất kỳ</li>
                    <li><i class="fa-solid fa-check"></i> Tự động điền số tiền & nội dung</li>
                    <li><i class="fa-solid fa-check"></i> Không cần đăng ký thêm</li>
                </ul>
                <a href="{{ route('payment.vietqr', $booking->booking_id) }}" class="btn-pay vietqr-btn">
                    <i class="fa-solid fa-qrcode"></i>
                    Chuyển khoản QR
                </a>
            </div>

            <!-- MoMo -->
            <div class="method-card">
                <div class="method-icon momo">
                    <i class="fa-solid fa-wallet"></i>
                </div>
                <div class="method-name">MoMo</div>
                <div class="method-desc">Thanh toán qua ví điện tử MoMo - Nhanh chóng, tiện lợi</div>
                <ul class="method-features">
                    <li><i class="fa-solid fa-check"></i> Quét QR bằng app MoMo</li>
                    <li><i class="fa-solid fa-check"></i> Chuyển tiền chỉ trong vài giây</li>
                    <li><i class="fa-solid fa-check"></i> Phổ biến nhất Việt Nam</li>
                </ul>
                <form action="{{ route('payment.momo', $booking->booking_id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-pay momo-btn">
                        <i class="fa-solid fa-wallet"></i>
                        Thanh toán MoMo
                    </button>
                </form>
            </div>
        </div>

        <div class="security-badge">
            <i class="fa-solid fa-shield-halved"></i>
            Mọi giao dịch được mã hóa SSL và bảo mật tuyệt đối
        </div>
    </div>
</div>
@endsection
