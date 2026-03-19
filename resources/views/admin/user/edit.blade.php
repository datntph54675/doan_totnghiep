@extends('admin.layout')

@section('content')
    <h2>Sửa thông tin người dùng</h2>

    @if($errors->any())
        <div style="background:#ffe4e6;padding:10px;border-radius:6px;margin-bottom:12px">
            <ul style="margin:0;padding-left:18px">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ url('admin/users/'.$user->user_id) }}" method="POST" style="max-width:600px">
        @csrf
        @method('PUT')

        <div style="margin-bottom:10px">
            <label for="fullname">Tên</label><br>
            <input id="fullname" name="fullname" value="{{ old('fullname', $user->fullname) }}" style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px" />
        </div>

        <div style="margin-bottom:10px">
            <label for="email">Email</label><br>
            <input id="email" name="email" value="{{ old('email', $user->email) }}" style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px" />
        </div>

        <div style="margin-bottom:10px">
            <label for="role">Vai trò</label><br>
            <select id="role" name="role" style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px">
                <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>user</option>
                <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>admin</option>
                <option value="tour_guide" {{ old('role', $user->role) === 'tour_guide' ? 'selected' : '' }}>tour_guide</option>
            </select>
        </div>

        <div style="display:flex;gap:8px">
            <button type="submit" style="background:#0f62fe;color:#fff;padding:8px 12px;border-radius:6px;border:0">Lưu</button>
            <a href="{{ route('admin.dashboard') }}" style="padding:8px 12px;border-radius:6px;border:1px solid #e5e7eb;text-decoration:none">Hủy</a>
        </div>
    </form>
@endsection
