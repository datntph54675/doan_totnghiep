@extends('layouts.user')

@section('title', 'Đặt tour - ' . $tour->name)

@push('styles')
    <style>
        /* ====== BOOKING HERO ====== */
        .booking-hero {
            background: linear-gradient(135deg, #005bb5 0%, var(--primary) 40%, #0ea5e9 100%);
            padding: 0;
            position: relative;
            overflow: hidden;
            min-height: 220px;
            display: flex;
            align-items: flex-end;
        }

        .booking-hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='80' height='80' viewBox='0 0 80 80' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Cpath d='M50 50c0-5.523 4.477-10 10-10s10 4.477 10 10-4.477 10-10 10c0 5.523-4.477 10-10 10s-10-4.477-10-10 4.477-10 10-10zM10 10c0-5.523 4.477-10 10-10s10 4.477 10 10-4.477 10-10 10c0 5.523-4.477 10-10 10S0 25.523 0 20s4.477-10 10-10z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        .booking-hero-float {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.07);
            animation: floatBubble 8s ease-in-out infinite;
        }

        .booking-hero-float:nth-child(1) {
            width: 200px;
            height: 200px;
            top: -60px;
            right: 10%;
            animation-delay: 0s;
        }

        .booking-hero-float:nth-child(2) {
            width: 120px;
            height: 120px;
            bottom: -30px;
            right: 25%;
            animation-delay: 2s;
        }

        .booking-hero-float:nth-child(3) {
            width: 80px;
            height: 80px;
            top: 20px;
            left: 8%;
            animation-delay: 4s;
        }

        @keyframes floatBubble {

            0%,
            100% {
                transform: translateY(0) scale(1);
            }

            50% {
                transform: translateY(-15px) scale(1.05);
            }
        }

        .booking-hero-content {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 50px 20px 40px;
        }

        .booking-breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: rgba(255, 255, 255, 0.75);
            margin-bottom: 16px;
        }

        .booking-breadcrumb a {
            color: rgba(255, 255, 255, 0.75);
            transition: color 0.2s;
        }

        .booking-breadcrumb a:hover {
            color: #fff;
        }

        .booking-breadcrumb .sep {
            opacity: 0.5;
        }

        .booking-hero-title {
            font-size: 30px;
            font-weight: 800;
            color: #fff;
            margin-bottom: 8px;
            line-height: 1.3;
        }

        .booking-hero-sub {
            font-size: 15px;
            color: rgba(255, 255, 255, 0.85);
            display: flex;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
        }

        .booking-hero-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.25);
            border-radius: 20px;
            padding: 4px 12px;
            font-size: 13px;
            backdrop-filter: blur(4px);
        }

        /* ====== STEPS INDICATOR ====== */
        .booking-steps {
            background: #fff;
            border-bottom: 1px solid var(--border);
            padding: 0;
            position: sticky;
            top: 68px;
            z-index: 100;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }

        .booking-steps-inner {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            align-items: center;
            gap: 0;
        }

        .step-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 16px 24px;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-light);
            position: relative;
            white-space: nowrap;
            border-bottom: 3px solid transparent;
        }

        .step-item.active {
            color: var(--primary);
            border-bottom-color: var(--primary);
        }

        .step-item.done {
            color: var(--success);
        }

        .step-num {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 700;
            background: #f1f5f9;
            color: var(--text-light);
            transition: all 0.2s;
        }

        .step-item.active .step-num {
            background: var(--primary);
            color: #fff;
        }

        .step-item.done .step-num {
            background: var(--success);
            color: #fff;
        }

        .step-arrow {
            color: var(--border);
            font-size: 12px;
            margin: 0 4px;
        }

        /* ====== LAYOUT ====== */
        .booking-layout {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px 80px;
            display: grid;
            grid-template-columns: 1fr 380px;
            gap: 32px;
            align-items: start;
        }

        @media (max-width: 1024px) {
            .booking-layout {
                grid-template-columns: 1fr;
            }
        }

        /* ====== FORM CARDS ====== */
        .bk-card {
            background: #fff;
            border-radius: 20px;
            border: 1px solid var(--border);
            overflow: hidden;
            margin-bottom: 24px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
            transition: box-shadow 0.2s;
        }

        .bk-card:hover {
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.1);
        }

        .bk-card-head {
            padding: 20px 28px;
            display: flex;
            align-items: center;
            gap: 14px;
            border-bottom: 1px solid #f1f5f9;
            background: linear-gradient(135deg, #fafaff, #f5f8ff);
        }

        .bk-card-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: var(--primary-light);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .bk-card-head-text h3 {
            font-size: 16px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 2px;
        }

        .bk-card-head-text p {
            font-size: 12px;
            color: var(--text-light);
        }

        .bk-card-body {
            padding: 28px;
        }

        /* ====== SCHEDULE SELECTOR ====== */
        .schedule-grid {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-bottom: 20px;
        }

        .schedule-option {
            border: 2px solid var(--border);
            border-radius: 14px;
            padding: 14px 18px;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            position: relative;
            overflow: hidden;
        }

        .schedule-option::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: var(--primary);
            border-radius: 4px 0 0 4px;
            opacity: 0;
            transition: opacity 0.2s;
        }

        .schedule-option:hover {
            border-color: var(--primary);
            background: var(--primary-light);
        }

        .schedule-option.selected {
            border-color: var(--primary);
            background: #f0f8ff;
        }

        .schedule-option.selected::before {
            opacity: 1;
        }

        .schedule-option.disabled-opt {
            opacity: 0.5;
            cursor: not-allowed;
            background: #f8f9fa;
        }

        .schedule-option input[type=radio] {
            display: none;
        }

        .schedule-date-info {
            flex: 1;
        }

        .schedule-date-main {
            font-size: 15px;
            font-weight: 700;
            color: var(--text-dark);
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 4px;
        }

        .schedule-date-arrow {
            color: var(--primary);
            font-size: 12px;
        }

        .schedule-date-sub {
            font-size: 12px;
            color: var(--text-light);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .schedule-spot-badge {
            padding: 4px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .spot-ok {
            background: #ecfdf5;
            color: #059669;
        }

        .spot-few {
            background: #fff7ed;
            color: #ea580c;
        }

        .spot-none {
            background: #fef2f2;
            color: #dc2626;
        }

        /* ====== NGƯỜI SỐ PEOPLE ====== */
        .people-selector {
            display: flex;
            align-items: center;
            gap: 0;
            border: 2px solid var(--border);
            border-radius: 14px;
            overflow: hidden;
            width: fit-content;
            transition: border-color 0.2s;
        }

        .people-selector:focus-within {
            border-color: var(--primary);
        }

        .people-btn {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--primary-light);
            color: var(--primary);
            font-size: 20px;
            font-weight: 700;
            cursor: pointer;
            border: none;
            transition: background 0.2s;
            user-select: none;
        }

        .people-btn:hover {
            background: var(--primary);
            color: #fff;
        }

        .people-input {
            width: 80px;
            text-align: center;
            font-size: 18px;
            font-weight: 700;
            border: none;
            color: var(--text-dark);
            background: transparent;
            padding: 0;
            outline: none;
            -moz-appearance: textfield;
        }

        .people-input::-webkit-outer-spin-button,
        .people-input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            appearance: none;
            margin: 0;
        }

        /* ====== SPOT INDICATOR ====== */
        .spot-indicator-bar {
            margin-top: 14px;
            padding: 12px 16px;
            border-radius: 12px;
            border: 1px solid;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13px;
            font-weight: 600;
            display: none;
        }

        .spot-indicator-bar.ok {
            background: #f0fdf4;
            border-color: #bbf7d0;
            color: #15803d;
        }

        .spot-indicator-bar.warn {
            background: #fff7ed;
            border-color: #fed7aa;
            color: #c2410c;
        }

        .spot-indicator-bar.error {
            background: #fef2f2;
            border-color: #fecaca;
            color: #dc2626;
        }

        .spot-progress {
            flex: 1;
            height: 6px;
            border-radius: 3px;
            background: rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .spot-progress-fill {
            height: 100%;
            border-radius: 3px;
            transition: width 0.4s ease;
        }

        .ok .spot-progress-fill {
            background: #22c55e;
        }

        .warn .spot-progress-fill {
            background: #f97316;
        }

        .error .spot-progress-fill {
            background: #ef4444;
        }

        /* ====== FORM FIELDS ====== */
        .fi-group {
            margin-bottom: 20px;
        }

        .fi-label {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-mid);
            margin-bottom: 8px;
        }

        .fi-label .req {
            color: #ef4444;
        }

        .fi-label .hint {
            font-size: 11px;
            font-weight: 400;
            color: var(--text-light);
            background: #f1f5f9;
            padding: 2px 8px;
            border-radius: 4px;
            margin-left: auto;
        }

        .fi-control {
            width: 100%;
            padding: 12px 16px;
            border: 1.5px solid var(--border);
            border-radius: 12px;
            font-size: 14px;
            color: var(--text-dark);
            font-family: 'Inter', sans-serif;
            transition: all 0.2s;
            outline: none;
            background: #fff;
        }

        .fi-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(0, 102, 204, 0.1);
        }

        .fi-control.prefilled {
            background: #f8fcff;
            border-color: rgba(0, 102, 204, 0.3);
        }

        .fi-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
        }

        @media (max-width: 560px) {
            .fi-row {
                grid-template-columns: 1fr;
            }
        }

        .fi-error {
            color: #ef4444;
            font-size: 12px;
            margin-top: 6px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .autofill-notice {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #f0f8ff;
            border: 1px solid rgba(0, 102, 204, 0.2);
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 12px;
            color: var(--primary);
            margin-bottom: 22px;
        }

        /* ====== SUBMIT BUTTON ====== */
        .btn-book {
            width: 100%;
            padding: 18px;
            background: linear-gradient(135deg, var(--primary) 0%, #0284c7 100%);
            color: #fff;
            border: none;
            border-radius: 16px;
            font-size: 17px;
            font-weight: 800;
            cursor: pointer;
            transition: all 0.25s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 6px 20px rgba(0, 102, 204, 0.35);
            letter-spacing: 0.3px;
            position: relative;
            overflow: hidden;
        }

        .btn-book::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.15), transparent);
            pointer-events: none;
        }

        .btn-book:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(0, 102, 204, 0.45);
        }

        .btn-book:disabled {
            opacity: 0.55;
            cursor: not-allowed;
            transform: none;
        }

        .btn-book-hint {
            text-align: center;
            font-size: 12px;
            color: var(--text-light);
            margin-top: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
        }

        /* ====== SIDEBAR ====== */
        .bk-sidebar {
            position: sticky;
            top: 130px;
        }

        .bk-summary-card {
            background: #fff;
            border-radius: 20px;
            border: 1px solid var(--border);
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .bk-summary-img {
            width: 100%;
            height: 220px;
            object-fit: cover;
        }

        .bk-summary-img-placeholder {
            width: 100%;
            height: 220px;
            background: linear-gradient(135deg, var(--primary) 0%, #0ea5e9 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 56px;
        }

        .bk-summary-body {
            padding: 22px;
        }

        .bk-summary-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: var(--primary-light);
            color: var(--primary);
            border-radius: 6px;
            padding: 3px 10px;
            font-size: 11px;
            font-weight: 700;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .bk-summary-name {
            font-size: 17px;
            font-weight: 800;
            color: var(--text-dark);
            line-height: 1.4;
            margin-bottom: 18px;
        }

        .bk-info-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #f1f5f9;
            font-size: 14px;
        }

        .bk-info-row:last-of-type {
            border-bottom: none;
        }

        .bk-info-label {
            color: var(--text-light);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .bk-info-value {
            font-weight: 600;
            color: var(--text-dark);
        }

        .bk-price-box {
            background: linear-gradient(135deg, #f0f8ff, #e8f2ff);
            border: 1px solid rgba(0, 102, 204, 0.15);
            border-radius: 16px;
            padding: 20px;
            margin-top: 18px;
        }

        .bk-price-row {
            display: flex;
            justify-content: space-between;
            font-size: 14px;
            color: var(--text-mid);
            margin-bottom: 10px;
            align-items: center;
        }

        .bk-price-row.total {
            border-top: 1px dashed rgba(0, 102, 204, 0.25);
            padding-top: 12px;
            margin-top: 8px;
            margin-bottom: 0;
        }

        .bk-price-row.total .label {
            font-size: 15px;
            font-weight: 700;
            color: var(--text-dark);
        }

        .bk-price-row.total .amount {
            font-size: 22px;
            font-weight: 800;
            color: var(--primary);
        }

        .bk-trust {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-top: 18px;
            padding-top: 16px;
            border-top: 1px solid var(--border);
        }

        .bk-trust-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            color: var(--text-mid);
        }

        .bk-trust-item i {
            color: var(--success);
            font-size: 13px;
        }

        /* ====== EMPTY SCHEDULES ====== */
        .bk-empty {
            text-align: center;
            padding: 40px 20px;
        }

        .bk-empty-icon {
            font-size: 48px;
            margin-bottom: 12px;
        }

        .bk-empty h4 {
            font-size: 16px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 6px;
        }

        .bk-empty p {
            font-size: 14px;
            color: var(--text-light);
        }

        /* ====== POLICY NOTE ====== */
        .policy-note {
            background: #fffbeb;
            border: 1px solid #fde68a;
            border-radius: 12px;
            padding: 14px 18px;
            margin-bottom: 20px;
            font-size: 13px;
            color: #92400e;
            display: flex;
            gap: 10px;
            align-items: flex-start;
        }
    </style>
@endpush

@section('content')
    {{-- HERO --}}
    <section class="booking-hero">
        <div class="booking-hero-float"></div>
        <div class="booking-hero-float"></div>
        <div class="booking-hero-float"></div>
        <div class="booking-hero-content">
            <div class="booking-breadcrumb">
                <a href="{{ route('home') }}"><i class="fa-solid fa-house"></i> Trang chủ</a>
                <span class="sep">/</span>
                <a href="{{ route('tours.index') }}">Danh sách tour</a>
                <span class="sep">/</span>
                <a href="{{ route('tours.show', $tour->tour_id) }}">{{ Str::limit($tour->name, 30) }}</a>
                <span class="sep">/</span>
                <span style="color:#fff">Đặt tour</span>
            </div>
            <div class="booking-hero-title">🎒 Đặt tour ngay</div>
            <div class="booking-hero-sub">
                <span class="booking-hero-tag">
                    <i class="fa-solid fa-route"></i> {{ $tour->name }}
                </span>
                @if ($tour->duration)
                    <span class="booking-hero-tag">
                        <i class="fa-solid fa-clock"></i> {{ $tour->duration }} ngày
                    </span>
                @endif
                <span class="booking-hero-tag">
                    <i class="fa-solid fa-tag"></i> {{ number_format($tour->price, 0, ',', '.') }} ₫/người
                </span>
            </div>
        </div>
    </section>

    {{-- ALERTS --}}
    <div class="container" style="margin-top: 20px;">
        @if (session('error'))
            <div
                style="background: #fff5f5; color: #c53030; border: 1px solid #feb2b2; padding: 18px 24px; border-radius: 16px; margin-bottom: 0; display: flex; align-items: flex-start; gap: 14px; font-size: 15px; box-shadow: 0 4px 12px rgba(229, 62, 62, 0.08);">
                <i class="fa-solid fa-circle-exclamation" style="margin-top: 3px; font-size: 18px;"></i>
                <div>
                    <strong style="display: block; margin-bottom: 4px;">Thông báo hệ thống</strong>
                    {{ session('error') }}
                    @if (str_contains(session('error'), 'đơn hàng chờ thanh toán'))
                        <a href="{{ route('user.bookings') }}"
                            style="display: inline-block; margin-top: 8px; color: #c53030; font-weight: 700; text-decoration: underline;">Xem
                            đơn hàng của bạn</a>
                    @endif
                </div>
            </div>
        @endif
    </div>

    {{-- STEPS --}}
    <div class="booking-steps">
        <div class="booking-steps-inner">
            <div class="step-item active">
                <div class="step-num">1</div>
                <span>Thông tin đặt tour</span>
            </div>
            <span class="step-arrow"><i class="fa-solid fa-chevron-right"></i></span>
            <div class="step-item">
                <div class="step-num">2</div>
                <span>Xác nhận & Thanh toán</span>
            </div>
            <span class="step-arrow"><i class="fa-solid fa-chevron-right"></i></span>
            <div class="step-item">
                <div class="step-num">3</div>
                <span>Hoàn tất</span>
            </div>
        </div>
    </div>

    {{-- MAIN LAYOUT --}}
    <div class="booking-layout">
        {{-- LEFT: FORM --}}
        <div>
            <form method="POST" action="{{ route('user.booking.store', $tour->tour_id) }}" id="bookingForm">
                @csrf

                {{-- CARD 1: CHỌN LỊCH KHỞI HÀNH --}}
                <div class="bk-card">
                    <div class="bk-card-head">
                        <div class="bk-card-icon">📅</div>
                        <div class="bk-card-head-text">
                            <h3>Chọn ngày khởi hành</h3>
                            <p>Chọn lịch trình phù hợp với kế hoạch của bạn</p>
                        </div>
                    </div>
                    <div class="bk-card-body">
                        @if ($schedules->count() > 0)
                            <div class="schedule-grid" id="scheduleGrid">
                                @foreach ($schedules as $s)
                                    @php
                                        $spots = $s->available_spots;
                                        $spotClass = $spots > 5 ? 'spot-ok' : ($spots > 0 ? 'spot-few' : 'spot-none');
                                        $spotText = $spots > 0 ? "Còn {$spots} chỗ" : 'Hết chỗ';
                                        $duration = $s->start_date->diffInDays($s->end_date) + 1;
                                    @endphp
                                    <label
                                        class="schedule-option {{ $spots <= 0 ? 'disabled-opt' : '' }} {{ old('schedule_id') == $s->schedule_id ? 'selected' : '' }}"
                                        data-id="{{ $s->schedule_id }}" data-spots="{{ $spots }}"
                                        for="sch_{{ $s->schedule_id }}">
                                        <input type="radio" id="sch_{{ $s->schedule_id }}" name="schedule_id"
                                            value="{{ $s->schedule_id }}"
                                            {{ old('schedule_id') == $s->schedule_id ? 'checked' : '' }}
                                            {{ $spots <= 0 ? 'disabled' : '' }}>
                                        <div class="schedule-date-info">
                                            <div class="schedule-date-main">
                                                <i class="fa-solid fa-calendar-day"
                                                    style="color:var(--primary);font-size:14px"></i>
                                                {{ $s->start_date->format('d/m/Y') }}
                                                <span class="schedule-date-arrow">→</span>
                                                {{ $s->end_date->format('d/m/Y') }}
                                            </div>
                                            <div class="schedule-date-sub">
                                                <span><i class="fa-solid fa-sun" style="color:#f59e0b"></i>
                                                    {{ $duration }} ngày {{ $duration - 1 }} đêm</span>
                                                @if ($s->meeting_point)
                                                    <span><i class="fa-solid fa-map-pin" style="color:#ef4444"></i>
                                                        {{ $s->meeting_point }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <span class="schedule-spot-badge {{ $spotClass }}">{{ $spotText }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @error('schedule_id')
                                <div class="fi-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>
                            @enderror

                            {{-- SỐ NGƯỜI --}}
                            <div style="border-top: 1px solid #f1f5f9; padding-top: 20px; margin-top: 4px;">
                                <div class="fi-label" style="margin-bottom:14px;">
                                    <i class="fa-solid fa-users" style="color:var(--primary)"></i>
                                    Số lượng người tham gia <span class="req">*</span>
                                </div>
                                <div style="display:flex; align-items:center; gap:24px; flex-wrap:wrap;">
                                    <div class="people-selector">
                                        <button type="button" class="people-btn" id="btnMinus">−</button>
                                        <input type="number" name="num_people" id="numPeople" class="people-input"
                                            value="{{ old('num_people', 1) }}" min="1" max="50" readonly>
                                        <button type="button" class="people-btn" id="btnPlus">+</button>
                                    </div>
                                    <div style="font-size:13px; color:var(--text-light)">
                                        <div id="pricePerPerson">Giá/người:
                                            <strong>{{ number_format($tour->price, 0, ',', '.') }} ₫</strong></div>
                                    </div>
                                </div>

                                {{-- SPOT INDICATOR --}}
                                <div class="spot-indicator-bar" id="spotBar">
                                    <i class="fa-solid fa-circle-info" id="spotIcon"></i>
                                    <div style="flex:1">
                                        <div id="spotText" style="margin-bottom:6px"></div>
                                        <div class="spot-progress">
                                            <div class="spot-progress-fill" id="spotFill" style="width:0%"></div>
                                        </div>
                                    </div>
                                </div>
                                @error('num_people')
                                    <div class="fi-error" style="margin-top:10px"><i
                                            class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>
                                @enderror
                            </div>
                        @else
                            <div class="bk-empty">
                                <div class="bk-empty-icon">😔</div>
                                <h4>Chưa có lịch khởi hành</h4>
                                <p>Hiện chưa có lịch khởi hành nào khả dụng cho tour này.<br>Vui lòng quay lại sau hoặc liên
                                    hệ chúng tôi.</p>
                            </div>
                        @endif
                    </div>
                </div>

                @if ($schedules->count() > 0)
                    {{-- CARD 2: THÔNG TIN LIÊN HỆ --}}
                    <div class="bk-card">
                        <div class="bk-card-head">
                            <div class="bk-card-icon">👤</div>
                            <div class="bk-card-head-text">
                                <h3>Thông tin người liên hệ</h3>
                                <p>Thông tin này dùng để xác nhận và liên hệ về chuyến đi</p>
                            </div>
                        </div>
                        <div class="bk-card-body">
                            @if ($user)
                                <div class="autofill-notice">
                                    <i class="fa-solid fa-circle-check"></i>
                                    Chúng tôi đã tự động điền thông tin từ tài khoản của bạn. Bạn có thể chỉnh sửa nếu cần.
                                </div>
                            @endif

                            <div class="fi-row">
                                <div class="fi-group">
                                    <div class="fi-label">
                                        Họ và tên <span class="req">*</span>
                                    </div>
                                    <input type="text" name="fullname"
                                        class="fi-control {{ $user ? 'prefilled' : '' }}"
                                        value="{{ old('fullname', $user->fullname ?? '') }}" placeholder="Nguyễn Văn An"
                                        required>
                                    @error('fullname')
                                        <div class="fi-error"><i class="fa-solid fa-circle-exclamation"></i>
                                            {{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="fi-group">
                                    <div class="fi-label">Giới tính <span class="req">*</span></div>
                                    <select name="gender" class="fi-control" required>
                                        <option value="">-- Chọn giới tính --</option>
                                        <option value="Nam" {{ old('gender', 'Nam') == 'Nam' ? 'selected' : '' }}>Nam
                                        </option>
                                        <option value="Nữ" {{ old('gender') == 'Nữ' ? 'selected' : '' }}>Nữ</option>
                                        <option value="Khác" {{ old('gender') == 'Khác' ? 'selected' : '' }}>Khác
                                        </option>
                                    </select>
                                    @error('gender')
                                        <div class="fi-error"><i class="fa-solid fa-circle-exclamation"></i>
                                            {{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="fi-row">
                                <div class="fi-group">
                                    <div class="fi-label">
                                        Số điện thoại <span class="req">*</span>
                                    </div>
                                    <input type="tel" name="phone"
                                        class="fi-control {{ $user ? 'prefilled' : '' }}"
                                        value="{{ old('phone', $user->phone ?? '') }}" placeholder="0901 234 567"
                                        required>
                                    @error('phone')
                                        <div class="fi-error"><i class="fa-solid fa-circle-exclamation"></i>
                                            {{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="fi-group">
                                    <div class="fi-label">
                                        Email <span class="req">*</span>
                                    </div>
                                    <input type="email" name="email"
                                        class="fi-control {{ $user ? 'prefilled' : '' }}"
                                        value="{{ old('email', $user->email ?? '') }}" placeholder="example@email.com"
                                        required>
                                    @error('email')
                                        <div class="fi-error"><i class="fa-solid fa-circle-exclamation"></i>
                                            {{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="fi-row">
                                <div class="fi-group">
                                    <div class="fi-label">
                                        Ngày sinh
                                        <span class="hint">Không bắt buộc</span>
                                    </div>
                                    <input type="date" name="birthdate" class="fi-control"
                                        value="{{ old('birthdate') }}">
                                    @error('birthdate')
                                        <div class="fi-error"><i class="fa-solid fa-circle-exclamation"></i>
                                            {{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="fi-group">
                                    <div class="fi-label">
                                        Số CCCD / Hộ chiếu
                                        <span class="hint">Không bắt buộc</span>
                                    </div>
                                    <input type="text" name="id_number" class="fi-control"
                                        value="{{ old('id_number') }}" placeholder="012 345 678 901">
                                    @error('id_number')
                                        <div class="fi-error"><i class="fa-solid fa-circle-exclamation"></i>
                                            {{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="fi-group">
                                <div class="fi-label">
                                    Yêu cầu đặc biệt
                                    <span class="hint">Không bắt buộc</span>
                                </div>
                                <textarea name="note" class="fi-control" rows="3"
                                    placeholder="Ví dụ: Ăn chay, dị ứng hải sản, cần xe lăn, đón tại điểm khác...">{{ old('note') }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- POLICY --}}
                    <div class="policy-note">
                        <i class="fa-solid fa-shield-halved" style="font-size:18px; margin-top:2px; flex-shrink:0"></i>
                        <div>
                            <strong>Chính sách đặt tour:</strong> Sau khi xác nhận đặt, đội ngũ GoTour sẽ liên hệ với bạn
                            trong vòng 2 giờ để xác nhận lịch trình và hướng dẫn thanh toán.
                        </div>
                    </div>

                    {{-- SUBMIT --}}
                    <button type="submit" class="btn-book" id="submitBtn">
                        <i class="fa-solid fa-check-circle"></i>
                        Xác nhận đặt tour ngay
                        <i class="fa-solid fa-arrow-right"></i>
                    </button>
                    <div class="btn-book-hint">
                        <i class="fa-solid fa-lock" style="color:var(--success)"></i>
                        Thông tin của bạn được bảo mật tuyệt đối
                    </div>
                @endif
            </form>
        </div>

        {{-- RIGHT: SIDEBAR --}}
        <div class="bk-sidebar">
            <div class="bk-summary-card">
                @if ($tour->image)
                    <img src="{{ asset('storage/' . $tour->image) }}" alt="{{ $tour->name }}" class="bk-summary-img">
                @else
                    <div class="bk-summary-img-placeholder">✈️</div>
                @endif
                <div class="bk-summary-body">
                    <div class="bk-summary-badge">
                        <i class="fa-solid fa-star"></i> Tour đặc sắc
                    </div>
                    <div class="bk-summary-name">{{ $tour->name }}</div>

                    <div class="bk-info-row">
                        <span class="bk-info-label"><i class="fa-solid fa-clock"></i> Thời gian</span>
                        <span class="bk-info-value">{{ $tour->duration ?? '—' }} ngày</span>
                    </div>
                    <div class="bk-info-row">
                        <span class="bk-info-label"><i class="fa-solid fa-layer-group"></i> Loại</span>
                        <span class="bk-info-value">{{ $tour->category->name ?? 'Tour Du lịch' }}</span>
                    </div>
                    <div class="bk-info-row">
                        <span class="bk-info-label"><i class="fa-solid fa-barcode"></i> Mã tour</span>
                        <span class="bk-info-value"
                            style="font-family:monospace;letter-spacing:0.5px">VT-{{ str_pad($tour->tour_id, 4, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div class="bk-info-row">
                        <span class="bk-info-label"><i class="fa-solid fa-user-tie"></i> Hỗ trợ 24/7</span>
                        <span class="bk-info-value" style="color:var(--success)">1900 xxxx</span>
                    </div>

                    <div class="bk-price-box">
                        <div class="bk-price-row">
                            <span class="label">Giá vé / người</span>
                            <span style="font-weight:700">{{ number_format($tour->price, 0, ',', '.') }} ₫</span>
                        </div>
                        <div class="bk-price-row">
                            <span class="label">Số lượng</span>
                            <span id="sidebarPeople" style="font-weight:700">1 người</span>
                        </div>
                        <div class="bk-price-row total">
                            <span class="label">💰 Tổng tiền dự kiến</span>
                            <span class="amount" id="sidebarTotal">{{ number_format($tour->price, 0, ',', '.') }}
                                ₫</span>
                        </div>
                    </div>

                    <div class="bk-trust">
                        <div class="bk-trust-item">
                            <i class="fa-solid fa-shield-halved"></i> Đảm bảo giá tốt nhất
                        </div>
                        <div class="bk-trust-item">
                            <i class="fa-solid fa-rotate-left"></i> Hoàn tiền nếu hủy trước 48h
                        </div>
                        <div class="bk-trust-item">
                            <i class="fa-solid fa-headset"></i> Hỗ trợ khách hàng 24/7
                        </div>
                        <div class="bk-trust-item">
                            <i class="fa-solid fa-certificate"></i> Tour được kiểm duyệt chất lượng
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="booking-meta" data-price="{{ $tour->price }}" style="display:none">
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const price = parseInt(document.getElementById('booking-meta').dataset.price) || 0;
            const numInput = document.getElementById('numPeople');
            const btnMinus = document.getElementById('btnMinus');
            const btnPlus = document.getElementById('btnPlus');
            const submitBtn = document.getElementById('submitBtn');
            const spotBar = document.getElementById('spotBar');
            const spotText = document.getElementById('spotText');
            const spotFill = document.getElementById('spotFill');
            const spotIcon = document.getElementById('spotIcon');
            const sidebarPeople = document.getElementById('sidebarPeople');
            const sidebarTotal = document.getElementById('sidebarTotal');

            // --- Schedule radio click ---
            let selectedSpots = 0;
            document.querySelectorAll('.schedule-option:not(.disabled-opt)').forEach(function(label) {
                label.addEventListener('click', function() {
                    document.querySelectorAll('.schedule-option').forEach(l => l.classList.remove(
                        'selected'));
                    this.classList.add('selected');
                    this.querySelector('input[type=radio]').checked = true;
                    selectedSpots = parseInt(this.dataset.spots) || 0;
                    updateAll();
                });
            });

            // Set initial selected spots if old value
            const checkedRadio = document.querySelector('input[name=schedule_id]:checked');
            if (checkedRadio) {
                const lbl = checkedRadio.closest('.schedule-option');
                if (lbl) {
                    lbl.classList.add('selected');
                    selectedSpots = parseInt(lbl.dataset.spots) || 0;
                }
            }

            // --- People +/- buttons ---
            btnMinus && btnMinus.addEventListener('click', function() {
                const v = parseInt(numInput.value) || 1;
                if (v > 1) {
                    numInput.value = v - 1;
                    updateAll();
                }
            });
            btnPlus && btnPlus.addEventListener('click', function() {
                const v = parseInt(numInput.value) || 1;
                if (v < 50) {
                    numInput.value = v + 1;
                    updateAll();
                }
            });
            numInput && numInput.addEventListener('input', updateAll);

            function updateAll() {
                const n = parseInt(numInput.value) || 1;

                // Sidebar
                sidebarPeople.textContent = n + ' người';
                sidebarTotal.textContent = (price * n).toLocaleString('vi-VN') + ' ₫';

                // Spot bar
                if (!spotBar) return;
                const radioChecked = document.querySelector('input[name=schedule_id]:checked');
                if (!radioChecked) {
                    spotBar.style.display = 'none';
                    return;
                }

                spotBar.style.display = 'flex';
                const pct = selectedSpots > 0 ? Math.min(100, Math.round((n / selectedSpots) * 100)) : 100;
                spotFill.style.width = pct + '%';

                spotBar.className = 'spot-indicator-bar';
                if (selectedSpots <= 0) {
                    spotBar.classList.add('error');
                    spotText.textContent = 'Lịch khởi hành này đã hết chỗ.';
                    if (submitBtn) {
                        submitBtn.disabled = true;
                    }
                } else if (n > selectedSpots) {
                    spotBar.classList.add('warn');
                    spotText.textContent = `Chỉ còn ${selectedSpots} chỗ — bạn đang chọn nhiều hơn số chỗ trống!`;
                    if (submitBtn) {
                        submitBtn.disabled = true;
                    }
                } else {
                    spotBar.classList.add('ok');
                    spotText.textContent = `Còn ${selectedSpots - n + n} chỗ trống · Bạn đặt ${n} người · Phù hợp!`;
                    spotText.textContent = `Còn ${selectedSpots} chỗ trống · Đang chọn ${n} người ✓`;
                    if (submitBtn) {
                        submitBtn.disabled = false;
                    }
                }
            }

            updateAll();
        });
    </script>
@endpush
