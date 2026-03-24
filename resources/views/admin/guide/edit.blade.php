@extends('layouts.app')

@section('content')
    <h2>Sửa thông tin hướng dẫn viên</h2>

    {{-- @if($errors->any())
        <div style="background:#ffe4e6;padding:10px;border-radius:6px;margin-bottom:12px">
            <ul style="margin:0;padding-left:18px">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif --}}

    <form action="{{ route('admin.guides.update', $guide->guide_id) }}" method="POST" style="max-width:600px">
        @csrf
        @method('PUT')

        <div style="margin-bottom:10px">
            <label for="fullname">Tên</label><br>
            <input id="fullname" name="fullname" value="{{ old('fullname', $guide->user->fullname) }}" style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px" />
        </div>

        <div style="margin-bottom:10px">
            <label for="email">Email</label><br>
            <input id="email" name="email" type="email" value="{{ old('email', $guide->user->email) }}" style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px" />
        </div>

        <div style="margin-bottom:10px">
            <label for="phone">SĐT</label><br>
            <input id="phone" name="phone" value="{{ old('phone', $guide->user->phone) }}" style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px" />
        </div>
        
        <div style="margin-bottom:10px">
            <label for="cccd">CCCD</label><br>
            <input id="cccd" name="cccd" value="{{ old('cccd', $guide->cccd) }}" style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px" />
        </div>

        <div style="margin-bottom:10px">
            <label for="language">Ngôn ngữ</label><br>
            <input id="language" name="language" value="{{ old('language', $guide->language) }}" style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px" />
        </div>

        <div style="margin-bottom:10px">
            <label for="experience">Kinh nghiệm</label><br>
            <textarea id="experience" name="experience" style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px">{{ old('experience', $guide->experience) }}</textarea>
        </div>

        <div style="margin-bottom:10px">
            <label for="certificate">Chứng chỉ</label><br>
            <input id="certificate" name="certificate" value="{{ old('certificate', $guide->certificate) }}" style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px" />
        </div>

        <div style="margin-bottom:10px">
            <label for="specialization">Chuyên môn</label><br>
            <input id="specialization" name="specialization" value="{{ old('specialization', $guide->specialization) }}" style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px" />
        </div>

        <div style="display:flex;gap:8px">
            <a href="{{ route('admin.guides.index') }}" style="padding:8px 12px;border-radius:6px;border:1px solid #e5e7eb;text-decoration:none">Hủy</a>
            <button type="submit" style="background:#0f62fe;color:#fff;padding:8px 12px;border-radius:6px;border:0">Lưu</button>
        </div>
    </form>
@endsection