@extends('user.layout')

@section('title', 'Danh sách tour du lịch')

@push('styles')
<style>
    .page-hero {
        background: linear-gradient(135deg, #0ea5e9 0%, #6366f1 100%);
        padding: 60px 20px;
        text-align: center;
        color: #fff;
    }
    .page-hero h1 { font-size: 36px; font-weight: 800; margin-bottom: 10px; }
    .page-hero p { font-size: 16px; opacity: .85; }

    .tours-grid {
        max-width: 1100px; margin: 40px auto; padding: 0 20px;
        display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 24px;
    }
    .tour-card {
        background: #fff; border-radius: 14px; border: 1px solid #e2e8f0;
        overflow: hidden; transition: transform .2s, box-shadow .2s;
        text-decoration: none; color: inherit; display: block;
    }
    .tour-card:hover { transform: translateY(-4px); box-shadow: 0 12px 32px rgba(0,0,0,.1); }
    .tour-card-img {
        height: 200px; background: linear-gradient(135deg, #0ea5e9, #6366f1);
        position: relative; overflow: hidden;
    }
    .tour-card-img img { width: 100%; height: 100%; object-fit: cover; }
    .tour-card-badge {
        position: absolute; top: 12px; left: 12px;
        background: rgba(255,255,255,.9); backdrop-filter: blur(4px);
        padding: 4px 10px; border-radius: 20px;
        font-size: 12px; font-weight: 600; color: #0ea5e9;
    }
    .tour-card-body { padding: 18px 20px; }
    .tour-card-title { font-size: 16px; font-weight: 700; color: #1e293b; margin-bottom: 8px; line-height: 1.4; }
    .tour-card-meta { display: flex; gap: 14px; flex-wrap: wrap; margin-bottom: 14px; }
    .tour-card-meta span { font-size: 13px; color: #64748b; display: flex; align-items: center; gap: 4px; }
    .tour-card-footer { display: flex; justify-content: space-between; align-items: center; padding-top: 14px; border-top: 1px solid #f1f5f9; }
    .tour-price { font-size: 18px; font-weight: 800; color: #0ea5e9; }
    .tour-price small { font-size: 12px; font-weight: 400; color: #94a3b8; }
    .btn-detail {
        background: linear-gradient(135deg, #0ea5e9, #6366f1);
        color: #fff; border: none; border-radius: 8px;
        padding: 8px 16px; font-size: 13px; font-weight: 600;
        cursor: pointer; text-decoration: none;
    }

    .empty { text-align: center; padding: 80px 20px; color: #94a3b8; }
    .empty-icon { font-size: 60px; margin-bottom: 16px; }

    .pagination-wrap { display: flex; justify-content: center; padding: 20px; gap: 8px; }
    .pagination-wrap a, .pagination-wrap span {
        padding: 8px 14px; border-radius: 8px; font-size: 14px;
        border: 1px solid #e2e8f0; text-decoration: none; color: #475569;
    }
    .pagination-wrap .active { background: #0ea5e9; color: #fff; border-color: #0ea5e9; }
</style>
@endpush

@section('content')
<div class="page-hero">
    <h1>🌏 Khám phá tour du lịch</h1>
    <p>Tìm kiếm và đặt tour phù hợp với bạn</p>
</div>

@if($tours->count() > 0)
<div class="tours-grid">
    @foreach($tours as $tour)
    <a href="{{ route('user.tour.detail', $tour->tour_id) }}" class="tour-card">
        <div class="tour-card-img">
            @if($tour->image_url)
                <img src="{{ $tour->image_url }}" alt="{{ $tour->name }}">
            @endif
            @if($tour->category)
            <div class="tour-card-badge">{{ $tour->category->name }}</div>
            @endif
        </div>
        <div class="tour-card-body">
            <div class="tour-card-title">{{ $tour->name }}</div>
            <div class="tour-card-meta">
                @if($tour->duration)
                <span>🕐 {{ $tour->duration }} ngày</span>
                @endif
                @if($tour->max_people)
                <span>👥 {{ $tour->max_people }} người</span>
                @endif
            </div>
            <div class="tour-card-footer">
                <div class="tour-price">
                    {{ number_format($tour->price, 0, ',', '.') }} ₫
                    <small>/người</small>
                </div>
                <span class="btn-detail">Xem chi tiết →</span>
            </div>
        </div>
    </a>
    @endforeach
</div>
<div class="pagination-wrap">{{ $tours->links() }}</div>
@else
<div class="empty">
    <div class="empty-icon">🗺️</div>
    <p>Chưa có tour nào.</p>
</div>
@endif
@endsection
