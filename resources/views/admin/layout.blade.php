<!doctype html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Admin</title>
    <link href="/css/app.css" rel="stylesheet">
    <style>
        body {
            font-family: Inter, system-ui, Segoe UI, Roboto, Arial;
            margin: 0;
            background: transparent
        }

        .container {
            padding: 20px
        }

        h2 {
            margin: 0 0 12px
        }

        .btn-logout {
            background: #0f62fe;
            color: #fff;
            padding: 8px 12px;
            border-radius: 8px;
            border: 0
        }
    </style>
</head>

<body>
    <div class="container">
        <a href="{{ route('admin.dashboard') }}" style="display:inline-block;margin-bottom:12px">← Dashboard</a>
        @yield('content')
    </div>
</body>

</html>