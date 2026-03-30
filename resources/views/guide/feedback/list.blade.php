@extends('guide.layout')

@section('page-title', 'Danh Sách Phản Hồi')
@section('page-sub', 'Quản lý các phản hồi bạn đã gửi cho admin')

@section('content')
    @if (session('success'))
        <div class="alert alert-success" style="margin-bottom: 20px;">
            ✅ {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger" style="margin-bottom: 20px;">
            ❌ {{ session('error') }}
        </div>
    @endif

    <!-- Stats -->
    <div class="stats-grid" style="margin-bottom: 30px;">
        <div class="stat-card">
            <div class="stat-icon blue">📊</div>
            <div>
                <div class="stat-value">{{ $stats['total'] }}</div>
                <div class="stat-label">Tổng phản hồi</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon orange">⏳</div>
            <div>
                <div class="stat-value">{{ $stats['pending'] }}</div>
                <div class="stat-label">Chưa xem</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon blue">👀</div>
            <div>
                <div class="stat-value">{{ $stats['viewed'] }}</div>
                <div class="stat-label">Đã xem</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green">✅</div>
            <div>
                <div class="stat-value">{{ $stats['resolved'] }}</div>
                <div class="stat-label">Đã xử lý</div>
            </div>
        </div>
    </div>

    <!-- Filters and New Button -->
    <div class="card" style="margin-bottom: 20px;">
        <div class="card-body" style="display: flex; gap: 15px; align-items: center; flex-wrap: wrap;">
            <a href="{{ route('guide.feedback.create') }}" class="btn btn-primary">
                ➕ Tạo Phản Hồi Mới
            </a>

            <form method="GET" style="display: flex; gap: 10px; flex: 1; min-width: 300px;">
                <select name="status" class="form-control" style="flex: 1;">
                    <option value="">Tất cả trạng thái</option>
                    <option value="pending" @selected(request('status') === 'pending')>⏳ Chưa xem</option>
                    <option value="viewed" @selected(request('status') === 'viewed')>👀 Đã xem</option>
                    <option value="resolved" @selected(request('status') === 'resolved')>✅ Đã xử lý</option>
                </select>
                <select name="type" class="form-control" style="flex: 1;">
                    <option value="">Tất cả loại</option>
                    <option value="danh_gia" @selected(request('type') === 'danh_gia')>🎯 Đánh giá</option>
                    <option value="su_co" @selected(request('type') === 'su_co')>⚠️ Báo cáo sự cố</option>
                </select>
                <button type="submit" class="btn btn-primary">🔍 Lọc</button>
                <a href="{{ route('guide.feedback.list') }}" class="btn btn-secondary">↺ Reset</a>
            </form>
        </div>
    </div>

    <!-- Feedback List -->
    <div class="card">
        <div class="card-body">
            @if ($feedbacks->count() > 0)
                <div class="feedback-list">
                    @foreach ($feedbacks as $feedback)
                        <div class="feedback-item">
                            <div class="feedback-header">
                                <div style="flex: 1;">
                                    <div class="feedback-title">
                                        @if ($feedback->type === 'su_co')
                                            ⚠️
                                        @else
                                            🎯
                                        @endif
                                        {{ $feedback->title }}
                                    </div>
                                    <div class="feedback-meta">
                                        <span class="badge" style="background: #e0f2fe; color: #0369a1;">
                                            {{ $feedback->type_label }}
                                        </span>
                                        <span>📅 {{ $feedback->created_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                </div>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    @php
                                        $statusColor = match ($feedback->status) {
                                            'pending' => '#fca5a5',
                                            'viewed' => '#bfdbfe',
                                            'resolved' => '#86efac',
                                        };
                                    @endphp
                                    <span class="badge" style="background: {{ $statusColor }};">
                                        {{ $feedback->status_label }}
                                    </span>
                                </div>
                            </div>

                            <div class="feedback-content">
                                {{ Str::limit($feedback->content, 150) }}
                            </div>

                            @if ($feedback->admin_reply)
                                <div class="admin-reply">
                                    <strong>💬 Phản hồi từ Admin:</strong>
                                    <p>{{ $feedback->admin_reply }}</p>
                                </div>
                            @endif

                            <div class="feedback-footer">
                                <a href="{{ route('guide.feedback.show', $feedback->id) }}" class="link">
                                    Xem chi tiết →
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if ($feedbacks->hasPages())
                    <div style="margin-top: 30px; display: flex; justify-content: center;">
                        {{ $feedbacks->links() }}
                    </div>
                @endif
            @else
                <div class="empty-state">
                    <div class="empty-icon">📭</div>
                    <div class="empty-text">Bạn chưa gửi phản hồi nào</div>
                    <div class="empty-action">
                        <a href="{{ route('guide.feedback.create') }}" class="btn btn-primary">
                            ➕ Tạo phản hồi mới
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <style>
        .feedback-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .feedback-item {
            padding: 16px;
            border: 1px solid var(--border);
            border-radius: 8px;
            transition: all 0.2s;
        }

        .feedback-item:hover {
            box-shadow: var(--shadow-md);
            border-color: var(--primary);
        }

        .feedback-header {
            display: flex;
            gap: 15px;
            margin-bottom: 12px;
            justify-content: space-between;
        }

        .feedback-title {
            font-weight: 600;
            font-size: 16px;
            color: var(--text);
            margin-bottom: 8px;
        }

        .feedback-meta {
            display: flex;
            gap: 12px;
            font-size: 13px;
            color: var(--text-muted);
            flex-wrap: wrap;
        }

        .feedback-content {
            color: var(--text);
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 12px;
        }

        .admin-reply {
            background: #fef3c7;
            border-left: 3px solid #f59e0b;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 12px;
            font-size: 14px;
        }

        .admin-reply p {
            margin: 6px 0 0 0;
            color: var(--text);
        }

        .feedback-footer {
            padding-top: 12px;
            border-top: 1px solid var(--border);
        }

        .link {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.2s;
        }

        .link:hover {
            color: var(--primary-dark);
        }

        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-icon {
            font-size: 48px;
            margin-bottom: 16px;
        }

        .empty-text {
            color: var(--text-muted);
            font-size: 16px;
            margin-bottom: 20px;
        }

        .empty-action {
            display: flex;
            justify-content: center;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            font-weight: 500;
        }

        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border-left: 3px solid #10b981;
        }

        .alert-danger {
            background: #fee2e2;
            color: #991b1b;
            border-left: 3px solid #ef4444;
        }
    </style>
@endsection