@extends('layouts.user')

@section('title', 'Liên hệ - GoTour')
@section('meta_description', 'Liên hệ GoTour — hotline, email và form gửi tin nhắn. Chúng tôi phản hồi nhanh trong giờ
    hành chính.')

@section('styles')
    <style>
        .contact-hero {
            position: relative;
            padding: 96px 0 72px;
            background: linear-gradient(160deg, #f0f9ff 0%, #e0f2fe 35%, #fff 100%);
            overflow: hidden;
        }

        .contact-hero::before {
            content: '';
            position: absolute;
            top: -80px;
            right: -60px;
            width: 420px;
            height: 420px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(0, 102, 204, .15) 0%, transparent 65%);
            pointer-events: none;
        }

        .contact-hero-inner {
            position: relative;
            z-index: 1;
            max-width: 640px;
        }

        .contact-hero-inner .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: .8rem;
            font-weight: 700;
            color: var(--primary);
            background: var(--primary-light);
            padding: 6px 14px;
            border-radius: 50px;
            margin-bottom: 14px;
        }

        .contact-hero-inner h1 {
            font-size: clamp(1.85rem, 4vw, 2.75rem);
            font-weight: 800;
            color: var(--text-dark);
            margin-bottom: 12px;
            line-height: 1.2;
        }

        .contact-hero-inner p {
            color: var(--text-mid);
            font-size: 1.02rem;
            line-height: 1.75;
        }

        .contact-section {
            padding: 0 0 96px;
            margin-top: -24px;
        }

        .contact-grid {
            display: grid;
            grid-template-columns: 1fr 1.15fr;
            gap: 36px;
            align-items: start;
        }

        .contact-cards {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .contact-card {
            background: #fff;
            border-radius: var(--radius-md);
            padding: 22px 22px 22px 24px;
            border: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
            display: flex;
            gap: 18px;
            align-items: flex-start;
            transition: box-shadow var(--transition), transform var(--transition);
        }

        .contact-card:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
        }

        .contact-card-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            flex-shrink: 0;
            background: linear-gradient(135deg, var(--primary-light), #dbeafe);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-size: 1.1rem;
        }

        .contact-card h3 {
            font-size: .95rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 4px;
        }

        .contact-card p,
        .contact-card a {
            font-size: .875rem;
            color: var(--text-mid);
            line-height: 1.55;
        }

        .contact-card a {
            color: var(--primary);
            font-weight: 600;
        }

        .contact-card a:hover {
            text-decoration: underline;
        }

        .contact-map {
            margin-top: 8px;
            border-radius: var(--radius-md);
            overflow: hidden;
            border: 1px solid var(--border);
            min-height: 200px;
            background: linear-gradient(135deg, #e8f2ff 0%, #fef3c7 100%);
            position: relative;
        }

        .contact-map-inner {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 8px;
            color: var(--text-mid);
            font-size: .9rem;
        }

        .contact-map-inner i {
            font-size: 2rem;
            color: var(--primary);
            opacity: .85;
        }

        .form-panel {
            background: #fff;
            border-radius: var(--radius-lg);
            padding: 36px 36px 40px;
            border: 1px solid var(--border);
            box-shadow: 0 20px 50px rgba(15, 23, 42, .08);
        }

        .form-panel-header {
            margin-bottom: 26px;
        }

        .form-panel-header h2 {
            font-size: 1.35rem;
            font-weight: 800;
            color: var(--text-dark);
            margin-bottom: 6px;
        }

        .form-panel-header p {
            font-size: .9rem;
            color: var(--text-light);
        }

        .form-row-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            font-size: .8rem;
            font-weight: 600;
            color: var(--text-mid);
            margin-bottom: 8px;
        }

        .form-group label .req {
            color: #dc2626;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 12px 16px;
            border-radius: var(--radius-sm);
            border: 1.5px solid var(--border);
            font-family: inherit;
            font-size: .92rem;
            color: var(--text-dark);
            background: var(--bg-light);
            transition: border-color var(--transition), box-shadow var(--transition), background var(--transition);
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--primary);
            background: #fff;
            box-shadow: 0 0 0 3px rgba(0, 102, 204, .12);
        }

        .form-group textarea {
            min-height: 140px;
            resize: vertical;
            line-height: 1.6;
        }

        .form-error {
            font-size: .78rem;
            color: #b91c1c;
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .form-error i {
            font-size: .7rem;
        }

        .alert-form-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
            padding: 12px 16px;
            border-radius: var(--radius-sm);
            font-size: .88rem;
            margin-bottom: 20px;
        }

        .form-submit-wrap {
            margin-top: 8px;
        }

        .form-submit {
            width: 100%;
            padding: 14px 24px;
            border-radius: var(--radius-sm);
            background: linear-gradient(135deg, var(--accent) 0%, var(--accent-dark) 100%);
            color: #fff;
            font-weight: 700;
            font-size: .95rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: transform var(--transition), box-shadow var(--transition);
        }

        .form-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 28px rgba(255, 107, 43, .35);
        }

        .form-note {
            font-size: .78rem;
            color: var(--text-light);
            margin-top: 14px;
            text-align: center;
        }

        @media (max-width: 992px) {
            .contact-grid {
                grid-template-columns: 1fr;
            }

            .form-row-2 {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection

@section('content')

    <section class="contact-hero">
        <div class="container">
            <div class="contact-hero-inner">
                <div class="eyebrow"><i class="fas fa-paper-plane"></i> Liên hệ</div>
                <h1>Chúng tôi luôn sẵn sàng lắng nghe bạn</h1>
                <p>Gửi câu hỏi về tour, yêu cầu tư vấn lịch trình hoặc phản hồi dịch vụ — đội ngũ GoTour sẽ phản hồi trong
                    thời gian sớm nhất có thể.</p>
            </div>
        </div>
    </section>

    <section class="contact-section">
        <div class="container">
            <div class="contact-grid">
                <div class="contact-cards">
                    <div class="contact-card">
                        <div class="contact-card-icon"><i class="fas fa-phone-alt"></i></div>
                        <div>
                            <h3>Hotline</h3>
                            <p><a href="tel:1900xxxx">1900 xxxx</a> (8:00 – 22:00 hằng ngày)</p>
                        </div>
                    </div>
                    <div class="contact-card">
                        <div class="contact-card-icon"><i class="fas fa-envelope"></i></div>
                        <div>
                            <h3>Email</h3>
                            <p><a href="mailto:info@GoTour.vn">info@GoTour.vn</a><br>Hỗ trợ đặt tour &amp; hợp tác</p>
                        </div>
                    </div>
                    <div class="contact-card">
                        <div class="contact-card-icon"><i class="fas fa-map-marker-alt"></i></div>
                        <div>
                            <h3>Văn phòng</h3>
                            <p>Đường ABC, Quận XYZ<br>Hà Nội, Việt Nam</p>
                        </div>
                    </div>
                    <div class="contact-map">
                        <div class="contact-map-inner">
                            <i class="fas fa-map"></i>
                            <span>Bản đồ (có thể nhúng Google Maps sau)</span>
                        </div>
                    </div>
                </div>

                <div class="form-panel">
                    <div class="form-panel-header">
                        <h2>Gửi tin nhắn</h2>
                        <p>Điền thông tin bên dưới — các trường có dấu <span style="color:#dc2626">*</span> là bắt buộc.</p>
                    </div>

                    @if ($errors->any())
                        <div class="alert-form-error">
                            <strong>Vui lòng kiểm tra lại:</strong>
                            <ul style="margin:8px 0 0 18px;">
                                @foreach ($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('contact.submit') }}" method="POST" novalidate>
                        @csrf
                        <div class="form-row-2">
                            <div class="form-group">
                                <label for="fullname">Họ và tên <span class="req">*</span></label>
                                <input type="text" id="fullname" name="fullname" value="{{ old('fullname') }}"
                                    placeholder="Nguyễn Văn A" autocomplete="name">
                                @error('fullname')
                                    <div class="form-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="phone">Số điện thoại</label>
                                <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                                    placeholder="09xx xxx xxx" autocomplete="tel">
                                @error('phone')
                                    <div class="form-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email">Email <span class="req">*</span></label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}"
                                placeholder="ban@email.com" autocomplete="email">
                            @error('email')
                                <div class="form-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="subject">Tiêu đề <span class="req">*</span></label>
                            <input type="text" id="subject" name="subject" value="{{ old('subject') }}"
                                placeholder="Ví dụ: Tư vấn tour Hạ Long 3 ngày">
                            @error('subject')
                                <div class="form-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="message">Nội dung <span class="req">*</span></label>
                            <textarea id="message" name="message" placeholder="Mô tả nhu cầu, số người, thời gian dự kiến...">{{ old('message') }}</textarea>
                            @error('message')
                                <div class="form-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-submit-wrap">
                            <button type="submit" class="form-submit">
                                <i class="fas fa-paper-plane"></i> Gửi tin nhắn
                            </button>
                            <p class="form-note">Bằng việc gửi form, bạn đồng ý để chúng tôi liên hệ lại qua thông tin đã
                                cung cấp.</p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

@endsection
