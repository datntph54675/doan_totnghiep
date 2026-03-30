<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liên hệ từ {{ $fullname }}</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #007bff; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background: #f9f9f9; }
        .footer { padding: 20px; text-align: center; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Liên hệ từ GoTour</h1>
        </div>
        <div class="content">
            <h2>Thông tin liên hệ</h2>
            <p><strong>Họ tên:</strong> {{ $fullname }}</p>
            <p><strong>Email:</strong> {{ $email }}</p>
            <p><strong>Số điện thoại:</strong> {{ $phone ?: 'Không cung cấp' }}</p>
            <p><strong>Tiêu đề:</strong> {{ $subject }}</p>
            <p><strong>Nội dung:</strong></p>
            <p>{{ nl2br(e($data['message'])) }}</p>
        </div>
        <div class="footer">
            <p>Email này được gửi từ form liên hệ trên website GoTour.</p>
        </div>
    </div>
</body>
</html>