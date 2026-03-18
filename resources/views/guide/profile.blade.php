@extends('guide.layout')

@section('title', 'Hồ sơ cá nhân')
@section('page-title', 'Hồ sơ cá nhân')
@section('page-sub', 'Thông tin hướng dẫn viên của bạn')

@section('content')

<div style="display:grid;grid-template-columns:320px 1fr;gap:20px;align-items:start">

    {{-- LEFT: Avatar card --}}
    <div class="card">
        <div class="card-body" style="text-align:center;padding:32px 24px">
            <div style="width:88px;height:88px;border-radius:20px;background:linear-gradient(135deg,#10b981,#6366f1);display:flex;align-items:center;justify-content:center;color:#fff;font-size:36px;font-weight:800;margin:0 auto 16px">
                {{ strtoupper(substr(auth()->user()->fullname ?? auth()->user()->username, 0, 1)) }}
            </div>
            <div style="font-size:20px;font-weight:700;margin-bottom:4px">{{ auth()->user()->fullname ?? auth()->user()->username }}</div>
            <div style="font-size:13px;color:var(--text-muted);margin-bottom:16px">Hướng dẫn viên du lịch</div>
            <span class="badge badge-success" style="font-size:13px;padding:6px 14px">● Đang hoạt động</span>

            <hr class="divider">

            <div style="text-align:left;display:flex;flex-direction:column;gap:12px">
                <div style="display:flex;align-items:center;gap:10px;font-size:14px">
                    <span style="font-size:18px">📧</span>
                    <span style="color:var(--text-muted)">{{ auth()->user()->email ?? '—' }}</span>
                </div>
                <div style="display:flex;align-items:center;gap:10px;font-size:14px">
                    <span style="font-size:18px">📱</span>
                    <span style="color:var(--text-muted)">{{ auth()->user()->phone ?? '—' }}</span>
                </div>
                @if($guide)
                <div style="display:flex;align-items:center;gap:10px;font-size:14px">
                    <span style="font-size:18px">🌐</span>
                    <span style="color:var(--text-muted)">{{ $guide->language ?? '—' }}</span>
                </div>
                @endif
            </div>

            <hr class="divider">

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;text-align:center">
                <div style="background:#f8fafc;border-radius:10px;padding:12px">
                    <div style="font-size:22px;font-weight:800;color:var(--primary)">{{ $totalTours }}</div>
                    <div style="font-size:12px;color:var(--text-muted);margin-top:2px">Tour đã dẫn</div>
                </div>
                <div style="background:#f8fafc;border-radius:10px;padding:12px">
                    <div style="font-size:22px;font-weight:800;color:#6366f1">{{ $totalCustomers }}</div>
                    <div style="font-size:12px;color:var(--text-muted);margin-top:2px">Khách đã phục vụ</div>
                </div>
            </div>
        </div>
    </div>

    {{-- RIGHT: Info + Edit --}}
    <div style="display:flex;flex-direction:column;gap:20px">

        @if(session('success'))
        <div class="alert alert-success">✅ {{ session('success') }}</div>
        @endif

        @if($errors->any())
        <div class="alert" style="background:#fef2f2;border:1px solid #fecaca;color:#dc2626">
            ⚠️ {{ $errors->first() }}
        </div>
        @endif

        {{-- Thông tin chuyên môn --}}
        @if($guide)
        <div class="card">
            <div class="card-header">
                <div class="card-title">🪪 Thông tin chuyên môn</div>
            </div>
            <div class="card-body">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                    <div>
                        <div style="font-size:12px;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px">Số CCCD</div>
                        <div style="font-size:15px;font-weight:600;font-family:monospace">{{ $guide->cccd ?? '—' }}</div>
                    </div>
                    <div>
                        <div style="font-size:12px;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px">Ngoại ngữ</div>
                        <div style="font-size:15px;font-weight:600">{{ $guide->language ?? '—' }}</div>
                    </div>
                    <div>
                        <div style="font-size:12px;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px">Chứng chỉ</div>
                        <div style="font-size:15px;font-weight:600">{{ $guide->certificate ?? '—' }}</div>
                    </div>
                    <div>
                        <div style="font-size:12px;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px">Chuyên môn</div>
                        <div style="font-size:15px;font-weight:600">{{ $guide->specialization ?? '—' }}</div>
                    </div>
                    <div style="grid-column:1/-1">
                        <div style="font-size:12px;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px">Kinh nghiệm</div>
                        <div style="font-size:14px;color:var(--text);line-height:1.6">{{ $guide->experience ?? '—' }}</div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Form cập nhật --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title">✏️ Cập nhật thông tin</div>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('guide.profile.update') }}">
                    @csrf
                    @method('PUT')

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                        <div class="form-group">
                            <label class="form-label">Họ tên đầy đủ</label>
                            <input type="text" name="fullname" class="form-control"
                                   value="{{ old('fullname', auth()->user()->fullname) }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control"
                                   value="{{ old('email', auth()->user()->email) }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Số điện thoại</label>
                            <input type="text" name="phone" class="form-control"
                                   value="{{ old('phone', auth()->user()->phone) }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Ngoại ngữ</label>
                            <input type="text" name="language" class="form-control"
                                   value="{{ old('language', $guide->language ?? '') }}"
                                   placeholder="VD: Tiếng Anh, Tiếng Pháp">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Chứng chỉ</label>
                            <input type="text" name="certificate" class="form-control"
                                   value="{{ old('certificate', $guide->certificate ?? '') }}"
                                   placeholder="VD: Chứng chỉ HDV Quốc gia">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Chuyên môn</label>
                            <input type="text" name="specialization" class="form-control"
                                   value="{{ old('specialization', $guide->specialization ?? '') }}"
                                   placeholder="VD: Tour miền Bắc, Tour biển đảo">
                        </div>
                        <div class="form-group" style="grid-column:1/-1">
                            <label class="form-label">Kinh nghiệm</label>
                            <textarea name="experience" class="form-control" placeholder="Mô tả kinh nghiệm của bạn...">{{ old('experience', $guide->experience ?? '') }}</textarea>
                        </div>
                    </div>

                    <hr class="divider">

                    <div style="font-size:14px;font-weight:700;color:var(--text);margin-bottom:14px">🔒 Đổi mật khẩu <span style="font-weight:400;color:var(--text-muted)">(để trống nếu không đổi)</span></div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                        <div class="form-group">
                            <label class="form-label">Mật khẩu mới</label>
                            <input type="password" name="password" class="form-control" placeholder="Nhập mật khẩu mới">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Xác nhận mật khẩu</label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Nhập lại mật khẩu">
                        </div>
                    </div>

                    <div style="display:flex;justify-content:flex-end;margin-top:8px">
                        <button type="submit" class="btn btn-primary">💾 Lưu thay đổi</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

@endsection
