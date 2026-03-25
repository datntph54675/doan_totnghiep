@extends('layouts.user')

@section('title', 'Đặt tour - ' . $tour->name)

@push('styles')
<style>
    .booking-hero {
        background: linear-gradient(135deg, #0ea5e9, #6366f1);
        padding: 40px 20px;
        color: #fff;
        text-align: center;
    }
    .booking-hero h1 { font-size: 28px; font-weight: 800; margin-bottom: 6px; }
    .booking-hero p { opacity: .85; font-size: 15px; }

    .booking-body {
        max-width: 900px; margin: 40px auto; padding: 0 20px;
        display: grid; grid-template-columns: 1fr 320px; gap: 28px;
    }
    @media(max-width:768px){ .booking-body { grid-template-columns: 1fr; } }

    .card { background: #fff; border-radius: 14px; border: 1px solid #e2e8f0; overflow: hidden; margin-bottom: 24px; }
    .card-header { padding: 18px 24px; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; gap: 10px; }
    .card-header h3 { font-size: 16px; font-weight: 700; }
    .card-body { padding: 24px; }

    .form-group { margin-bottom: 18px; }
    .form-label { display: block; font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 6px; }
    .form-label span { color: #ef4444; }
    .form-control {
        width: 100%; padding: 10px 14px;
        border: 1px solid #e2e8f0; border-radius: 8px;
        font-size: 14px; color: #1e293b;
        transition: border-color .2s, box-shadow .2s;
        outline: none;
    }
    .form-control:focus { border-color: #0ea5e9; box-shadow: 0 0 0 3px rgba(14,165,233,.1); }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
    @media(max-width:500px){ .form-row { grid-template-columns: 1fr; } }

    .error-msg { color: #ef4444; font-size: 12px; margin-top: 4px; }

    .btn-submit {
        width: 100%; padding: 14px;
        background: linear-gradient(135deg, #0ea5e9, #6366f1);
        color: #fff; border: none; border-radius: 10px;
        font-size: 16px; font-weight: 700; cursor: pointer;
        transition: opacity .2s, transform .15s;
    }
    .btn-submit:hover { opacity: .9; transform: translateY(-1px); }

    /* SIDEBAR */
    .tour-summary { background: #fff; border-radius: 14px; border: 1px solid #e2e8f0; padding: 22px; position: sticky; top: 84px; }
    .tour-summary img { width: 100%; height: 160px; object-fit: cover; border-radius: 10px; margin-bottom: 14px; }
    .tour-summary-placeholder { width: 100%; height: 160px; border-radius: 10px; background: linear-gradient(135deg,#0ea5e9,#6366f1); margin-bottom: 14px; display:flex;align-items:center;justify-content:center;font-size:40px; }
    .tour-summary h4 { font-size: 15px; font-weight: 700; color: #1e293b; margin-bottom: 12px; line-height: 1.4; }
    .summary-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f1f5f9; font-size: 14px; }
    .summary-row:last-child { border-bottom: none; }
    .summary-row .label { color: #64748b; }
    .summary-row .value { font-weight: 600; color: #1e293b; }
    .total-row { display: flex; justify-content: space-between; padding: 14px 0 0; font-size: 16px; font-weight: 800; }
    .total-row .value { color: #0ea5e9; font-size: 20px; }

    .price-calc { background: #f0f9ff; border-radius: 10px; padding: 14px 16px; margin-top: 14px; }
    .price-calc .calc-line { display: flex; justify-content: space-between; font-size: 13px; color: #475569; margin-bottom: 6px; }
    .price-calc .calc-total { display: flex; justify-content: space-between; font-size: 16px; font-weight: 800; color: #0ea5e9; padding-top: 8px; border-top: 1px solid #bae6fd; margin-top: 6px; }

    .back-link { display: inline-flex; align-items: center; gap: 6px; color: #64748b; text-decoration: none; font-size: 14px; padding: 16px 20px 0; }
    .back-link:hover { color: #0ea5e9; }

    select.form-control { background: #fff; }
</style>
@endpush

@section('content')

<a href="{{ route('tours.show', $tour->tour_id) }}" class="back-link">← Quay lại chi tiết tour</a>

<div class="booking-hero">
    <h1>🎒 Đặt tour</h1>
    <p>{{ $tour->name }}</p>
</div>

<div class="booking-body">
    {{-- FORM --}}
    <div>
        <form method="POST" action="{{ route('user.booking.store', $tour->tour_id) }}">
            @csrf

            {{-- CHỌN LỊCH KHỞI HÀNH --}}
            <div class="card">
                <div class="card-header">
                    <span style="font-size:20px">📅</span>
                    <h3>Chọn lịch khởi hành</h3>
                </div>
                <div class="card-body">
                    @if($schedules->count() > 0)
                    <div class="form-group">
                        <label class="form-label">Lịch khởi hành <span>*</span></label>
                        <select name="schedule_id" class="form-control" required>
                            <option value="">-- Chọn ngày khởi hành --</option>
                            @foreach($schedules as $s)
                            <option value="{{ $s->schedule_id }}" {{ old('schedule_id') == $s->schedule_id ? 'selected' : '' }}>
                                {{ $s->start_date->format('d/m/Y') }} → {{ $s->end_date->format('d/m/Y') }}
                                @if($s->meeting_point) | {{ $s->meeting_point }} @endif
                            </option>
                            @endforeach
                        </select>
                        @error('schedule_id')<div class="error-msg">{{ $message }}</div>@enderror
                    </div>
                    @else
                    <div style="text-align:center;padding:20px;color:#94a3b8">
                        😔 Hiện chưa có lịch khởi hành nào. Vui lòng quay lại sau.
                    </div>
                    @endif

                    <div class="form-group">
                        <label class="form-label">Số người <span>*</span></label>
                        <input type="number" name="num_people" class="form-control"
                            value="{{ old('num_people', 1) }}" min="1" max="50"
                            id="numPeople" required>
                        @error('num_people')<div class="error-msg">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            {{-- THÔNG TIN KHÁCH HÀNG --}}
            <div class="card">
                <div class="card-header">
                    <span style="font-size:20px">👤</span>
                    <h3>Thông tin người đặt</h3>
                </div>
                <div class="card-body">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Họ và tên <span>*</span></label>
                            <input type="text" name="fullname" class="form-control"
                                value="{{ old('fullname') }}" placeholder="Nguyễn Văn A" required>
                            @error('fullname')<div class="error-msg">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Giới tính <span>*</span></label>
                            <select name="gender" class="form-control" required>
                                <option value="Nam" {{ old('gender')=='Nam'?'selected':'' }}>Nam</option>
                                <option value="Nữ" {{ old('gender')=='Nữ'?'selected':'' }}>Nữ</option>
                                <option value="Khác" {{ old('gender')=='Khác'?'selected':'' }}>Khác</option>
                            </select>
                            @error('gender')<div class="error-msg">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Số điện thoại <span>*</span></label>
                            <input type="text" name="phone" class="form-control"
                                value="{{ old('phone') }}" placeholder="0901234567" required>
                            @error('phone')<div class="error-msg">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control"
                                value="{{ old('email') }}" placeholder="example@email.com">
                            @error('email')<div class="error-msg">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Ngày sinh</label>
                            <input type="date" name="birthdate" class="form-control" value="{{ old('birthdate') }}">
                            @error('birthdate')<div class="error-msg">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Số CCCD/Hộ chiếu</label>
                            <input type="text" name="id_number" class="form-control"
                                value="{{ old('id_number') }}" placeholder="012345678901">
                            @error('id_number')<div class="error-msg">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Ghi chú</label>
                        <textarea name="note" class="form-control" rows="3"
                            placeholder="Yêu cầu đặc biệt, dị ứng thức ăn...">{{ old('note') }}</textarea>
                    </div>
                </div>
            </div>

            @if($schedules->count() > 0)
            <button type="submit" class="btn-submit">✅ Xác nhận đặt tour</button>
            @endif
        </form>
    </div>

    {{-- SIDEBAR --}}
    <div>
        <div class="tour-summary">
            @if($tour->image)
                <img src="{{ asset('storage/' . $tour->image) }}" alt="{{ $tour->name }}">
            @else
                <div class="tour-summary-placeholder">✈️</div>
            @endif
            <h4>{{ $tour->name }}</h4>

            <div class="summary-row">
                <span class="label">⏱ Thời gian</span>
                <span class="value">{{ $tour->duration ?? '—' }} ngày</span>
            </div>
            <div class="summary-row">
                <span class="label">👥 Tối đa</span>
                <span class="value">{{ $tour->max_people ?? '—' }} người</span>
            </div>
            @if($tour->category)
            <div class="summary-row">
                <span class="label">🏷 Danh mục</span>
                <span class="value">{{ $tour->category->name }}</span>
            </div>
            @endif

            <div class="price-calc">
                <div class="calc-line">
                    <span>Giá/người</span>
                    <span>{{ number_format($tour->price, 0, ',', '.') }} ₫</span>
                </div>
                <div class="calc-line">
                    <span>Số người</span>
                    <span id="displayPeople">1</span>
                </div>
                <div class="calc-total">
                    <span>Tổng tiền</span>
                    <span id="displayTotal">{{ number_format($tour->price, 0, ',', '.') }} ₫</span>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    const price = {{ $tour->price }};
    const input = document.getElementById('numPeople');
    const displayPeople = document.getElementById('displayPeople');
    const displayTotal = document.getElementById('displayTotal');

    function updateTotal() {
        const n = parseInt(input.value) || 1;
        displayPeople.textContent = n + ' người';
        displayTotal.textContent = (price * n).toLocaleString('vi-VN') + ' ₫';
    }

    input.addEventListener('input', updateTotal);
    updateTotal();
</script>
@endpush
