<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Lỗi máy chủ</title>
    <meta name="description" content="Đã xảy ra lỗi máy chủ nội bộ">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        :root {
            --primary: #0066cc;
            --primary-dark: #004fa3;
            --primary-light: #e8f2ff;
            --accent: #ff6b2b;
            --error: #ef4444;
            --error-dark: #dc2626;
            --text-dark: #1a1a2e;
            --text-mid: #4a5568;
            --text-light: #718096;
            --bg-light: #f7faff;
            --bg-white: #ffffff;
            --border: #e2e8f0;
            --shadow-lg: 0 10px 40px rgba(0,0,0,.15);
            --radius-lg: 20px;
            --transition: .25s cubic-bezier(.4,0,.2,1);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .error-container {
            max-width: 600px;
            background: var(--bg-white);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-lg);
            padding: 60px 40px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .error-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--error), var(--error-dark));
        }

        .error-icon {
            font-size: 6rem;
            color: var(--error);
            margin-bottom: 30px;
            opacity: 0.8;
        }

        .error-code {
            font-size: 8rem;
            font-weight: 800;
            color: var(--error);
            margin-bottom: 20px;
            line-height: 1;
            opacity: 0.1;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1;
        }

        .error-content {
            position: relative;
            z-index: 2;
        }

        .error-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 16px;
        }

        .error-message {
            font-size: 1.1rem;
            color: var(--text-mid);
            margin-bottom: 20px;
            line-height: 1.6;
        }

        .error-details {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 30px;
            text-align: left;
        }

        .error-details h4 {
            color: var(--error-dark);
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .error-details ul {
            margin: 0;
            padding-left: 20px;
        }

        .error-details li {
            color: var(--text-mid);
            margin-bottom: 8px;
            font-size: 0.95rem;
        }

        .error-actions {
            display: flex;
            gap: 16px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            padding: 14px 28px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: var(--transition);
            border: 2px solid transparent;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 102, 204, 0.3);
        }

        .btn-outline {
            background: transparent;
            color: var(--error);
            border-color: var(--error);
        }

        .btn-outline:hover {
            background: var(--error);
            color: white;
        }

        .btn-secondary {
            background: #6b7280;
            color: white;
        }

        .btn-secondary:hover {
            background: #4b5563;
        }

        @media (max-width: 768px) {
            .error-container {
                padding: 40px 20px;
            }

            .error-title {
                font-size: 2rem;
            }

            .error-code {
                font-size: 6rem;
            }

            .error-actions {
                flex-direction: column;
                align-items: center;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }

            .error-details {
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-code">500</div>
        <div class="error-content">
            <div class="error-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h1 class="error-title">Lỗi máy chủ nội bộ</h1>
            <p class="error-message">
                Xin lỗi, đã xảy ra lỗi không mong muốn trên máy chủ của chúng tôi. Vui lòng thử lại sau hoặc liên hệ hỗ trợ.
            </p>

            <div class="error-details">
                <h4>
                    <i class="fas fa-info-circle"></i>
                    Thông tin khắc phục:
                </h4>
                <ul>
                    <li>Làm mới trang</li>
                    <li>Xóa cache trình duyệt và thử lại</li>
                    <li>Quay lại sau vài phút</li>
                    <li>Liên hệ bộ phận kỹ thuật nếu lỗi vẫn tiếp tục</li>
                </ul>
            </div>

            <div class="error-actions">
                <a href="{{ url('/') }}" class="btn btn-primary">
                    <i class="fas fa-home"></i>
                    Về trang chủ
                </a>
                <button onclick="location.reload()" class="btn btn-secondary">
                    <i class="fas fa-redo"></i>
                    Làm mới trang
                </button>
            </div>
        </div>
    </div>
</body>
</html>
