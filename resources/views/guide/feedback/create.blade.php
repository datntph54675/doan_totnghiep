@extends('guide.layout')

@section('page-title', 'Gửi Phản Hồi')
@section('page-sub', 'Gửi báo cáo, kiến nghị hoặc đánh giá cho admin')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="card-title">📝 Tạo Phản Hồi Mới</div>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('guide.feedback.store') }}" class="form">
                @csrf

                <!-- Type Selection -->
                <div class="form-group">
                    <label for="type" class="form-label">Loại Phản Hồi *</label>
                    <select name="type" id="type" class="form-control @error('type') is-invalid @enderror" required>
                        <option value="">-- Chọn loại phản hồi --</option>
                        <option value="danh_gia" @selected(old('type') === 'danh_gia')>🎯 Đánh Giá Hệ Thống</option>
                        <option value="su_co" @selected(old('type') === 'su_co')>⚠️ Báo Cáo Sự Cố</option>
                    </select>
                    @error('type')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Type Description -->
                <div
                    style="padding: 12px; background: var(--primary-bg); border-radius: 8px; margin-bottom: 20px; font-size: 13px; color: var(--text-muted);">
                    <strong>Hướng dẫn:</strong>
                    <ul style="margin: 8px 0 0 20px; padding: 0;">
                        <li><strong>Đánh Giá Hệ Thống:</strong> Chia sẻ ý kiến về giao diện, tính năng, hoặc trải nghiệm sử
                            dụng</li>
                        <li><strong>Báo Cáo Sự Cố:</strong> Thông báo về lỗi kỹ thuật, vấn đề gặp phải trong quá trình làm
                            việc</li>
                    </ul>
                </div>

                <!-- Title -->
                <div class="form-group">
                    <label for="title" class="form-label">Tiêu Đề *</label>
                    <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror"
                        value="{{ old('title') }}" placeholder="Nhập tiêu đề phản hồi..." required>
                    @error('title')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Content -->
                <div class="form-group">
                    <label for="content" class="form-label">Nội Dung Chi Tiết *</label>
                    <textarea name="content" id="content" class="form-control @error('content') is-invalid @enderror"
                        placeholder="Mô tả chi tiết phản hồi của bạn (tối thiểu 10 ký tự)..." rows="8"
                        required>{{ old('content') }}</textarea>
                    <small style="color: var(--text-muted); display: block; margin-top: 8px;">
                        <span id="char-count">0</span> / 5000 ký tự
                    </small>
                    @error('content')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Submit Buttons -->
                <div class="form-actions" style="display: flex; gap: 10px; margin-top: 30px;">
                    <button type="submit" class="btn btn-primary" style="flex: 1;">
                        ✉️ Gửi Phản Hồi
                    </button>
                    <a href="{{ route('guide.feedback.list') }}" class="btn btn-secondary" style="flex: 1;">
                        ← Quay Lại
                    </a>
                </div>
            </form>
        </div>
    </div>

    <style>
        .form {
            max-width: 600px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--text);
            font-size: 14px;
        }

        .form-control {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
            transition: all 0.2s;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
        }

        .form-control.is-invalid {
            border-color: var(--danger);
        }

        .form-error {
            color: var(--danger);
            font-size: 12px;
            display: block;
            margin-top: 6px;
        }

        .form-actions {
            padding-top: 20px;
            border-top: 1px solid var(--border);
        }

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

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            box-shadow: var(--shadow-md);
        }

        .btn-secondary {
            background: var(--border);
            color: var(--text);
        }

        .btn-secondary:hover {
            background: #d1d5db;
        }
    </style>

    <script>
        document.getElementById('content').addEventListener('input', function () {
            document.getElementById('char-count').textContent = this.value.length;
        });

        // Initialize character count
        document.getElementById('char-count').textContent = document.getElementById('content').value.length;
    </script>
@endsection