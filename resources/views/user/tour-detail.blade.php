@extends('user.layout')

@section('title', $tour->name . ' - Chi tiết tour')

@push('styles')
<style>
    /* HERO */
    .hero {
        position: relative;
        height: 420px;
        background: linear-gradient(135deg, #0ea5e9 0%, #6366f1 100%);
        overflow: hidden;
        display: flex; align-items: flex-end;
    }
    .hero img {
        position: absolute; inset: 0;
        width: 100%; height: 100%; object-fit: cover;
        opacity: .55;
    }
    .hero-content {
        position: relative; z-index: 2;
        padding: 40px;
        color: #fff;
        width: 100%;
    }
    .hero-badge {
        display: inline-block;
        background: rgba(255,255,255,.2);
        border: 1px solid rgba(255,255,255,.4);
        backdrop-filter: blur(6px);
        padding: 4px 14px; border-radius: 20px;
        font-size: 13px; font-weight: 600;
        margin-bottom: 12px;
    }
    .hero-title { font-size: 36px; font-weight: 800; line-height: 1.2; margin-bottom: 10px; text-shadow: 0 2px 8px rgba(0,0,0,.3); }
    .hero-meta { display: flex; gap: 20px; flex-wrap: wrap; font-size: 15px; opacity: .9; }
    .hero-meta span { display: flex; align-items: center; gap: 6px; }

    /* LAYOUT */
    .page-body { max-width: 1100px; margin: 0 auto; padding: 40px 20px; display: grid; grid-template-columns: 1fr 340px; gap: 30px; }
    @media(max-width:768px){ .page-body { grid-template-columns: 1fr; } }

    /* CARD */
    .card { background: #fff; border-radius: 14px; border: 1px solid #e2e8f0; overflow: hidden; margin-bottom: 24px; }
    .card-header { padding: 18px 24px; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; gap: 10px; }
    .card-header h3 { font-size: 17px; font-weight: 700; color: #1e293b; }
    .card-body { padding: 24px; }

    /* INFO GRID */
    .info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 16px; }
    .info-item { background: #f8fafc; border-radius: 10px; padding: 14px 16px; }
    .info-label { font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: .5px; margin-bottom: 6px; }
    .info-value { font-size: 16px; font-weight: 700; color: #1e293b; }
    .info-value.price { color: #0ea5e9; font-size: 20px; }

    /* DESCRIPTION */
    .desc-text { font-size: 15px; line-height: 1.8; color: #475569; }

    /* ITINERARY TIMELINE */
    .timeline { position: relative; padding-left: 40px; }
    .timeline::before { content:''; position:absolute; left:16px; top:0; bottom:0; width:2px; background:#e2e8f0; }
    .timeline-item { position: relative; margin-bottom: 28px; }
    .timeline-dot {
        position: absolute; left: -32px; top: 4px;
        width: 32px; height: 32px; border-radius: 50%;
        background: linear-gradient(135deg, #0ea5e9, #6366f1);
        color: #fff; font-size: 12px; font-weight: 700;
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 2px 8px rgba(14,165,233,.3);
    }
    .timeline-title { font-size: 15px; font-weight: 700; color: #1e293b; margin-bottom: 4px; }
    .timeline-desc { font-size: 14px; color: #64748b; line-height: 1.6; }
    .timeline-meta { display: flex; gap: 12px; margin-top: 6px; flex-wrap: wrap; }
    .timeline-meta span { font-size: 12px; color: #94a3b8; display: flex; align-items: center; gap: 4px; }

    /* SCHEDULE TABLE */
    .schedule-table { width: 100%; border-collapse: collapse; font-size: 14px; }
    .schedule-table th { background: #f8fafc; padding: 10px 14px; text-align: left; font-size: 12px; font-weight: 700; color: #94a3b8; text-transform: uppercase; }
    .schedule-table td { padding: 12px 14px; border-top: 1px solid #f1f5f9; }
    .schedule-table tr:hover td { background: #f8fafc; }

    /* STICKY SIDEBAR */
    .sidebar { position: sticky; top: 84px; height: fit-content; }
    .price-card { background: linear-gradient(135deg, #0ea5e9, #6366f1); border-radius: 14px; padding: 28px; color: #fff; margin-bottom: 20px; }
    .price-card .price-label { font-size: 13px; opacity: .8; margin-bottom: 6px; }
    .price-card .price-amount { font-size: 36px; font-weight: 800; margin-bottom: 4px; }
    .price-card .price-per { font-size: 13px; opacity: .7; margin-bottom: 24px; }
    .btn-book {
        display: block; width: 100%;
        background: #fff; color: #0ea5e9;
        border: none; border-radius: 10px;
        padding: 14px; font-size: 16px; font-weight: 700;
        cursor: pointer; text-align: center; text-decoration: none;
        transition: transform .15s, box-shadow .15s;
    }
    .btn-book:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(0,0,0,.15); }

    .quick-info { background: #fff; border-radius: 14px; border: 1px solid #e2e8f0; padding: 20px; }
    .quick-info-item { display: flex; align-items: center; gap: 12px; padding: 10px 0; border-bottom: 1px solid #f1f5f9; }
    .quick-info-item:last-child { border-bottom: none; }
    .quick-info-icon { width: 36px; height: 36px; border-radius: 8px; background: #f0f9ff; display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0; }
    .quick-info-text .label { font-size: 11px; color: #94a3b8; font-weight: 600; text-transform: uppercase; }
    .quick-info-text .value { font-size: 14px; font-weight: 600; color: #1e293b; }

    /* BADGE */
    .badge { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; }
    .badge-blue { background: #dbeafe; color: #1d4ed8; }
    .badge-green { background: #dcfce7; color: #15803d; }
    .badge-gray { background: #f1f5f9; color: #64748b; }

    /* POLICY */
    .policy-box { background: #fefce8; border: 1px solid #fde68a; border-radius: 10px; padding: 16px 20px; font-size: 14px; color: #92400e; line-height: 1.7; }

    /* BACK */
    .back-link { display: inline-flex; align-items: center; gap: 6px; color: #64748b; text-decoration: none; font-size: 14px; padding: 10px 0 0 20px; }
    .back-link:hover { color: #0ea5e9; }
</style>
@endpush

@section('content')

<a href="{{ route('user.tours') }}" class="back-link">← Quay lại danh sách tour</a>

{{-- HERO --}}
<div class="hero">
    @if($tour->image_url)
        <img src="{{ $tour->image_url }}" alt="{{ $tour->name }}">
    @endif
    <div class="hero-content">
        <div class="container">
            @if($tour->category)
            <div class="hero-badge">{{ $tour->category->name }}</div>
            @endif
            <div class="hero-title">{{ $tour->name }}</div>
            <div class="hero-meta">
                @if($tour->duration)
                <span>🕐 {{ $tour->duration }} ngày</span>
                @endif
                @if($tour->max_people)
                <span>👥 Tối đa {{ $tour->max_people }} người</span>
                @endif
                @if($tour->supplier)
                <span>🏢 {{ $tour->supplier }}</span>
                @endif
                <span>💰 {{ number_format($tour->price, 0, ',', '.') }} ₫/người</span>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    {{-- LEFT COLUMN --}}
    <div>
        {{-- THÔNG TIN TỔNG QUAN --}}
        <div class="card">
            <div class="card-header">
                <span style="font-size:20px">📋</span>
                <h3>Thông tin tổng quan</h3>
            </div>
            <div class="card-body">
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Giá tour</div>
                        <div class="info-value price">{{ number_format($tour->price, 0, ',', '.') }} ₫</div>
                    </div>
                    @if($tour->duration)
                    <div class="info-item">
                        <div class="info-label">Thời gian</div>
                        <div class="info-value">{{ $tour->duration }} ngày</div>
                    </div>
                    @endif
                    @if($tour->max_people)
                    <div class="info-item">
                        <div class="info-label">Số người tối đa</div>
                        <div class="info-value">{{ $tour->max_people }} người</div>
                    </div>
                    @endif
                    @if($tour->category)
                    <div class="info-item">
                        <div class="info-label">Danh mục</div>
                        <div class="info-value">{{ $tour->category->name }}</div>
                    </div>
                    @endif
                    @if($tour->supplier)
                    <div class="info-item">
                        <div class="info-label">Nhà cung cấp</div>
                        <div class="info-value">{{ $tour->supplier }}</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- MÔ TẢ --}}
        @if($tour->description)
        <div class="card">
            <div class="card-header">
                <span style="font-size:20px">📖</span>
                <h3>Mô tả tour</h3>
            </div>
            <div class="card-body">
                <p class="desc-text">{{ $tour->description }}</p>
            </div>
        </div>
        @endif

        {{-- LỊCH TRÌNH --}}
        @if($itineraries->count() > 0)
        <div class="card">
            <div class="card-header">
                <span style="font-size:20px">🗓️</span>
                <h3>Lịch trình chi tiết</h3>
                <span class="badge badge-blue" style="margin-left:auto">{{ $itineraries->count() }} ngày</span>
            </div>
            <div class="card-body">
                <div class="timeline">
                    @foreach($itineraries as $item)
                    <div class="timeline-item">
                        <div class="timeline-dot">{{ $item->day_number }}</div>
                        <div class="timeline-title">
                            Ngày {{ $item->day_number }}
                            @if($item->title) — {{ $item->title }} @endif
                        </div>
                        @if($item->description)
                        <div class="timeline-desc">{{ $item->description }}</div>
                        @endif
                        <div class="timeline-meta">
                            @if($item->location)
                            <span>📍 {{ $item->location }}</span>
                            @endif
                            @if($item->time_start)
                            <span>🕐 {{ $item->time_start }} @if($item->time_end) – {{ $item->time_end }} @endif</span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        {{-- LỊCH KHỞI HÀNH --}}
        @if($schedules->count() > 0)
        <div class="card">
            <div class="card-header">
                <span style="font-size:20px">📅</span>
                <h3>Lịch khởi hành sắp tới</h3>
                <span class="badge badge-green" style="margin-left:auto">{{ $schedules->count() }} chuyến</span>
            </div>
            <div class="card-body" style="padding:0">
                <table class="schedule-table">
                    <thead>
                        <tr>
                            <th>Ngày khởi hành</th>
                            <th>Ngày kết thúc</th>
                            <th>Điểm tập trung</th>
                            <th>Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($schedules as $s)
                        <tr>
                            <td style="font-weight:600">{{ $s->start_date->format('d/m/Y') }}</td>
                            <td>{{ $s->end_date->format('d/m/Y') }}</td>
                            <td>{{ $s->meeting_point ?? '—' }}</td>
                            <td><span class="badge badge-green">Còn chỗ</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        {{-- CHÍNH SÁCH --}}
        @if($tour->policy)
        <div class="card">
            <div class="card-header">
                <span style="font-size:20px">📜</span>
                <h3>Chính sách tour</h3>
            </div>
            <div class="card-body">
                <div class="policy-box">{{ $tour->policy }}</div>
            </div>
        </div>
        @endif
    </div>

    {{-- RIGHT SIDEBAR --}}
    <div class="sidebar">
        <div class="price-card">
            <div class="price-label">Giá chỉ từ</div>
            <div class="price-amount">{{ number_format($tour->price, 0, ',', '.') }} ₫</div>
            <div class="price-per">/ người</div>
            <a href="{{ route('user.booking', $tour->tour_id) }}" class="btn-book">🎒 Đặt tour ngay</a>
        </div>

        <div class="quick-info">
            @if($tour->duration)
            <div class="quick-info-item">
                <div class="quick-info-icon">🕐</div>
                <div class="quick-info-text">
                    <div class="label">Thời gian</div>
                    <div class="value">{{ $tour->duration }} ngày</div>
                </div>
            </div>
            @endif
            @if($tour->max_people)
            <div class="quick-info-item">
                <div class="quick-info-icon">👥</div>
                <div class="quick-info-text">
                    <div class="label">Số người tối đa</div>
                    <div class="value">{{ $tour->max_people }} người</div>
                </div>
            </div>
            @endif
            @if($tour->category)
            <div class="quick-info-item">
                <div class="quick-info-icon">🏷️</div>
                <div class="quick-info-text">
                    <div class="label">Danh mục</div>
                    <div class="value">{{ $tour->category->name }}</div>
                </div>
            </div>
            @endif
            <div class="quick-info-item">
                <div class="quick-info-icon">📅</div>
                <div class="quick-info-text">
                    <div class="label">Lịch khởi hành</div>
                    <div class="value">{{ $schedules->count() }} chuyến sắp tới</div>
                </div>
            </div>
            @if($tour->supplier)
            <div class="quick-info-item">
                <div class="quick-info-icon">🏢</div>
                <div class="quick-info-text">
                    <div class="label">Nhà cung cấp</div>
                    <div class="value">{{ $tour->supplier }}</div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@endsection
