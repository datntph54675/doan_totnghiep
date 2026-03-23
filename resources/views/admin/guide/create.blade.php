@extends('layouts.app')

@section('content')
    <h2>Thêm hướng dẫn viên</h2>

    @if($errors->any())
        <div style="background:#ffe4e6;padding:10px;border-radius:6px;margin-bottom:12px">
            <ul style="margin:0;padding-left:18px">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.guides.store') }}" method="POST" style="max-width:600px">
        @csrf

        <div style="margin-bottom:10px">
            <label for="fullname">Tên người dùng</label><br>
            <input id="fullname" name="fullname" value="{{ old('fullname') }}" style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px" required />
        </div>

        <div style="margin-bottom:10px">
            <label for="email">Email</label><br>
            <input id="email" name="email" type="email" value="{{ old('email') }}" style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px" required />
        </div>

        <div style="margin-bottom:10px">
            <label for="cccd">CCCD</label><br>
            <input id="cccd" name="cccd" value="{{ old('cccd') }}" style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px" />
        </div>

        <div style="margin-bottom:10px">
            <label for="language">Ngôn ngữ</label><br>
            <input id="language" name="language" value="{{ old('language') }}" style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px" />
        </div>

        <div style="margin-bottom:10px">
            <label for="certificate">Chứng chỉ</label><br>
            <input id="certificate" name="certificate" value="{{ old('certificate') }}" style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px" />
        </div>

        <div style="margin-bottom:10px">
            <label for="experience">Kinh nghiệm</label><br>
            <textarea id="experience" name="experience" style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px">{{ old('experience') }}</textarea>
        </div>

        <div style="margin-bottom:10px">
            <label for="specialization">Chuyên môn</label><br>
            <input id="specialization" name="specialization" value="{{ old('specialization') }}" style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px" />
        </div>

        <div style="display:flex;gap:8px">
            <button type="submit" style="background:#0f62fe;color:#fff;padding:8px 12px;border-radius:6px;border:0">Lưu</button>
            <a href="{{ route('admin.guides.index') }}" style="padding:8px 12px;border-radius:6px;border:1px solid #e5e7eb;text-decoration:none">Hủy</a>
        </div>
    </form>
@endsection