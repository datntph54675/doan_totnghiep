@extends('layouts.app')

@section('title', 'Chi tiết Liên hệ')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Chi tiết Liên hệ #{{ $contact->id }}</h4>
                        <a href="{{ route('admin.contacts.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Quay lại
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Thông tin Liên hệ</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-sm-3"><strong>Họ tên:</strong></div>
                                        <div class="col-sm-9">{{ $contact->fullname }}</div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-3"><strong>Email:</strong></div>
                                        <div class="col-sm-9">
                                            <a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-3"><strong>Số điện thoại:</strong></div>
                                        <div class="col-sm-9">{{ $contact->phone ?? 'N/A' }}</div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-3"><strong>Tiêu đề:</strong></div>
                                        <div class="col-sm-9">{{ $contact->subject }}</div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-3"><strong>Thời gian gửi:</strong></div>
                                        <div class="col-sm-9">{{ $contact->created_at->format('d/m/Y H:i:s') }}</div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-3"><strong>Trạng thái:</strong></div>
                                        <div class="col-sm-9">
                                            <span
                                                class="badge
                                            @if ($contact->status === 'pending') bg-warning
                                            @elseif($contact->status === 'replied') bg-success
                                            @else bg-secondary @endif">
                                                @if ($contact->status === 'pending')
                                                    Chờ xử lý
                                                @elseif($contact->status === 'replied')
                                                    Đã trả lời
                                                @else
                                                    Kết thúc
                                                @endif
                                            </span>
                                            @if ($contact->replied_at)
                                                <small class="text-muted ms-2">
                                                    (Trả lời lúc: {{ $contact->replied_at->format('d/m/Y H:i') }})
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mt-3">
                                <div class="card-header">
                                    <h5 class="mb-0">Nội dung Tin nhắn</h5>
                                </div>
                                <div class="card-body">
                                    <div class="bg-light p-3 rounded">
                                        {!! nl2br(e($contact->message)) !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Thao tác</h5>
                                </div>
                                <div class="card-body">
                                    @if($contact->status === 'pending')
                                        <form action="{{ route('admin.contacts.update', $contact) }}" method="POST" class="mb-3">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="replied">
                                            <button type="submit" class="btn btn-success w-100">
                                                <i class="fas fa-reply"></i> Đánh dấu đã trả lời
                                            </button>
                                        </form>
                                    @elseif($contact->status === 'replied')
                                        <form action="{{ route('admin.contacts.update', $contact) }}" method="POST" class="mb-3">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="closed">
                                            <button type="submit" class="btn btn-secondary w-100">
                                                <i class="fas fa-times"></i> Kết thúc liên hệ
                                            </button>
                                        </form>
                                    @else
                                        <div class="alert alert-info">
                                            <i class="fas fa-lock"></i> Liên hệ này đã kết thúc và không thể thay đổi trạng thái.
                                        </div>
                                    @endif

                                    <form action="{{ route('admin.contacts.destroy', $contact) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger w-100"
                                            onclick="return confirm('Bạn có chắc muốn xóa liên hệ này?')">
                                            <i class="fas fa-trash"></i> Xóa liên hệ
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
