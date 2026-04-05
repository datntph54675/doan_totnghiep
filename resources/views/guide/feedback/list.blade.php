@extends('guide.layout')

@section('page-title', 'Danh Sách Phản Hồi')
@section('page-sub', 'Quản lý các phản hồi bạn đã gửi cho admin')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        :root {
            --primary: #4f46e5;
            --primary-soft: #eef2ff;
            --primary-hover: #4338ca;
            /* Đã thêm biến hover bị thiếu */
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #3b82f6;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --bg-body: #f8fafc;
            --border: #e2e8f0;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        }

        .feedback-container {
            font-family: 'Inter', system-ui, sans-serif;
            color: var(--text-main);
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: #fff;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: var(--shadow);
            display: flex;
            align-items: center;
            border: 1px solid var(--border);
        }

        .stat-icon {
            width: 54px;
            height: 54px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-right: 1rem;
        }

        .icon-total {
            background: var(--primary-soft);
            color: var(--primary);
        }

        .icon-pending {
            background: #fff7ed;
            color: var(--warning);
        }

        .icon-viewed {
            background: #eff6ff;
            color: var(--info);
        }

        .icon-resolved {
            background: #ecfdf5;
            color: var(--success);
        }

        .stat-info .value {
            font-size: 1.75rem;
            font-weight: 800;
            line-height: 1.2;
            display: block;
        }

        .stat-info .label {
            font-size: 0.875rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        /* Action Bar */
        .action-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .btn-create {
            background: var(--primary);
            color: white !important;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-create:hover {
            background: var(--primary-hover);
            transform: translateY(-2px);
        }

        .filter-box {
            display: flex;
            gap: 0.5rem;
            background: #fff;
            padding: 0.5rem;
            border-radius: 8px;
            border: 1px solid var(--border);
        }

        .filter-box select {
            border: 1px solid transparent;
            padding: 0.5rem;
            border-radius: 6px;
            font-size: 0.9rem;
            outline: none;
            background: #f1f5f9;
        }

        /* Feedback List */
        .fb-card {
            background: #fff;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            border: 1px solid var(--border);
            transition: 0.3s;
        }

        .fb-card:hover {
            border-color: var(--primary);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .fb-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }

        .fb-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 0.25rem;
        }

        .fb-meta {
            font-size: 0.85rem;
            color: var(--text-muted);
            display: flex;
            gap: 1rem;
        }

        .fb-meta i {
            margin-right: 4px;
        }

        .badge {
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .status-pending {
            background: #fee2e2;
            color: #b91c1c;
        }

        .status-viewed {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .status-resolved {
            background: #dcfce7;
            color: #15803d;
        }

        .fb-content {
            color: #475569;
            line-height: 1.6;
            margin-bottom: 1.25rem;
        }

        .admin-reply {
            background: #fffbeb;
            border-radius: 8px;
            padding: 1rem;
            border-left: 4px solid var(--warning);
        }

        .reply-label {
            font-weight: 700;
            color: #92400e;
            font-size: 0.85rem;
            text-transform: uppercase;
            margin-bottom: 4px;
            display: block;
        }

        .fb-footer {
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #f1f5f9;
            display: flex;
            justify-content: flex-end;
        }

        .btn-detail {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .btn-detail:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .action-bar {
                flex-direction: column;
                align-items: stretch;
            }

            .filter-box {
                flex-direction: column;
            }
        }
    </style>

    <div class="feedback-container">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon icon-total"><i class="fa-solid fa-layer-group"></i></div>
                <div class="stat-info">
                    <span class="value">{{ $stats['total'] }}</span>
                    <span class="label">Tổng phản hồi</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon icon-pending"><i class="fa-solid fa-hourglass-half"></i></div>
                <div class="stat-info">
                    <span class="value">{{ $stats['pending'] }}</span>
                    <span class="label">Chưa xem</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon icon-viewed"><i class="fa-solid fa-eye"></i></div>
                <div class="stat-info">
                    <span class="value">{{ $stats['viewed'] }}</span>
                    <span class="label">Đã xem</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon icon-resolved"><i class="fa-solid fa-clipboard-check"></i></div>
                <div class="stat-info">
                    <span class="value">{{ $stats['resolved'] }}</span>
                    <span class="label">Đã xử lý</span>
                </div>
            </div>
        </div>

        <div class="action-bar">
            <a href="{{ route('guide.feedback.create') }}" class="btn-create">
                <i class="fa-solid fa-plus"></i> Tạo Phản Hồi Mới
            </a>

            <form method="GET" class="filter-box">
                <select name="status">
                    <option value="">Mọi trạng thái</option>
                    <option value="pending" @selected(request('status') === 'pending')>Chưa xem</option>
                    <option value="viewed" @selected(request('status') === 'viewed')>Đã xem</option>
                    <option value="resolved" @selected(request('status') === 'resolved')>Đã xử lý</option>
                </select>
                <select name="type">
                    <option value="">Mọi loại</option>
                    <option value="danh_gia" @selected(request('type') === 'danh_gia')>Đánh giá</option>
                    <option value="su_co" @selected(request('type') === 'su_co')>Sự cố</option>
                </select>
                <button type="submit" class="btn-create" style="padding: 0.5rem 1rem; background: #334155;">
                    <i class="fa-solid fa-filter"></i>
                </button>
                <a href="{{ route('guide.feedback.list') }}" class="btn-create"
                    style="padding: 0.5rem 1rem; background: #e2e8f0; color: #475569 !important;">
                    <i class="fa-solid fa-rotate-left"></i>
                </a>
            </form>
        </div>

        @if ($feedbacks->count() > 0)
            @foreach ($feedbacks as $feedback)
                <div class="fb-card">
                    <div class="fb-header">
                        <div>
                            <h3 class="fb-title">
                                <i class="{{ $feedback->type === 'su_co' ? 'fa-solid fa-triangle-exclamation text-warning' : 'fa-solid fa-star text-primary' }}"
                                    style="margin-right: 8px;"></i>
                                {{ $feedback->title }}
                            </h3>
                            <div class="fb-meta">
                                <span><i class="fa-solid fa-tag"></i> {{ $feedback->type_label }}</span>
                                <span><i class="fa-solid fa-calendar-days"></i>
                                    {{ $feedback->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>
                        <span class="badge status-{{ $feedback->status }}">
                            @if($feedback->status == 'pending') <i class="fa-solid fa-clock"></i>
                            @elseif($feedback->status == 'viewed') <i class="fa-solid fa-eye"></i>
                            @else <i class="fa-solid fa-check-double"></i> @endif
                            {{ $feedback->status_label }}
                        </span>
                    </div>

                    <div class="fb-content">
                        {{ Str::limit($feedback->content, 180) }}
                    </div>

                    @if ($feedback->admin_reply)
                        <div class="admin-reply">
                            <span class="reply-label"><i class="fa-solid fa-reply"></i> Phản hồi từ Admin:</span>
                            <p style="margin: 0; color: #475569;">{{ $feedback->admin_reply }}</p>
                        </div>
                    @endif

                    <div class="fb-footer">
                        <a href="{{ route('guide.feedback.show', $feedback->id) }}" class="btn-detail">
                            Chi tiết phản hồi <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            @endforeach

            <div style="margin-top: 2rem; display: flex; justify-content: center;">
                {{ $feedbacks->links() }}
            </div>
        @else
            <div
                style="text-align: center; padding: 5rem; background: #fff; border-radius: 12px; border: 2px dashed var(--border);">
                <i class="fa-solid fa-inbox" style="font-size: 3rem; color: var(--border); margin-bottom: 1rem;"></i>
                <h3 style="color: var(--text-muted);">Hộp thư phản hồi đang trống</h3>
                <p style="color: var(--text-muted); margin-bottom: 1.5rem;">Chúng tôi luôn lắng nghe ý kiến từ bạn.</p>
                <a href="{{ route('guide.feedback.create') }}" class="btn-create">Gửi ý kiến ngay</a>
            </div>
        @endif
    </div>
@endsection