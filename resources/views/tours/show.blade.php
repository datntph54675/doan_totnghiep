@extends('layouts.user')

@section('title', $tour->name . ' - GoTour')
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

/* TIMELINE & ACCORDION */
.itinerary-item { border: 1px solid var(--border); border-radius: 12px; margin-bottom: 16px; overflow: hidden; transition: all 0.3s ease; }
.itinerary-item:hover { border-color: var(--primary); box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
.itinerary-header { padding: 16px 20px; background: #fff; display: flex; align-items: center; cursor: pointer; user-select: none; }
.itinerary-day { width: 40px; height: 40px; background: var(--primary-light); color: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 14px; margin-right: 16px; flex-shrink: 0; }
.itinerary-title { font-weight: 700; color: var(--text-dark); flex-grow: 1; }
.itinerary-toggle { color: var(--text-light); transition: transform 0.3s; }
.itinerary-item.active .itinerary-toggle { transform: rotate(180deg); }
.itinerary-content { padding: 0 20px 20px 72px; display: none; background: #fff; }
.itinerary-item.active .itinerary-content { display: block; }
.itinerary-desc { font-size: 0.95rem; color: var(--text-mid); line-height: 1.7; }

/* STICKY BOOKING BAR */
.sticky-booking-bar {
    position: fixed; bottom: 0; left: 0; right: 0; z-index: 1000;
    background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px);
    border-top: 1px solid var(--border); padding: 12px 0;
    box-shadow: 0 -4px 20px rgba(0,0,0,0.1);
    transform: translateY(100%); transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
}
.sticky-booking-bar.visible { transform: translateY(0); }
.sticky-bar-flex { display: flex; align-items: center; justify-content: space-between; gap: 20px; }
.sticky-tour-info { display: flex; align-items: center; gap: 12px; }
.sticky-tour-info img { width: 50px; height: 50px; border-radius: 8px; object-fit: cover; }
.sticky-tour-name { font-weight: 700; color: var(--text-dark); font-size: 1rem; line-height: 1.2; }
.sticky-price-box { text-align: right; }
.sticky-price-val { font-size: 1.3rem; font-weight: 800; color: var(--accent); }
.sticky-per { font-size: 0.75rem; color: var(--text-light); }

/* SCHEDULE CARDS */
.schedule-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 16px; }
.schedule-card { border: 1px solid var(--border); border-radius: 12px; padding: 16px; background: #fff; transition: all 0.2s; }
.schedule-card:hover { border-color: var(--primary); background: var(--bg-light); }
.sch-dates { display: flex; align-items: center; gap: 10px; margin-bottom: 12px; }
.sch-date-box { font-weight: 700; color: var(--text-dark); }
.sch-arrow { color: var(--text-light); font-size: 0.8rem; }
.sch-info { font-size: 0.85rem; color: var(--text-mid); display: flex; flex-direction: column; gap: 4px; }
.sch-info span { display: flex; align-items: center; gap: 6px; }
.sch-btn { margin-top: 12px; width: 100%; padding: 8px; border-radius: 8px; border: 1px solid var(--primary); background: transparent; color: var(--primary); font-weight: 700; cursor: pointer; transition: all 0.2s; }
.sch-btn:hover { background: var(--primary); color: #fff; }

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
    @if($tour->image_url)
        <img src="{{ $tour->image_url }}" alt="{{ $tour->name }}">
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
            <div class="d-card" id="section-itinerary">
                <div class="d-card-header">
                    <span>🗓️</span><h3>Lịch trình chi tiết</h3>
                    <span class="badge badge-blue" style="margin-left:auto">{{ $tour->itineraries->count() }} ngày</span>
                </div>
                <div class="d-card-body">
                    <div class="itinerary-accordion">
                        @foreach($tour->itineraries->sortBy('day_number') as $item)
                        <div class="itinerary-item @if($loop->first) active @endif">
                            <div class="itinerary-header" onclick="this.parentElement.classList.toggle('active')">
                                <div class="itinerary-day">{{ $item->day_number }}</div>
                                <div class="itinerary-title">Ngày {{ $item->day_number }}: {{ $item->title }}</div>
                                <div class="itinerary-toggle"><i class="fas fa-chevron-down"></i></div>
                            </div>
                            <div class="itinerary-content">
                                <div class="itinerary-desc">{!! nl2br(e($item->description)) !!}</div>
                                @if($item->location)
                                <div class="timeline-meta" style="margin-top:15px">
                                    <span><i class="fas fa-map-marker-alt"></i> {{ $item->location }}</span>
                                </div>
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
            <div class="d-card" id="section-schedules">
                <div class="d-card-header">
                    <span>📅</span><h3>Lịch khởi hành sắp tới</h3>
                    <span class="badge badge-green" style="margin-left:auto">{{ $schedules->count() }} chuyến</span>
                </div>
                <div class="d-card-body">
                    <div class="schedule-grid">
                        @foreach($schedules as $s)
                        <div class="schedule-card">
                            <div class="sch-dates">
                                <div class="sch-date-box"><i class="far fa-calendar-check"></i> {{ $s->start_date->format('d/m/Y') }}</div>
                                <div class="sch-arrow"><i class="fas fa-long-arrow-alt-right"></i></div>
                                <div class="sch-date-box text-muted">{{ $s->end_date->format('d/m/Y') }}</div>
                            </div>
                            <div class="sch-info">
                                <span><i class="fas fa-map-marker-alt"></i> {{ $s->meeting_point ?? 'Liên hệ' }}</span>
                                <span><i class="fas fa-users"></i> Còn chỗ: {{ $s->max_people }} khách</span>
                            </div>
                            <a href="{{ route('user.booking', $tour->tour_id) }}?schedule={{ $s->schedule_id }}" class="sch-btn">Chọn chuyến này</a>
                        </div>
                        @endforeach
                    </div>
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
                                @if($r->image_url)
                                    <img src="{{ $r->image_url }}" alt="{{ $r->name }}" loading="lazy">
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

<!-- STICKY BOOKING BAR -->
<div class="sticky-booking-bar" id="sticky-bar">
    <div class="container">
        <div class="sticky-bar-flex">
            <div class="sticky-tour-info">
                @if($tour->image_url)
                    <img src="{{ $tour->image_url }}" alt="tour">
                @endif
                <div>
                    <div class="sticky-tour-name">{{ $tour->name }}</div>
                    <div class="hero-meta" style="font-size: 11px; margin-top: 4px;">
                        <span><i class="fas fa-clock"></i> {{ $tour->duration }} ngày</span>
                    </div>
                </div>
            </div>
            <div class="sticky-bar-flex" style="gap: 30px;">
                <div class="sticky-price-box">
                    <div class="sticky-price-val">{{ number_format($tour->price, 0, ',', '.') }} ₫</div>
                    <div class="sticky-per">/ khách</div>
                </div>
                <a href="{{ route('user.booking', $tour->tour_id) }}" class="btn-book" style="width: 200px; padding: 10px; background: #fff; border: 1px solid var(--primary); color: var(--primary);">
                    Đặt tour ngay
                </a>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    window.addEventListener('scroll', function() {
        const bar = document.getElementById('sticky-bar');
        const scrollPos = window.scrollY;
        
        if (scrollPos > 500) {
            bar.classList.add('visible');
        } else {
            bar.classList.remove('visible');
        }
    });
</script>
@endsection
