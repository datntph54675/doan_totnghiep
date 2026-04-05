@extends('guide.layout')

@section('page-title', 'Chi Tiết Phản Hồi')
@section('page-sub', 'Xem chi tiết và phản hồi từ admin')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        :root {
            --primary: #4f46e5;
            --primary-soft: #eef2ff;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --white: #ffffff;
            --shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            --radius: 16px;
        }

        .detail-container {
            max-width: 900px;
            margin: 0 auto;
            font-family: 'Inter', system-ui, sans-serif;
        }

        /* Ticket Header Card */
        .ticket-card {
            background: var(--white);
            border-radius: var(--radius);
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow);
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .ticket-header {
            background: #f8fafc;
            padding: 1.5rem 2rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .ticket-title-group {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .ticket-title-group i {
            font-size: 1.5rem;
            color: var(--primary);
        }

        .ticket-title {
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--text-main);
            margin: 0;
        }

        /* Badge Status */
        .status-badge {
            padding: 6px 16px;
            border-radius: 30px;
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            display: inline-flex;
            align-items: center;
            gap: 6px;
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

        /* Info Grid */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1.5rem;
            padding: 1.5rem 2rem;
            background: #fff;
            border-bottom: 1px solid #f1f5f9;
        }

        .info-item label {
            display: block;
            font-size: 0.75rem;
            text-transform: uppercase;
            color: var(--text-muted);
            font-weight: 700;
            margin-bottom: 4px;
            letter-spacing: 0.5px;
        }

        .info-item span {
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--text-main);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* Content Area */
        .ticket-body {
            padding: 2rem;
        }

        .section-label {
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 8px;
            text-transform: uppercase;
        }

        .content-box {
            background: #f8fafc;
            padding: 1.5rem;
            border-radius: 12px;
            border-left: 5px solid var(--primary);
            line-height: 1.8;
            color: #334155;
            font-size: 1rem;
            white-space: pre-wrap;
        }

        /* Admin Reply Section */
        .reply-area {
            margin-top: 2rem;
        }

        .reply-box {
            background: #fffbeb;
            padding: 1.5rem;
            border-radius: 12px;
            border: 1px solid #fef3c7;
            border-left: 5px solid #f59e0b;
            position: relative;
        }

        .reply-box::before {
            content: '\f3e5';
            /* FontAwesome reply icon */
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            position: absolute;
            right: 20px;
            top: 20px;
            font-size: 2rem;
            color: rgba(245, 158, 11, 0.1);
        }

        .reply-content {
            color: #92400e;
            line-height: 1.8;
            font-size: 1rem;
        }

        /* Wait Message */
        .wait-box {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 1.25rem;
            border-radius: 12px;
            font-weight: 500;
        }

        .wait-pending {
            background: #fff7ed;
            color: #9a3412;
            border: 1px solid #ffedd5;
        }

        .wait-viewed {
            background: #eff6ff;
            color: #1e40af;
            border: 1px solid #dbeafe;
        }

        /* Footer Buttons */
        .action-footer {
            margin-top: 2rem;
            display: flex;
            gap: 1rem;
        }

        .btn-back {
            background: #f1f5f9;
            color: #475569 !important;
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: 0.2s;
        }

        .btn-back:hover {
            background: #e2e8f0;
            transform: translateX(-5px);
        }

        @media (max-width: 640px) {
            .info-grid {
                grid-template-columns: 1fr 1fr;
            }

            .ticket-header {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>

    <div class="detail-container">
        <div class="ticket-card">
            <div class="ticket-header">
                <div class="ticket-title-group">
                    <i
                        class="fa-solid {{ $feedback->type === 'su_co' ? 'fa-triangle-exclamation' : 'fa-circle-check' }}"></i>
                    <h2 class="ticket-title">{{ $feedback->title }}</h2>
                </div>

                <span class="status-badge status-{{ $feedback->status }}">
                    @if($feedback->status == 'pending') <i class="fa-solid fa-clock-rotate-left"></i>
                    @elseif($feedback->status == 'viewed') <i class="fa-solid fa-eye"></i>
                    @else <i class="fa-solid fa-circle-check"></i> @endif
                    {{ $feedback->status_label }}
                </span>
            </div>

            <div class="info-grid">
                <div class="info-item">
                    <label>Loại phản hồi</label>
                    <span><i class="fa-solid fa-tag"></i> {{ $feedback->type_label }}</span>
                </div>
                <div class="info-item">
                    <label>Ngày gửi</label>
                    <span><i class="fa-solid fa-calendar"></i> {{ $feedback->created_at->format('d/m/Y') }}</span>
                </div>
                <div class="info-item">
                    <label>Giờ gửi</label>
                    <span><i class="fa-solid fa-clock"></i> {{ $feedback->created_at->format('H:i') }}</span>
                </div>
                <div class="info-item">
                    <label>Cập nhật lần cuối</label>
                    <span><i class="fa-solid fa-pen-nib"></i> {{ $feedback->updated_at->format('d/m/Y H:i') }}</span>
                </div>
            </div>

            <div class="ticket-body">
                <div class="section-label">
                    <i class="fa-solid fa-comment-dots"></i> Nội dung của bạn
                </div>
                <div class="content-box">
                    {!! nl2br(e($feedback->content)) !!}
                </div>

                <div class="reply-area">
                    <div class="section-label">
                        <i class="fa-solid fa-reply-all"></i> Phản hồi từ quản trị viên
                    </div>

                    @if ($feedback->admin_reply)
                        <div class="reply-box">
                            <div class="reply-content">
                                {!! nl2br(e($feedback->admin_reply)) !!}
                            </div>
                        </div>
                    @else
                        @if ($feedback->status === 'pending')
                            <div class="wait-box wait-pending">
                                <i class="fa-solid fa-hourglass-start fa-spin"></i>
                                <span>Admin đang tiếp nhận yêu cầu. Bạn sẽ sớm nhận được phản hồi.</span>
                            </div>
                        @else
                            <div class="wait-box wait-viewed">
                                <i class="fa-solid fa-spinner fa-spin"></i>
                                <span>Admin đã xem phản hồi này và đang trong quá trình xử lý/giải quyết.</span>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>

        <div class="action-footer">
            <a href="{{ route('guide.feedback.list') }}" class="btn-back">
                <i class="fa-solid fa-chevron-left"></i> Quay lại danh sách
            </a>
        </div>
    </div>
@endsection