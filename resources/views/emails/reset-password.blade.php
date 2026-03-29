<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt lại mật khẩu GoTour</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f7f9;
            color: #333;
        }
        .email-wrapper {
            width: 100%;
            padding: 40px 0;
            background-color: #f4f7f9;
        }
        .email-content {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        }
        .email-header {
            background-color: #0066cc;
            padding: 40px 20px;
            text-align: center;
            color: #ffffff;
        }
        .logo-icon {
            font-size: 40px;
            margin-bottom: 15px;
        }
        .email-header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 800;
            letter-spacing: 1px;
        }
        .email-body {
            padding: 40px 50px;
            line-height: 1.7;
        }
        .greeting {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 20px;
            color: #2d3436;
        }
        .message {
            font-size: 15px;
            color: #636e72;
            margin-bottom: 30px;
        }
        .cta-wrapper {
            text-align: center;
            margin: 40px 0;
        }
        .btn-reset {
            background-color: #0066cc;
            color: #ffffff !important;
            padding: 18px 35px;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 700;
            font-size: 16px;
            display: inline-block;
            box-shadow: 0 8px 20px rgba(0, 102, 204, 0.25);
            transition: all 0.3s;
        }
        .footer {
            padding: 20px 50px 40px;
            text-align: center;
            font-size: 13px;
            color: #b2bec3;
            border-top: 1px solid #f1f2f6;
        }
        .security-note {
            background-color: #fff9db;
            padding: 15px;
            border-radius: 10px;
            font-size: 13px;
            color: #856404;
            margin-top: 30px;
            text-align: left;
        }
        .link-alt {
            word-break: break-all;
            color: #0066cc;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-content">
            <!-- Header -->
            <div class="email-header">
                <div class="logo-icon">✈</div>
                <h1>GoTour</h1>
            </div>

            <!-- Body -->
            <div class="email-body">
                <div class="greeting">Xin chào!</div>
                <div class="message">
                    Chúng tôi nhận được yêu cầu đặt lại mật khẩu cho tài khoản GoTour của bạn. Đừng lo lắng, hãy nhấp vào nút bên dưới để tạo mật khẩu cực kỳ bảo mật mới.
                </div>

                <div class="cta-wrapper">
                    <a href="{{ $url }}" class="btn-reset">Đặt lại mật khẩu ngay</a>
                </div>

                <div class="message">
                    Gợi ý: Mật khẩu mới nên có cả chữ hoa, chữ thường, số và ký tự đặc biệt để đảm bảo an toàn tuyệt đối cho tài khoản của bạn.
                </div>

                <div class="security-note">
                    <strong>Thông báo bảo mật:</strong> Liên kết này sẽ hết hạn sau 60 phút. Nếu bạn không yêu cầu đặt lại mật khẩu, bạn có thể bỏ qua thư này, tài khoản của bạn vẫn được an toàn.
                </div>
            </div>

            <!-- Footer -->
            <div class="footer">
                Nếu bạn gặp khó khăn khi nhấp vào nút "Đặt lại mật khẩu ngay", hãy sao chép và dán địa chỉ URL sau vào trình duyệt web của bạn:<br><br>
                <a href="{{ $url }}" class="link-alt">{{ $url }}</a>
                <br><br>
                © 2026 GoTour. Khám phá hành trình di sản Việt Nam.<br>
                Hỗ trợ khách hàng: 1900 xxxx
            </div>
        </div>
    </div>
</body>
</html>
