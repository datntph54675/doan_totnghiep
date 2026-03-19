<!doctype html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đổi mật khẩu</title>
    <link href="/css/app.css" rel="stylesheet">
    <style>
        :root { --bg:#f3f6fb; --card:#fff; --accent:#2563eb; --muted:#6b7280 }
        *{ box-sizing:border-box }
        body{ margin:0; font-family:Inter,ui-sans-serif,system-ui,-apple-system,"Segoe UI",Roboto,"Helvetica Neue",Arial; background:var(--bg); color:#111 }
        .wrap{ min-height:100vh; display:flex; align-items:center; justify-content:center; padding:32px }
        .card{ width:100%; max-width:520px; background:var(--card); border-radius:12px; padding:28px; box-shadow:0 8px 30px rgba(2,6,23,.08) }
        .row{ display:flex; align-items:center; justify-content:space-between; gap:12px; margin-bottom:14px }
        .muted{ color:var(--muted); font-size:13px }
        .field{ margin-bottom:14px }
        label{ display:block; font-size:13px; margin-bottom:6px; font-weight:600 }
        input[type=password]{ width:100%; padding:11px 12px; border:1px solid #e6e9ef; border-radius:8px; font-size:14px; background:#fbfdff }
        .actions{ display:flex; gap:12px; margin-top:8px; justify-content:flex-end; flex-wrap:wrap }
        .btn{ background:var(--accent); color:#fff; padding:10px 14px; border-radius:8px; border:0; cursor:pointer; font-weight:600; text-decoration:none; display:inline-flex; align-items:center; justify-content:center }
        .btn.secondary{ background:#eef2ff; color:var(--accent); border:1px solid rgba(37,99,235,.08) }
        .error{ background:#fff5f5; border:1px solid #f8d7da; color:#b91c1c; padding:10px; border-radius:8px; margin-bottom:12px }
        .success{ background:#effaf3; border:1px solid #bbf7d0; color:#166534; padding:10px; border-radius:8px; margin-bottom:12px }
    </style>
</head>

<body>
    <div class="wrap">
        <div class="card">
            <div class="row">
                <div>
                    <h2 style="margin:0">Đổi mật khẩu</h2>
                    <div class="muted" style="margin-top:6px">Tài khoản: <strong>{{ auth()->user()->username }}</strong></div>
                </div>
                <a class="btn secondary" href="{{ route('dashboard') }}">Về dashboard</a>
            </div>

            @if (session('status'))
            <div class="success">{{ session('status') }}</div>
            @endif

            @if ($errors->any())
            <div class="error">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('password.change.post') }}">
                @csrf
                <div class="field">
                    <label for="current_password">Mật khẩu hiện tại</label>
                    <input id="current_password" name="current_password" type="password" required>
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
                    <a class="btn secondary" href="{{ route('profile.show') }}">Cập nhật thông tin</a>
                    <button class="btn" type="submit">Đổi mật khẩu</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>

