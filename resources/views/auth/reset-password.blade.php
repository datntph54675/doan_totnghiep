<!doctype html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt lại mật khẩu</title>
    <link href="/css/app.css" rel="stylesheet">
    <style>
        :root { --bg:#f3f6fb; --card:#fff; --accent:#2563eb; --muted:#6b7280 }
        *{ box-sizing:border-box }
        body{ margin:0; font-family:Inter,ui-sans-serif,system-ui,-apple-system,"Segoe UI",Roboto,"Helvetica Neue",Arial; background:var(--bg); color:#111 }
        .wrap{ min-height:100vh; display:flex; align-items:center; justify-content:center; padding:32px }
        .card{ width:100%; max-width:520px; background:var(--card); border-radius:12px; padding:28px; box-shadow:0 8px 30px rgba(2,6,23,.08) }
        .field{ margin-bottom:14px }
        label{ display:block; font-size:13px; margin-bottom:6px; font-weight:600 }
        input[type=email], input[type=password]{ width:100%; padding:11px 12px; border:1px solid #e6e9ef; border-radius:8px; font-size:14px; background:#fbfdff }
        .actions{ display:flex; gap:12px; margin-top:8px; justify-content:space-between; flex-wrap:wrap }
        .btn{ background:var(--accent); color:#fff; padding:10px 14px; border-radius:8px; border:0; cursor:pointer; font-weight:600; text-decoration:none; display:inline-flex; align-items:center; justify-content:center }
        .btn.secondary{ background:#eef2ff; color:var(--accent); border:1px solid rgba(37,99,235,.08) }
        .error{ background:#fff5f5; border:1px solid #f8d7da; color:#b91c1c; padding:10px; border-radius:8px; margin-bottom:12px }
        .muted{ color:var(--muted); font-size:13px; margin-top:10px }
    </style>
</head>

<body>
    <div class="wrap">
        <div class="card">
            <h2 style="margin:0 0 6px">Đặt lại mật khẩu</h2>
            <div class="muted">Nhập email và mật khẩu mới của bạn.</div>

            @if ($errors->any())
            <div class="error" style="margin-top:12px">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('password.update') }}" style="margin-top:12px">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="field">
                    <label for="email">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required>
                </div>

                <div class="field">
                    <label for="password">Mật khẩu mới</label>
                    <input id="password" name="password" type="password" required>
                </div>

                <div class="field">
                    <label for="password_confirmation">Nhập lại mật khẩu mới</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required>
                </div>

                <div class="actions">
                    <a class="btn secondary" href="{{ route('login') }}">Về đăng nhập</a>
                    <button class="btn" type="submit">Đặt lại mật khẩu</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>

