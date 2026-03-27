@extends('admin.layout')

@section('content')
<style>
    .card {
        background: #ffffff;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 6px 18px rgba(20, 41, 75, 0.06);
        max-width: 980px;
        margin: auto;
    }

    .card h2 {
        margin: 0 0 12px 0;
        color: #0f4c81;
    }

    .form-row {
        display: flex;
        gap: 12px;
        margin-bottom: 12px;
    }

    .form-row .col {
        flex: 1;
    }

    .form-row .col-sm {
        width: 160px;
        flex: 0 0 160px;
    }

    label.field {
        display: block;
        font-weight: 600;
        color: #16325c;
        margin-bottom: 6px;
        font-size: 14px;
    }

    input[type="text"],
    input[type="number"],
    input[type="date"],
    select,
    textarea {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #e6eef6;
        border-radius: 8px;
        background: #f8fbff;
        color: #0b2b45;
    }

    textarea {
        min-height: 110px;
        resize: vertical;
    }

    .btn-primary {
        background: #0f6fbf;
        color: #fff;
        border: none;
        padding: 10px 16px;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
    }

    .btn-secondary {
        background: transparent;
        color: #335;
        border: 1px solid #d0dbe8;
        padding: 9px 14px;
        border-radius: 8px;
        text-decoration: none;
        display: inline-block;
    }

    .errors {
        background: #fff5f5;
        color: #7a1c1c;
        border: 1px solid #ffd6d6;
        padding: 10px 12px;
        border-radius: 8px;
        margin-bottom: 12px;
    }

    .file-input {
        padding: 6px;
    }

    .thumb {
        height: 64px;
        width: 96px;
        object-fit: cover;
        border-radius: 6px;
        display: block;
    }

    @media (max-width:720px) {
        .form-row {
            flex-direction: column;
        }

        .form-row .col-sm {
            width: 100%;
            flex: 1;
        }
    }
</style>

<div class="card">
    <h2>Sửa Tour</h2>

    @if($errors->any())
    <div class="errors">
        <strong>Vui lòng sửa lỗi sau:</strong>
        <ul style="margin:8px 0 0 16px;padding:0">
            @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('admin.tours.update', $tour->tour_id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-row">
            <div class="col">
                <label class="field">Danh mục</label>
                <select name="category_id">
                    <option value="">-- Chọn category --</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->category_id }}" {{ old('category_id', $tour->category_id) == $cat->category_id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm">
                <label class="field">Trạng thái</label>
                <select name="status">
                    <option value="active" {{ old('status', $tour->status)=='active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status', $tour->status)=='inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
        </div>

        <div style="margin-bottom:12px">
            <label class="field">Tên</label>
            <input type="text" name="name" value="{{ old('name', $tour->name) }}" required>
        </div>

        <div style="margin-bottom:12px">
            <label class="field">Mô tả</label>
            <textarea name="description">{{ old('description', $tour->description) }}</textarea>
        </div>

        <div style="margin-bottom:12px">
            <label class="field">Chính sách</label>
            <textarea name="policy">{{ old('policy', $tour->policy) }}</textarea>
        </div>

        <div class="form-row">
            <div class="col">
                <label class="field">Nhà cung cấp</label>
                <input type="text" name="supplier" value="{{ old('supplier', $tour->supplier) }}">
            </div>
            <div class="col-sm">
                <label class="field">Ảnh</label>
                @if($tour->image)
                <a href="{{ strpos($tour->image,'http')===0 ? $tour->image : asset('storage/'.$tour->image) }}" target="_blank">
                    <img class="thumb" src="{{ strpos($tour->image,'http')===0 ? $tour->image : asset('storage/'.$tour->image) }}" alt="Ảnh tour">
                </a>
                @endif
                <input class="file-input" type="file" name="image">
            </div>
        </div>

        <div class="form-row">
            <div class="col">
                <label class="field">Giá</label>
                <input type="number" step="0.01" name="price" value="{{ old('price', $tour->price) }}">
            </div>
            <div class="col-sm">
                <label class="field">Thời lượng (ngày)</label>
                <input type="number" name="duration" value="{{ old('duration', $tour->duration) }}">
            </div>
            <div class="col-sm">
                <label class="field">Số người</label>
                <input type="number" name="max_people" value="{{ old('max_people', $tour->max_people) }}">
            </div>
        </div>

        <div class="form-row">
            <div class="col">
                <label class="field">Ngày bắt đầu</label>
                <input type="date" name="start_date" value="{{ old('start_date', $tour->start_date) }}">
            </div>
            <div class="col">
                <label class="field">Ngày kết thúc</label>
                <input type="date" name="end_date" value="{{ old('end_date', $tour->end_date) }}">
            </div>
        </div>

        <div style="display:flex;gap:12px;margin-top:14px;align-items:center">
            <button type="submit" class="btn-primary">Lưu</button>
            <a href="{{ route('admin.tours.index') }}" class="btn-secondary">Hủy</a>
        </div>

    </form>
</div>

@endsection