<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Trang không tồn tại</title>
    <meta name="description" content="Trang bạn tìm kiếm không tồn tại">

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
            background: linear-gradient(135deg, var(--bg-light) 0%, var(--primary-light) 100%);
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
            background: linear-gradient(90deg, var(--accent), var(--primary));
        }

        .error-icon {
            font-size: 6rem;
            color: var(--accent);
            margin-bottom: 30px;
            opacity: 0.8;
        }

        .error-code {
            font-size: 8rem;
            font-weight: 800;
            color: var(--accent);
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
            margin-bottom: 40px;
            line-height: 1.6;
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
            color: var(--accent);
            border-color: var(--accent);
        }

        .btn-outline:hover {
            background: var(--accent);
            color: white;
        }

        .search-form {
            max-width: 400px;
            margin: 0 auto 30px;
            position: relative;
        }

        .search-input {
            width: 100%;
            padding: 14px 50px 14px 20px;
            border: 2px solid var(--border);
            border-radius: 12px;
            font-size: 1rem;
            transition: var(--transition);
        }

        .search-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(0, 102, 204, 0.1);
        }

        .search-btn {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: var(--primary);
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 8px;
            cursor: pointer;
            transition: var(--transition);
        }

        .search-btn:hover {
            background: var(--primary-dark);
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
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-code">404</div>
        <div class="error-content">
            <div class="error-icon">
                <i class="fas fa-search"></i>
            </div>
            <h1 class="error-title">Không tìm thấy trang</h1>
            <p class="error-message">
                Trang bạn đang tìm kiếm có thể đã bị xóa, đổi tên hoặc không tồn tại. Hãy thử tìm kiếm hoặc quay về trang chủ.
            </p>

            <div class="error-actions">
                <a href="{{ url('/') }}" class="btn btn-primary">
                    <i class="fas fa-home"></i>
                    Về trang chủ
                </a>
            </div>
        </div>
    </div>
</body>
</html>
