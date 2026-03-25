@extends('layouts.user')

@section('title', $tour->name . ' - VietTour')
@section('meta_description', $tour->description ?? 'Chi tiết tour ' . $tour->name)

@section('styles')
<style>
/* HERO */
.tour-hero {
    position: relative; height: 460px;
    background: linear-gradient(135deg, #0066cc 0%, #004fa3 100%);
    overflow: hidden; display: flex; align-items: flex-end;
    margin-top: 0;
}
.tour-hero img {
    position: absolute; inset: 0;
    width: 100%; height: 100%; object-fit: cover; opacity: .5;
}
.tour-hero-content {
    position: relative; z-index: 2;
    padding: 40px 0; color: #fff; width: 100%;
}
.hero-badge {
    display: inline-block;
    background: rgba(255,255,255,.2); border: 1px solid rgba(255,255,255,.4);
    backdrop-filter: blur(6px); padding: 4px 14px; border-radius: 20px;
    font-size: 13px; font-weight: 600; margin-bottom: 12px;
}
.hero-title { font-size: 2.2rem; font-weight: 800; line-height: 1.2; margin-bottom: 12px; text-shadow: 0 2px 8px rgba(0,0,0,.3); }
.hero-meta { display: flex; gap: 20px; flex-wrap: wrap; font-size: 15px; opacity: .9; }
.hero-meta span { display: flex; align-items: center; gap: 6px; }

/* LAYOUT */
.detail-body { display: grid; grid-template-columns: 1fr 340px; gap: 32px; padding: 40px 0 80px; }
@media(max-width: 900px) { .detail-body { grid-template-columns: 1fr; } }

/* CARD */
.d-card { background: #fff; border-radius: var(--radius-md); border: 1px solid var(--border); overflow: hidden; margin-bottom: 24px; box-shadow: var(--shadow-sm); }
.d-card-header { padding: 18px 24px; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; gap: 10px; }
.d-card-header h3 { font-size: 1rem; font-weight: 700; color: var(--text-dark); }
.d-card-body { padding: 24px; }

/* INFO GRID */
.info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 14px; }
.info-item { background: var(--bg-light); border-radius: var(--radius-sm); padding: 14px 16px; }
.info-label { font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: .5px; margin-bottom: 6px; }
.info-value { font-size: 1rem; font-weight: 700; color: var(--text-dark); }
.info-value.price { color: var(--accent); font-size: 1.3rem; }

/* TIMELINE */
.timeline { position: relative; padding-left: 40px; }
.timeline::before { content:''; position:absolute; left:16px; top:0; bottom:0; width:2px; background: var(--border); }
.timeline-item { position: relative; margin-bottom: 28px; }
.timeline-dot {
    position: absolute; left: -32px; top: 4px;
    width: 32px; height: 32px; border-radius: 50%;
    background: linear-gradient(135deg, var(--primary), #6366f1);
    color: #fff; font-size: 12px; font-weight: 700;
    display: flex; align-items: center; justify-content: center;
    box-shadow: 0 2px 8px rgba(0,102,204,.3);
}
.timeline-title { font-size: .95rem; font-weight: 700; color: var(--text-dark); margin-bottom: 4px; }
.timeline-desc { font-size: .875rem; color: var(--text-mid); line-height: 1.6; }
.timeline-meta { display: flex; gap: 12px; margin-top: 6px; flex-wrap: wrap; }
.timeline-meta span { font-size: .75rem; color: var(--text-light); display: flex; align-items: center; gap: 4px; }

/* SCHEDULE TABLE */
.schedule-table { width: 100%; border-collapse: collapse; font-size: .875rem; }
.schedule-table th { background: var(--bg-light); padding: 10px 14px; text-align: left; font-size: .75rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; }
.schedule-table td { padding: 12px 14px; border-top: 1px solid #f1f5f9; }
.schedule-table tr:hover td { background: var(--bg-light); }

/* POLICY */
.policy-box { background: #fefce8; border: 1px solid #fde68a; border-radius: var(--radius-sm); padding: 16px 20px; font-size: .875rem; color: #92400e; line-height: 1.7; }

/* SIDEBAR */
.detail-sidebar { position: sticky; top: 88px; height: fit-content; }
.price-card {
    background: linear-gradient(135deg, var(--primary), #6366f1);
    border-radius: var(--radius-md); padding: 28px; color: #fff; margin-bottom: 20px;
}
.price-card .price-label { font-size: .8rem; opacity: .8; margin-bottom: 4px; }
.price-card .price-amount { font-size: 2.2rem; font-weight: 800; margin-bottom: 2px; }
.price-card .price-per { font-size: .8rem; opacity: .7; margin-bottom: 24px; }
.btn-book {
    display: block; width: 100%;
    background: #fff; color: var(--primary);
    border: none; border-radius: var(--radius-sm);
    padding: 14px; font-size: 1rem; font-weight: 700;
    cursor: pointer; text-align: center; text-decoration: none;
    transition: transform .15s, box-shadow .15s;
}
.btn-book:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(0,0,0,.2); }

.quick-info { background: #fff; border-radius: var(--radius-md); border: 1px solid var(--border); padding: 20px; box-shadow: var(--shadow-sm); }
.quick-info-item { display: flex; align-items: center; gap: 12px; padding: 10px 0; border-bottom: 1px solid #f1f5f9; }
.quick-info-item:last-child { border-bottom: none; }
.quick-info-icon { width: 36px; height: 36px; border-radius: 8px; background: var(--primary-light); display: flex; align-items: center; justify-content: center; font-size: 1.1rem; flex-shrink: 0; }
.quick-info-text .ql { font-size: .7rem; color: #94a3b8; font-weight: 600; text-transform: uppercase; }
.quick-info-text .qv { font-size: .875rem; font-weight: 600; color: var(--text-dark); }

/* RELATED */
.related-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 20px; }

/* BREADCRUMB */
.breadcrumb-bar { background: var(--bg-light); border-bottom: 1px solid var(--border); padding: 12px 0; }
.breadcrumb { display: flex; align-items: center; gap: 8px; font-size: .8rem; color: var(--text-light); }
.breadcrumb a { color: var(--primary); }
.breadcrumb a:hover { text-decoration: underline; }
</style>
@endsection

@section('content')

<!-- BREADCRUMB -->
<div class="breadcrumb-bar">
    <div class="container">
        <div class="breadcrumb">
            <a href="{{ route('home') }}"><i class="fas fa-home"></i> Trang chủ</a>
            <span>/</span>
            <a href="{{ route('tours.index') }}">Danh sách tour</a>
            <span>/</span>
            <span>{{ $tour->name }}</span>
        </div>
    </div>
</div>

<!-- HERO -->
<div class="tour-hero">
    @if($tour->image)
        <img src="{{ asset('storage/' . $tour->image) }}" alt="{{ $tour->name }}">
    @endif
    <div class="tour-hero-content">
        <div class="container">
            @if($tour->category)
            <div class="hero-badge">{{ $tour->category->name }}</div>
            @endif
            <div class="hero-title">{{ $tour->name }}</div>
            <div class="hero-meta">
                @if($tour->duration)
                <span><i class="fas fa-clock"></i> {{ $tour->duration }} ngày</span>
                @endif
                @if($tour->max_people)
                <span><i class="fas fa-users"></i> Tối đa {{ $tour->max_people }} người</span>
                @endif
                @if($tour->supplier)
                <span><i class="fas fa-building"></i> {{ $tour->supplier }}</span>
                @endif
                <span><i class="fas fa-tag"></i> {{ number_format($tour->price, 0, ',', '.') }} ₫/người</span>
            </div>
        </div>
    </div>
</div>

<!-- BODY -->
<div class="container">
    <div class="detail-body">

        <!-- LEFT -->
        <div>
            <!-- THÔNG TIN TỔNG QUAN -->
            <div class="d-card">
                <div class="d-card-header">
                    <span>📋</span><h3>Thông tin tổng quan</h3>
                </div>
                <div class="d-card-body">
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

            <!-- MÔ TẢ -->
            @if($tour->description)
            <div class="d-card">
                <div class="d-card-header"><span>📖</span><h3>Mô tả tour</h3></div>
                <div class="d-card-body">
                    <p style="font-size:.9rem;line-height:1.8;color:var(--text-mid)">{{ $tour->description }}</p>
                </div>
            </div>
            @endif

            <!-- LỊCH TRÌNH -->
            @if(isset($tour->itineraries) && $tour->itineraries->count() > 0)
            <div class="d-card">
                <div class="d-card-header">
                    <span>🗓️</span><h3>Lịch trình chi tiết</h3>
                    <span class="badge badge-blue" style="margin-left:auto">{{ $tour->itineraries->count() }} ngày</span>
                </div>
                <div class="d-card-body">
                    <div class="timeline">
                        @foreach($tour->itineraries->sortBy('day_number') as $item)
                        <div class="timeline-item">
                            <div class="timeline-dot">{{ $item->day_number }}</div>
                            <div class="timeline-title">
                                Ngày {{ $item->day_number }}@if($item->title) — {{ $item->title }}@endif
                            </div>
                            @if($item->description)
                            <div class="timeline-desc">{{ $item->description }}</div>
                            @endif
                            <div class="timeline-meta">
                                @if($item->location ?? null)
                                <span><i class="fas fa-map-marker-alt"></i> {{ $item->location }}</span>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- LỊCH KHỞI HÀNH -->
            @if($schedules->count() > 0)
            <div class="d-card">
                <div class="d-card-header">
                    <span>📅</span><h3>Lịch khởi hành sắp tới</h3>
                    <span class="badge badge-green" style="margin-left:auto">{{ $schedules->count() }} chuyến</span>
                </div>
                <div class="d-card-body" style="padding:0">
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

            <!-- CHÍNH SÁCH -->
            @if($tour->policy)
            <div class="d-card">
                <div class="d-card-header"><span>📜</span><h3>Chính sách tour</h3></div>
                <div class="d-card-body">
                    <div class="policy-box">{{ $tour->policy }}</div>
                </div>
            </div>
            @endif

            <!-- TOUR LIÊN QUAN -->
            @if(isset($relatedTours) && $relatedTours->count() > 0)
            <div class="d-card">
                <div class="d-card-header"><span>🗺️</span><h3>Tour liên quan</h3></div>
                <div class="d-card-body">
                    <div class="related-grid">
                        @foreach($relatedTours as $r)
                        <a href="{{ route('tours.show', $r->tour_id) }}" class="tour-card">
                            <div class="tour-card-img">
                                @if($r->image)
                                    <img src="{{ asset('storage/' . $r->image) }}" alt="{{ $r->name }}" loading="lazy">
                                @else
                                    <div class="tour-card-img-placeholder"><i class="fas fa-map-marked-alt"></i></div>
                                @endif
                            </div>
                            <div class="tour-card-body">
                                <div class="tour-card-name">{{ $r->name }}</div>
                                <div class="tour-card-footer">
                                    <div class="tour-price">{{ number_format($r->price, 0, ',', '.') }} ₫</div>
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- SIDEBAR -->
        <div class="detail-sidebar">
            <div class="price-card">
                <div class="price-label">Giá chỉ từ</div>
                <div class="price-amount">{{ number_format($tour->price, 0, ',', '.') }} ₫</div>
                <div class="price-per">/ người</div>
                <a href="{{ route('user.booking', $tour->tour_id) }}" class="btn-book">
                    <i class="fas fa-suitcase"></i> Đặt tour ngay
                </a>
            </div>

            <div class="quick-info">
                @if($tour->duration)
                <div class="quick-info-item">
                    <div class="quick-info-icon">⏱</div>
                    <div class="quick-info-text">
                        <div class="ql">Thời gian</div>
                        <div class="qv">{{ $tour->duration }} ngày</div>
                    </div>
                </div>
                @endif
                @if($tour->max_people)
                <div class="quick-info-item">
                    <div class="quick-info-icon">👥</div>
                    <div class="quick-info-text">
                        <div class="ql">Số người tối đa</div>
                        <div class="qv">{{ $tour->max_people }} người</div>
                    </div>
                </div>
                @endif
                @if($tour->category)
                <div class="quick-info-item">
                    <div class="quick-info-icon">🏷️</div>
                    <div class="quick-info-text">
                        <div class="ql">Danh mục</div>
                        <div class="qv">{{ $tour->category->name }}</div>
                    </div>
                </div>
                @endif
                <div class="quick-info-item">
                    <div class="quick-info-icon">📅</div>
                    <div class="quick-info-text">
                        <div class="ql">Lịch khởi hành</div>
                        <div class="qv">{{ $schedules->count() }} chuyến sắp tới</div>
                    </div>
                </div>
                @if($tour->supplier)
                <div class="quick-info-item">
                    <div class="quick-info-icon">🏢</div>
                    <div class="quick-info-text">
                        <div class="ql">Nhà cung cấp</div>
                        <div class="qv">{{ $tour->supplier }}</div>
                    </div>
                </div>
                @endif
            </div>
        </div>

    </div>
</div>

@endsection
