@extends('layouts.app')

@section('title', 'Quản lý Liên hệ')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Danh sách Liên hệ</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Họ tên</th>
                                    <th>Email</th>
                                    <th>Số điện thoại</th>
                                    <th>Tiêu đề</th>
                                    <th>Trạng thái</th>
                                    <th>Thời gian</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($contacts as $contact)
                                    <tr>
                                        <td>{{ $contact->id }}</td>
                                        <td>{{ $contact->fullname }}</td>
                                        <td>{{ $contact->email }}</td>
                                        <td>{{ $contact->phone ?? 'N/A' }}</td>
                                        <td>{{ Str::limit($contact->subject, 30) }}</td>
                                        <td>
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
                                        </td>
                                        <td>{{ $contact->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.contacts.show', $contact) }}"
                                                    class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i> Xem
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Chưa có liên hệ nào.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $contacts->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
