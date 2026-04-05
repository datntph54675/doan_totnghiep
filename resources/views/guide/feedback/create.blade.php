@extends('guide.layout')

@section('page-title', 'Gửi Phản Hồi')
@section('page-sub', 'Gửi báo cáo, kiến nghị hoặc đánh giá cho admin')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        :root {
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --bg-light: #f8fafc;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --white: #ffffff;
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --radius: 12px;
        }

        .feedback-form-container {
            max-width: 800px;
            margin: 0 auto;
            font-family: 'Inter', system-ui, sans-serif;
        }

        .form-card {
            background: var(--white);
            border-radius: var(--radius);
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border-color);
            overflow: hidden;
        }

        .card-header-custom {
            background: #f1f5f9;
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-header-custom i {
            color: var(--primary);
            font-size: 1.25rem;
        }

        .card-title-custom {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text-main);
            margin: 0;
        }

        .card-body-custom {
            padding: 2rem;
        }

        /* Hướng dẫn Box */
        .guide-box {
            background: #eff6ff;
            border-left: 4px solid var(--primary);
            padding: 1rem 1.25rem;
            border-radius: 8px;
            margin-bottom: 2rem;
        }

        .guide-box h4 {
            font-size: 0.9rem;
            font-weight: 700;
            color: #1e40af;
            margin: 0 0 8px 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .guide-list {
            margin: 0;
            padding-left: 1.25rem;
            font-size: 0.875rem;
            color: #1e40af;
            list-style-type: disc;
        }

        .guide-list li {
            margin-bottom: 4px;
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label-custom {
            display: block;
            font-weight: 600;
            font-size: 0.9rem;
            color: var(--text-main);
            margin-bottom: 8px;
        }

        .form-label-custom span {
            color: #ef4444;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        .form-control-custom {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.5rem;
            /* Space for icon */
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 0.95rem;
            transition: all 0.2s;
            background: #fff;
        }

        .form-control-custom:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
        }

        textarea.form-control-custom {
            padding-left: 1rem;
            /* No icon for textarea */
        }

        .is-invalid-custom {
            border-color: #ef4444 !important;
        }

        .error-msg {
            color: #ef4444;
            font-size: 0.8rem;
            margin-top: 6px;
            display: block;
        }

        .char-counter {
            display: block;
            text-align: right;
            font-size: 0.8rem;
            color: var(--text-muted);
            margin-top: 6px;
        }

        /* Actions */
        .form-actions-custom {
            display: flex;
            gap: 12px;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border-color);
        }

        .btn-submit {
            background: var(--primary);
            color: white !important;
            padding: 0.8rem 2rem;
            border-radius: 8px;
            font-weight: 700;
            border: none;
            cursor: pointer;
            transition: 0.2s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            flex: 2;
        }

        .btn-submit:hover {
            background: var(--primary-hover);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
        }

        .btn-back {
            background: #f1f5f9;
            color: #475569 !important;
            padding: 0.8rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            flex: 1;
            transition: 0.2s;
        }

        .btn-back:hover {
            background: #e2e8f0;
        }

        @media (max-width: 640px) {
            .form-actions-custom {
                flex-direction: column;
            }
        }
    </style>

    <div class="feedback-form-container">
        <div class="form-card">
            <div class="card-header-custom">
                <i class="fa-solid fa-pen-to-square"></i>
                <h2 class="card-title-custom">Tạo Phản Hồi Mới</h2>
            </div>

            <div class="card-body-custom">
                <form method="POST" action="{{ route('guide.feedback.store') }}">
                    @csrf

                    <div class="guide-box">
                        <h4><i class="fa-solid fa-circle-info"></i> Hướng dẫn gửi phản hồi:</h4>
                        <ul class="guide-list">
                            <li><strong>Đánh Giá:</strong> Góp ý về giao diện, tính năng hoặc trải nghiệm.</li>
                            <li><strong>Báo Cáo Sự Cố:</strong> Thông báo lỗi kỹ thuật hoặc khó khăn khi làm việc.</li>
                        </ul>
                    </div>

                    <div class="form-group">
                        <label class="form-label-custom">Loại Phản Hồi <span>*</span></label>
                        <div class="input-wrapper">
                            <i class="fa-solid fa-list-check"></i>
                            <select name="type" class="form-control-custom @error('type') is-invalid-custom @enderror"
                                required>
                                <option value="">-- Chọn loại phản hồi --</option>
                                <option value="danh_gia" @selected(old('type') === 'danh_gia')>Đánh Giá Hệ Thống</option>
                                <option value="su_co" @selected(old('type') === 'su_co')>Báo Cáo Sự Cố</option>
                            </select>
                        </div>
                        @error('type') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label-custom">Tiêu Đề <span>*</span></label>
                        <div class="input-wrapper">
                            <i class="fa-solid fa-heading"></i>
                            <input type="text" name="title"
                                class="form-control-custom @error('title') is-invalid-custom @enderror"
                                value="{{ old('title') }}" placeholder="Ví dụ: Lỗi không hiển thị bản đồ..." required>
                        </div>
                        @error('title') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label-custom">Nội Dung Chi Tiết <span>*</span></label>
                        <textarea name="content" id="content_textarea"
                            class="form-control-custom @error('content') is-invalid-custom @enderror"
                            placeholder="Vui lòng mô tả chi tiết vấn đề của bạn..." rows="6"
                            required>{{ old('content') }}</textarea>

                        <div class="char-counter">
                            <i class="fa-solid fa-keyboard"></i> <span id="char-count">0</span> / 5000 ký tự
                        </div>
                        @error('content') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-actions-custom">
                        <a href="{{ route('guide.feedback.list') }}" class="btn-back">
                            <i class="fa-solid fa-arrow-left"></i> Quay Lại
                        </a>
                        <button type="submit" class="btn-submit">
                            <i class="fa-solid fa-paper-plane"></i> Gửi Phản Hồi Ngay
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const textarea = document.getElementById('content_textarea');
        const countDisplay = document.getElementById('char-count');

        textarea.addEventListener('input', function () {
            countDisplay.textContent = this.value.length;
            if (this.value.length > 4500) {
                countDisplay.style.color = '#ef4444';
            } else {
                countDisplay.style.color = 'var(--text-muted)';
            }
        });

        // Khởi tạo đếm ký tự nếu có dữ liệu old()
        countDisplay.textContent = textarea.value.length;
    </script>
@endsection