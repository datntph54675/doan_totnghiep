@extends('guide.layout')

@section('page-title', 'Chi Tiết Phản Hồi')
@section('page-sub', 'Xem chi tiết và phản hồi từ admin')

@section('content')
    <div class="card">
        <div class="card-header">
            <div style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
                <div class="card-title">
                    @if ($feedback->type === 'su_co')
                        ⚠️
                    @else
                        🎯
                    @endif
                    {{ $feedback->title }}
                </div>
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
        <div class="card-body">
            <!-- Feedback Info -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
                <div>
                    <div style="color: var(--text-muted); font-size: 13px; margin-bottom: 4px;">Loại Phản Hồi</div>
                    <div style="font-weight: 600; color: var(--text);">{{ $feedback->type_label }}</div>
                </div>
                <div>
                    <div style="color: var(--text-muted); font-size: 13px; margin-bottom: 4px;">Ngày Gửi</div>
                    <div style="font-weight: 600; color: var(--text);">{{ $feedback->created_at->format('d/m/Y H:i') }}</div>
                </div>
                <div>
                    <div style="color: var(--text-muted); font-size: 13px; margin-bottom: 4px;">Trạng Thái</div>
                    <div style="font-weight: 600; color: var(--text);">{{ $feedback->status_label }}</div>
                </div>
                <div>
                    <div style="color: var(--text-muted); font-size: 13px; margin-bottom: 4px;">Lần Cập Nhật</div>
                    <div style="font-weight: 600; color: var(--text);">{{ $feedback->updated_at->format('d/m/Y H:i') }}</div>
                </div>
            </div>

            <!-- Content -->
            <div style="margin-bottom: 30px;">
                <h3 style="font-size: 14px; font-weight: 600; color: var(--text-muted); margin-bottom: 12px; text-transform: uppercase;">Nội Dung Phản Hồi</h3>
                <div style="background: var(--primary-bg); padding: 16px; border-radius: 8px; border-left: 4px solid var(--primary); line-height: 1.8; color: var(--text);">
                    {!! nl2br(e($feedback->content)) !!}
                </div>
            </div>

            <!-- Admin Reply -->
            @if ($feedback->admin_reply)
                <div style="margin-bottom: 30px;">
                    <h3 style="font-size: 14px; font-weight: 600; color: var(--text-muted); margin-bottom: 12px; text-transform: uppercase;">💬 Phản Hồi Từ Admin</h3>
                    <div style="background: #fef3c7; padding: 16px; border-radius: 8px; border-left: 4px solid #f59e0b; line-height: 1.8; color: var(--text);">
                        {!! nl2br(e($feedback->admin_reply)) !!}
                    </div>
                </div>
            @else
                @if ($feedback->status === 'pending')
                    <div style="background: #fef3c7; padding: 16px; border-radius: 8px; margin-bottom: 30px; color: var(--text);">
                        <strong>⏳ Phản hồi của admin sẽ được gửi vào địa chỉ email của bạn khi xử lý xong.</strong>
                    </div>
                @else
                    <div style="background: #e0f2fe; padding: 16px; border-radius: 8px; margin-bottom: 30px; color: var(--text);">
                        <strong>👀 Admin đã xem phản hồi của bạn và đang xử lý.</strong>
                    </div>
                @endif
            @endif

            <!-- Back Button -->
            <div style="display: flex; gap: 10px;">
                <a href="{{ route('guide.feedback.list') }}" class="btn btn-secondary">
                    ← Quay Lại
                </a>
            </div>
        </div>
    </div>

    <style>
        .btn {
            padding: 10px 16px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-secondary {
            background: var(--border);
            color: var(--text);
        }

        .btn-secondary:hover {
            background: #d1d5db;
        }

        .badge {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
        }
    </style>
@endsection
