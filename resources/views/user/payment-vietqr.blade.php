@extends('layouts.user')

@section('title', 'Chuyển khoản VietQR - VietTour')

@push('styles')
<style>
    .vietqr-page {
        background: #f8fafc;
        min-height: calc(100vh - 80px);
        padding: 50px 0;
    }
    .vietqr-container {
        max-width: 800px;
        margin: 0 auto;
        padding: 0 20px;
    }
    .vietqr-header {
        text-align: center;
        margin-bottom: 35px;
    }
    .vietqr-header h1 {
        font-size: 26px;
        font-weight: 800;
        color: #1a1a2e;
        margin-bottom: 10px;
    }
    .vietqr-header p {
        color: #636e72;
        font-size: 15px;
    }

    .qr-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.06);
        overflow: hidden;
        margin-bottom: 25px;
    }
    .qr-card-header {
        background: linear-gradient(135deg, #0066cc, #00aaff);
        color: #fff;
        padding: 20px 30px;
        display: flex;
        align-items: center;
        gap: 12px;
        font-weight: 700;
        font-size: 16px;
    }
    .qr-card-body {
        padding: 35px;
    }
    .qr-layout {
        display: grid;
        grid-template-columns: 280px 1fr;
        gap: 35px;
        align-items: start;
    }

    .qr-image-wrap {
        text-align: center;
    }
    .qr-image-wrap img {
        width: 260px;
        height: auto;
        border-radius: 12px;
        border: 2px solid #e8ecf1;
    }
    .qr-scan-text {
        margin-top: 12px;
        font-size: 13px;
        color: #636e72;
    }

    .bank-details {
        padding: 0;
    }
    .bank-details h3 {
        font-size: 17px;
        font-weight: 700;
        color: #1a1a2e;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .bank-details h3 i { color: #0066cc; }

    .detail-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 14px 0;
        border-bottom: 1px solid #f1f2f6;
    }
    .detail-row:last-child {
        border-bottom: none;
    }
    .detail-label {
        font-size: 14px;
        color: #636e72;
    }
    .detail-value {
        font-size: 14px;
        font-weight: 700;
        color: #1a1a2e;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .detail-value.highlight {
        color: #0066cc;
        font-size: 18px;
    }
    .detail-value.content-code {
        background: #fff9db;
        color: #d69e2e;
        padding: 6px 14px;
        border-radius: 8px;
        font-size: 15px;
        font-weight: 800;
        letter-spacing: 1px;
    }

    .copy-btn {
        background: #e6f2ff;
        border: none;
        color: #0066cc;
        padding: 6px 12px;
        border-radius: 8px;
        cursor: pointer;
        font-size: 12px;
        font-weight: 600;
        transition: all 0.2s;
    }
    .copy-btn:hover {
        background: #0066cc;
        color: #fff;
    }

    .warning-box {
        background: #fffbeb;
        border: 1px solid #fde68a;
        border-radius: 14px;
        padding: 18px 22px;
        font-size: 13px;
        color: #92400e;
        line-height: 1.7;
        margin-bottom: 25px;
    }
    .warning-box strong { color: #78350f; }
    .warning-box i { margin-right: 8px; }

    .steps-section {
        background: #fff;
        border-radius: 16px;
        padding: 30px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.04);
        margin-bottom: 25px;
    }
    .steps-section h3 {
        font-size: 16px;
        font-weight: 700;
        color: #1a1a2e;
        margin-bottom: 20px;
    }
    .step-list {
        list-style: none;
        padding: 0;
        counter-reset: step-counter;
    }
    .step-list li {
        counter-increment: step-counter;
        padding: 12px 0 12px 50px;
        position: relative;
        font-size: 14px;
        color: #4a5568;
        border-bottom: 1px solid #f7f8fa;
    }
    .step-list li:last-child { border-bottom: none; }
    .step-list li::before {
        content: counter(step-counter);
        position: absolute;
        left: 0;
        top: 12px;
        width: 32px;
        height: 32px;
        background: linear-gradient(135deg, #0066cc, #00aaff);
        color: #fff;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 14px;
    }

    .btn-actions {
        display: flex;
        gap: 15px;
        margin-top: 10px;
    }
    .btn-confirm {
        flex: 1;
        background: linear-gradient(135deg, #00b894, #00cec9);
        color: #fff;
        border: none;
        padding: 16px 25px;
        border-radius: 14px;
        font-size: 16px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }
    .btn-confirm:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0,184,148,0.3);
    }
    .btn-back {
        background: #fff;
        color: #636e72;
        border: 2px solid #e8ecf1;
        padding: 16px 25px;
        border-radius: 14px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .btn-back:hover {
        border-color: #0066cc;
        color: #0066cc;
    }

    @media (max-width: 768px) {
        .qr-layout {
            grid-template-columns: 1fr;
        }
        .qr-image-wrap { order: -1; }
        .btn-actions { flex-direction: column; }
    }
</style>
@endpush

@section('content')
<div class="vietqr-page">
    <div class="vietqr-container">
        <div class="vietqr-header">
            <h1>📱 Chuyển khoản ngân hàng</h1>
            <p>Quét mã QR hoặc chuyển khoản theo thông tin bên dưới</p>
        </div>

        <!-- Thẻ QR chính -->
        <div class="qr-card">
            <div class="qr-card-header">
                <i class="fa-solid fa-qrcode"></i>
                Đơn hàng #{{ $booking->booking_id }} - {{ $booking->tour->name }}
            </div>
            <div class="qr-card-body">
                <div class="qr-layout">
                    <!-- Mã QR -->
                    <div class="qr-image-wrap">
                        <img src="{{ $qrUrl }}" alt="Mã QR Chuyển khoản VietTour">
                        <div class="qr-scan-text">Mở app ngân hàng → Quét mã QR</div>
                    </div>

                    <!-- Thông tin chuyển khoản -->
                    <div class="bank-details">
                        <h3><i class="fa-solid fa-building-columns"></i> Thông tin chuyển khoản</h3>

                        <div class="detail-row">
                            <span class="detail-label">Ngân hàng</span>
                            <span class="detail-value">
                                <strong>MB Bank</strong>
                            </span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Số tài khoản</span>
                            <span class="detail-value">
                                {{ $bankInfo['account_no'] }}
                                <button class="copy-btn" onclick="copyText('{{ $bankInfo['account_no'] }}')">
                                    <i class="fa-solid fa-copy"></i> Sao chép
                                </button>
                            </span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Chủ tài khoản</span>
                            <span class="detail-value">{{ $bankInfo['account_name'] }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Số tiền</span>
                            <span class="detail-value highlight">
                                {{ number_format($amount, 0, ',', '.') }} ₫
                            </span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Nội dung CK</span>
                            <span class="detail-value content-code">
                                {{ $transferContent }}
                                <button class="copy-btn" onclick="copyText('{{ $transferContent }}')">
                                    <i class="fa-solid fa-copy"></i>
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cảnh báo -->
        <div class="warning-box">
            <i class="fa-solid fa-triangle-exclamation"></i>
            <strong>Lưu ý quan trọng:</strong> Vui lòng nhập chính xác nội dung chuyển khoản <strong>"{{ $transferContent }}"</strong> để hệ thống có thể xác nhận giao dịch của bạn. Nếu sai nội dung, việc xác nhận có thể bị chậm trễ.
        </div>

        <!-- Hướng dẫn -->
        <div class="steps-section">
            <h3>📋 Hướng dẫn thanh toán</h3>
            <ol class="step-list">
                <li>Mở ứng dụng ngân hàng trên điện thoại (MB Bank, Vietcombank, BIDV...)</li>
                <li>Chọn <strong>"Quét mã QR"</strong> và quét mã bên trên (hoặc nhập thông tin thủ công)</li>
                <li>Kiểm tra số tiền và nội dung chuyển khoản đúng như hiển thị</li>
                <li>Xác nhận chuyển khoản và bấm nút <strong>"Tôi đã chuyển khoản"</strong> bên dưới</li>
            </ol>
        </div>

        <!-- Nút hành động -->
        <div class="btn-actions">
            <a href="{{ route('payment.choose', $booking->booking_id) }}" class="btn-back">
                <i class="fa-solid fa-arrow-left"></i> Đổi phương thức
            </a>
            <form action="{{ route('payment.vietqr.confirm', $booking->booking_id) }}" method="POST" style="flex: 1;">
                @csrf
                <button type="submit" class="btn-confirm" onclick="return confirm('Bạn xác nhận đã chuyển khoản thành công?')">
                    <i class="fa-solid fa-circle-check"></i>
                    Tôi đã chuyển khoản
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function copyText(text) {
    navigator.clipboard.writeText(text).then(() => {
        alert('Đã sao chép: ' + text);
    });
}
</script>
@endsection
