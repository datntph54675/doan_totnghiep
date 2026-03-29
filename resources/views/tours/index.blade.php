@extends('layouts.user')

@section('title', 'Danh Sách Tour - GoTour')
@section('meta_description', 'Tìm kiếm và đặt tour du lịch Việt Nam uy tín, giá tốt. Filter theo danh mục, giá, thời gian.')

@section('styles')
<style>
.page-header {
    background: linear-gradient(135deg, #1a1a2e 0%, #0f3460 100%);
    padding: 80px 0 40px; color: #fff;
}
.page-header h1 { font-size: 2.2rem; font-weight: 800; margin-bottom: 8px; }
.page-header p { color: rgba(255,255,255,.7); font-size: 1rem; }
.breadcrumb { display: flex; align-items: center; gap: 8px; font-size: .85rem; margin-bottom: 16px; }
.breadcrumb a { color: rgba(255,255,255,.7); }
.breadcrumb a:hover { color: #fff; }
.breadcrumb span { color: rgba(255,255,255,.4); }

/* Filter Sidebar */
.tours-layout { display: grid; grid-template-columns: 280px 1fr; gap: 32px; padding: 40px 0 80px; }
.filter-sidebar { position: sticky; top: 90px; height: fit-content; }
.filter-card { background: #fff; border-radius: var(--radius-md); border: 1px solid var(--border); overflow: hidden; }
.filter-header {
    padding: 16px 20px; background: var(--primary); color: #fff;
    font-weight: 700; font-size: .95rem;
    display: flex; align-items: center; gap: 8px;
}
.filter-section { padding: 20px; border-bottom: 1px solid var(--border); }
.filter-section:last-child { border-bottom: none; }
.filter-label { font-size: .8rem; font-weight: 700; color: var(--text-mid); text-transform: uppercase; letter-spacing: .05em; margin-bottom: 12px; }
.filter-input {
    width: 100%; padding: 10px 14px;
    border: 1.5px solid var(--border); border-radius: var(--radius-sm);
    font-size: .875rem; font-family: inherit; color: var(--text-dark);
    transition: all var(--transition); background: var(--bg-light);
}
.filter-input:focus { outline: none; border-color: var(--primary); background: #fff; box-shadow: 0 0 0 3px rgba(0,102,204,.1); }
.price-row { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
.filter-apply {
    width: 100%; padding: 12px; background: var(--accent); color: #fff;
    border: none; border-radius: var(--radius-sm); font-weight: 700;
    font-size: .9rem; cursor: pointer; margin-top: 4px;
    transition: all var(--transition);
}
.filter-apply:hover { background: var(--accent-dark); }
.filter-reset {
    width: 100%; padding: 10px; background: var(--bg-light); color: var(--text-mid);
    border: 1.5px solid var(--border); border-radius: var(--radius-sm);
    font-weight: 600; font-size: .85rem; cursor: pointer; margin-top: 8px;
    transition: all var(--transition);
}
.filter-reset:hover { border-color: var(--primary); color: var(--primary); }

/* Tour Results */
.results-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; }
.results-count { font-size:.9rem; color:var(--text-light); }
.results-count strong { color: var(--text-dark); }
.sort-select {
    padding: 8px 14px; border: 1.5px solid var(--border); border-radius: var(--radius-sm);
    font-size: .875rem; font-family: inherit; color: var(--text-dark);
    background: var(--bg-light); cursor: pointer;
}
.tours-grid-main { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; }
.empty-state { text-align: center; padding: 80px 0; color: var(--text-light); }
.empty-state i { font-size: 4rem; color: var(--border); margin-bottom: 16px; }
.empty-state h3 { font-size: 1.2rem; color: var(--text-mid); margin-bottom: 8px; }

/* Pagination */
.pagination-wrapper { display: flex; justify-content: center; margin-top: 40px; }
.pagination-wrapper .pagination {
    display: flex; gap: 6px; align-items: center;
}
.pagination-wrapper .page-item .page-link {
    padding: 10px 16px; border-radius: var(--radius-sm);
    border: 1.5px solid var(--border); color: var(--text-mid);
    font-weight: 500; font-size: .875rem; transition: all var(--transition);
}
.pagination-wrapper .page-item.active .page-link {
    background: var(--primary); color: #fff; border-color: var(--primary);
}
.pagination-wrapper .page-item .page-link:hover {
    border-color: var(--primary); color: var(--primary);
}

/* Active filters */
.active-filters { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 20px; }
.filter-tag {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 5px 12px; background: var(--primary-light); color: var(--primary);
    border-radius: 50px; font-size: .78rem; font-weight: 600;
}
.filter-tag a { color: var(--primary); margin-left: 2px; font-size: .7rem; }

@media (max-width: 1024px) {
    .tours-layout { grid-template-columns: 1fr; }
    .filter-sidebar { position: static; }
    .tours-grid-main { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 600px) { .tours-grid-main { grid-template-columns: 1fr; } }
</style>
@endsection

@section('content')

<div class="page-header">
    <div class="container">
        <div class="breadcrumb">
            <a href="{{ route('home') }}"><i class="fas fa-home"></i> Trang chủ</a>
            <span>/</span>
            <span>Danh sách tour</span>
        </div>
        <h1>🗺️ Tất Cả Tour Du Lịch</h1>
        <p>Tìm kiếm và đặt tour phù hợp với bạn</p>
    </div>
</div>

<div class="container">
    <div class="tours-layout">

        <!-- SIDEBAR FILTER -->
        <aside class="filter-sidebar">
            <div class="filter-card">
                <div class="filter-header">
                    <i class="fas fa-sliders-h"></i> Bộ Lọc Tìm Kiếm
                </div>
                <form action="{{ route('tours.index') }}" method="GET" id="filterForm">
                    <div class="filter-section">
                        <div class="filter-label">Từ khóa</div>
                        <input class="filter-input" type="text" name="search" placeholder="Tên tour, địa điểm..." value="{{ request('search') }}">
                    </div>
                    <div class="filter-section">
                        <div class="filter-label">Danh mục</div>
                        <select class="filter-input" name="category" style="cursor:pointer">
                            <option value="">Tất cả danh mục</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->category_id }}" {{ request('category') == $cat->category_id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-section">
                        <div class="filter-label">Số ngày</div>
                        <select class="filter-input" name="duration" style="cursor:pointer">
                            <option value="">Bất kỳ</option>
                            <option value="1" {{ request('duration') == '1' ? 'selected' : '' }}>1 ngày</option>
                            <option value="2" {{ request('duration') == '2' ? 'selected' : '' }}>2 ngày</option>
                            <option value="3" {{ request('duration') == '3' ? 'selected' : '' }}>3 ngày</option>
                            <option value="4" {{ request('duration') == '4' ? 'selected' : '' }}>4 ngày</option>
                            <option value="5" {{ request('duration') == '5' ? 'selected' : '' }}>5 ngày</option>
                            <option value="6" {{ request('duration') == '6' ? 'selected' : '' }}>6 ngày</option>
                            <option value="7" {{ request('duration') == '7' ? 'selected' : '' }}>7 ngày</option>
                        </select>
                    </div>
                    <div class="filter-section">
                        <div class="filter-label">Giá (VNĐ)</div>
                        <div class="price-row">
                            <input class="filter-input" type="number" name="min_price" placeholder="Từ" value="{{ request('min_price') }}" min="0">
                            <input class="filter-input" type="number" name="max_price" placeholder="Đến" value="{{ request('max_price') }}" min="0">
                        </div>
                    </div>
                    <div class="filter-section">
                        <button type="submit" class="filter-apply">
                            <i class="fas fa-search"></i> Áp Dụng
                        </button>
                        <a href="{{ route('tours.index') }}" class="filter-reset" style="text-decoration:none;text-align:center;display:block">
                            <i class="fas fa-undo"></i> Xóa bộ lọc
                        </a>
                    </div>
                </form>
            </div>
        </aside>

        <!-- TOUR RESULTS -->
        <div class="tours-main">
            <!-- Active filters -->
            @if(request()->hasAny(['search','category','min_price','max_price','duration']))
            <div class="active-filters">
                <span style="font-size:.8rem;color:var(--text-light);align-self:center">Đang lọc:</span>
                @if(request('search'))
                    <span class="filter-tag">Từ khóa: "{{ request('search') }}"</span>
                @endif
                @if(request('duration'))
                    <span class="filter-tag">{{ request('duration') }} ngày</span>
                @endif
                @if(request('min_price') || request('max_price'))
                    <span class="filter-tag">
                        Giá: {{ request('min_price') ? number_format(request('min_price'),0,',','.').'đ' : '0đ' }}
                        - {{ request('max_price') ? number_format(request('max_price'),0,',','.').'đ' : '∞' }}
                    </span>
                @endif
            </div>
            @endif

            <div class="results-header">
                <div class="results-count">
                    Tìm thấy <strong>{{ $tours->total() }}</strong> tour
                </div>
            </div>

            @if($tours->count() > 0)
            <div class="tours-grid-main">
                @foreach($tours as $i => $tour)
                <a href="{{ route('tours.show', $tour->tour_id) }}" class="tour-card">
                    <div class="tour-card-img">
                        @if($tour->image && file_exists(storage_path('app/public/' . $tour->image)))
                            <img src="{{ Storage::url($tour->image) }}" alt="{{ $tour->name }}" loading="lazy">
                        @else
                            <div class="tour-card-img-placeholder" style="background: linear-gradient(135deg,
                                {{ ['#667eea,#764ba2','#f093fb,#f5576c','#4facfe,#00f2fe','#43e97b,#38f9d7','#fa709a,#fee140','#30cfd0,#667eea','#a18cd1,#fbc2eb','#fda085,#f6d365'][($tours->currentPage() * 9 + $i) % 8] }})">
                                <i class="fas fa-map-marked-alt"></i>
                            </div>
                        @endif
                        <div class="tour-card-overlay">
                            @if($tour->category)
                                <span class="badge badge-blue" style="font-size:.7rem">{{ $tour->category->name }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="tour-card-body">
                        <div class="tour-card-name">{{ $tour->name }}</div>
                        @if($tour->description)
                        <div style="font-size:.8rem;color:var(--text-light);margin-bottom:10px;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                            {{ $tour->description }}
                        </div>
                        @endif
                        <div class="tour-card-meta">
                            @if($tour->duration)
                            <span><i class="fas fa-clock"></i> {{ $tour->duration }} ngày</span>
                            @endif
                            @if($tour->max_people)
                            <span><i class="fas fa-users"></i> {{ $tour->max_people }} người</span>
                            @endif
                        </div>
                        <div class="tour-card-footer">
                            <div class="tour-price">
                                <small>Từ</small>
                                {{ $tour->price ? number_format($tour->price, 0, ',', '.') . ' đ' : 'Liên hệ' }}
                            </div>
                            <span class="btn btn-accent" style="padding:8px 16px;font-size:.8rem">Xem chi tiết</span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($tours->hasPages())
            <div class="pagination-wrapper" style="margin-top:40px">
                {{ $tours->links('pagination::bootstrap-5') }}
            </div>
            @endif

            @else
            <div class="empty-state">
                <i class="fas fa-search"></i>
                <h3>Không tìm thấy tour phù hợp</h3>
                <p>Thử thay đổi bộ lọc để tìm tour khác</p>
                <a href="{{ route('tours.index') }}" class="btn btn-primary" style="margin-top:20px;display:inline-flex">
                    <i class="fas fa-undo"></i> Xem tất cả tour
                </a>
            </div>
            @endif
        </div>
    </div>
</div>

@endsection
