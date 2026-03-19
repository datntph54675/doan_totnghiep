@extends('admin.layout')

@section('content')
<div class="card">
    <h2 style="margin-bottom:12px">Sửa Category</h2>

    @if($errors->any())
    <div style="padding:10px;background:#fff1f2;border:1px solid #f8d7da;margin-bottom:12px">
        <ul style="margin:0;padding-left:18px">@foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul>
    </div>
    @endif

    <form method="POST" action="{{ route('admin.categories.update', $category->category_id) }}">
        @csrf @method('PUT')
        <div style="margin-bottom:12px;max-width:640px">
            <label>Tên</label><br>
            <input name="name" value="{{ old('name', $category->name) }}" style="width:100%" />
        </div>
        <div style="margin-bottom:12px;max-width:640px">
            <label>Mô tả</label><br>
            <textarea name="description" style="width:100%;height:120px">{{ old('description', $category->description) }}</textarea>
        </div>
        <div>
            <button class="btn-logout" type="submit">Lưu</button>
            <a href="{{ route('admin.categories.index') }}" style="margin-left:8px;background:transparent;border:1px solid #e6eef6;padding:8px 12px;border-radius:8px;color:#0b2540;text-decoration:none">Hủy</a>
        </div>
    </form>
</div>

@endsection