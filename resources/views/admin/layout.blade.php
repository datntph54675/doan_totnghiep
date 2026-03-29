<!doctype html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Admin</title>
    <link href="/css/app.css" rel="stylesheet">
    <style>
        :root {
            --accent: #0f62fe;
            --muted: #6b7280;
            --card: #fff;
        }

        body {
            font-family: Inter, system-ui, Segoe UI, Roboto, Arial;
            margin: 0;
            background: #f6fbff;
            color: #0b2540;
        }

        .container {
            max-width: 1100px;
            margin: 28px auto;
            padding: 20px;
        }

        h2 {
            margin: 0 0 12px
        }

        .card {
            background: var(--card);
            border-radius: 12px;
            padding: 16px;
            box-shadow: 0 8px 30px rgba(2, 6, 23, 0.06)
        }

        .table-card {
            background: var(--card);
            border-radius: 12px;
            padding: 14px;
            box-shadow: 0 8px 30px rgba(2, 6, 23, 0.06)
        }

        table.admin-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px
        }

        table.admin-table thead th {
            background: linear-gradient(90deg, #f3f7ff, #eef6ff);
            color: #0b2540;
            font-weight: 700;
            padding: 10px;
            text-align: left
        }

        table.admin-table tbody td {
            padding: 10px;
            vertical-align: middle;
            border-top: 1px solid #f1f5f9
        }

        .btn-logout {
            background: var(--accent);
            color: #fff;
            padding: 8px 12px;
            border-radius: 8px;
            border: 0;
            display: inline-block;
            text-decoration: none
        }

        .muted-sm {
            color: var(--muted);
            font-size: 13px
        }

        .actions {
            display: flex;
            gap: 8px;
            align-items: center;
            justify-content: flex-end
        }

        input[type="text"],
        input,
        textarea {
            border: 1px solid #e6eef6;
            padding: 8px;
            border-radius: 8px
        }

        @media(max-width:900px) {
            .container {
                padding: 14px
            }
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
