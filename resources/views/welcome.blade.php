@extends('layouts.user')

@section('title', 'GoTour - Khám Phá Việt Nam Cùng Chúng Tôi')

@section('styles')
<style>
/* ===== HERO ===== */
.hero {
    min-height: 92vh;
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 40%, #0f3460 100%);
    display: flex; align-items: center; position: relative; overflow: hidden;
}
.hero::before {
    content: '';
    position: absolute; inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%230066cc' fill-opacity='0.06'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}
.hero-particles {
    position: absolute; inset: 0; overflow: hidden; pointer-events: none;
}
.particle {
    position: absolute; border-radius: 50%;
    background: rgba(0,102,204,.15);
    animation: float linear infinite;
}
@keyframes float {
    0% { transform: translateY(100vh) scale(0); opacity: 0; }
    10% { opacity: 1; }
    90% { opacity: 1; }
    100% { transform: translateY(-100px) scale(1); opacity: 0; }
}
.hero-content { position: relative; z-index: 2; color: #fff; }
.hero-badge {
    display: inline-flex; align-items: center; gap: 8px;
    background: rgba(255,255,255,.1); backdrop-filter: blur(8px);
    border: 1px solid rgba(255,255,255,.2);
    padding: 8px 18px; border-radius: 50px; font-size: .85rem;
    color: rgba(255,255,255,.9); margin-bottom: 28px;
    animation: fadeDown .8s ease;
}
.hero-badge .dot { width: 8px; height: 8px; border-radius: 50%; background: #10b981; animation: pulse 2s infinite; }
@keyframes pulse { 0%,100%{opacity:1} 50%{opacity:.4} }
@keyframes fadeDown { from{opacity:0;transform:translateY(-20px)} to{opacity:1;transform:none} }
.hero h1 {
    font-size: clamp(2.2rem, 5vw, 3.8rem); font-weight: 800; line-height: 1.15;
    margin-bottom: 20px; animation: fadeUp .8s ease .1s both;
}
.hero h1 span { color: #60a5fa; }
.hero p {
    font-size: 1.1rem; color: rgba(255,255,255,.75); max-width: 540px;
    margin-bottom: 36px; line-height: 1.8; animation: fadeUp .8s ease .2s both;
}
@keyframes fadeUp { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:none} }
.hero-btns { display: flex; gap: 14px; flex-wrap: wrap; animation: fadeUp .8s ease .3s both; }
.hero-stats {
    display: flex; gap: 40px; margin-top: 56px;
    animation: fadeUp .8s ease .4s both;
}
.hero-stat { text-align: center; }
.hero-stat-num { font-size: 2rem; font-weight: 800; color: #60a5fa; }
.hero-stat-label { font-size: .8rem; color: rgba(255,255,255,.6); margin-top: 4px; }

/* ===== SEARCH BOX ===== */
.search-floating {
    position: relative; z-index: 10; margin-top: -40px; margin-bottom: 60px;
}
.search-box {
    background: #fff; border-radius: var(--radius-lg);
    box-shadow: 0 20px 60px rgba(0,0,0,.18);
    padding: 28px 32px;
}
.search-box-title { font-size: 1.1rem; font-weight: 700; margin-bottom: 20px; color: var(--text-dark); }
.search-form {
    display: grid; grid-template-columns: 2fr 1fr 1fr auto;
    gap: 12px; align-items: end;
}
.search-field label {
    display: block; font-size: .8rem; font-weight: 600;
    color: var(--text-mid); margin-bottom: 6px;
}
.search-field input, .search-field select {
    width: 100%; padding: 12px 16px;
    border: 1.5px solid var(--border); border-radius: var(--radius-sm);
    font-size: .9rem; font-family: inherit; color: var(--text-dark);
    transition: border-color var(--transition), box-shadow var(--transition);
    background: var(--bg-light);
}
.search-field input:focus, .search-field select:focus {
    outline: none; border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(0,102,204,.12);
    background: #fff;
}
.search-btn {
    padding: 12px 28px; background: var(--accent); color: #fff;
    border-radius: var(--radius-sm); font-weight: 700; font-size: .95rem;
    border: none; cursor: pointer; white-space: nowrap;
    transition: all var(--transition);
    display: flex; align-items: center; gap: 8px;
}
.search-btn:hover { background: var(--accent-dark); transform: translateY(-1px); }

/* ===== CATEGORIES ===== */
.categories-section { padding: 60px 0; background: var(--bg-light); }
.categories-grid { display: flex; gap: 14px; flex-wrap: wrap; }
.category-chip {
    display: flex; align-items: center; gap: 8px;
    padding: 10px 20px; border-radius: 50px;
    background: #fff; border: 1.5px solid var(--border);
    font-weight: 600; font-size: .875rem; color: var(--text-mid);
    cursor: pointer; transition: all var(--transition); text-decoration: none;
}
.category-chip:hover, .category-chip.active {
    background: var(--primary); color: #fff; border-color: var(--primary);
    transform: translateY(-2px); box-shadow: 0 6px 16px rgba(0,102,204,.25);
}
.category-chip i { font-size: .9rem; }

/* ===== FEATURED TOURS ===== */
.featured-section { padding: 80px 0; }
.section-header { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 40px; }
.tours-grid {
    display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px;
}
.tour-card-img .tag {
    position: absolute; top: 12px; right: 12px;
    background: var(--accent); color: #fff;
    font-size: .7rem; font-weight: 700; padding: 4px 10px; border-radius: 50px;
}

/* ===== WHY US ===== */
.why-section { padding: 80px 0; background: var(--text-dark); }
.why-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 32px; }
.why-card { text-align: center; }
.why-icon {
    width: 64px; height: 64px; border-radius: 18px;
    background: rgba(0,102,204,.2);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 16px; font-size: 1.5rem; color: #60a5fa;
}
.why-title { font-size: 1rem; font-weight: 700; color: #fff; margin-bottom: 8px; }
.why-desc { font-size: .875rem; color: #718096; line-height: 1.7; }

/* ===== CTA BANNER ===== */
.cta-section {
    padding: 80px 0;
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
    position: relative; overflow: hidden;
}
.cta-section::before {
    content:''; position:absolute; top:-50%; right:-10%;
    width: 400px; height: 400px; border-radius: 50%;
    background: rgba(255,255,255,.06);
}
.cta-inner { text-align: center; position: relative; z-index: 1; }
.cta-inner h2 { font-size: 2rem; font-weight: 800; color: #fff; margin-bottom: 12px; }
.cta-inner p { color: rgba(255,255,255,.8); font-size: 1rem; margin-bottom: 32px; }
.btn-white {
    background: #fff; color: var(--primary); font-weight: 700; padding: 14px 36px; border-radius: 50px;
    transition: all var(--transition);
}
.btn-white:hover { transform: translateY(-2px); box-shadow: 0 10px 30px rgba(0,0,0,.2); }

@media (max-width: 1024px) { .tours-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 768px) {
    .search-form { grid-template-columns: 1fr; }
    .hero-stats { gap: 24px; }
    .tours-grid { grid-template-columns: repeat(2, 1fr); }
    .why-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 480px) { .tours-grid, .why-grid { grid-template-columns: 1fr; } }
</style>
@endsection

@section('content')

<!-- HERO -->
<section class="hero">
    <div class="hero-particles" id="particles"></div>
    <div class="container">
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:60px; align-items:center; padding:100px 0 160px;">
            <div class="hero-content">
                <div class="hero-badge">
                    <span class="dot"></span>
                    🌏 Hơn {{ $stats['tours'] }} tour đang mở đặt chỗ
                </div>
                <h1>Khám Phá <span>Việt Nam</span> Theo Cách Của Bạn</h1>
                <p>Chúng tôi mang đến hành trình trọn gói — từ Hà Nội đến Hội An, từ Sapa đến Phú Quốc. Đặt tour dễ dàng, an toàn, giá tốt nhất.</p>
                <div class="hero-btns">
                    <a href="{{ route('tours.index') }}" class="btn btn-accent">
                        <i class="fas fa-search"></i> Tìm Tour Ngay
                    </a>
                    <a href="#featured" class="btn" style="background:rgba(255,255,255,.12); color:#fff; border:1.5px solid rgba(255,255,255,.3); backdrop-filter:blur(8px);">
                        <i class="fas fa-play-circle"></i> Xem Tour Nổi Bật
                    </a>
                </div>
                <div class="hero-stats">
                    <div class="hero-stat">
                        <div class="hero-stat-num">{{ $stats['tours_all'] }}+</div>
                        <div class="hero-stat-label">Tour Du Lịch</div>
                    </div>
                    <div class="hero-stat">
                        <div class="hero-stat-num">{{ $stats['customers'] }}+</div>
                        <div class="hero-stat-label">Khách Hàng</div>
                    </div>
                    <div class="hero-stat">
                        <div class="hero-stat-num">5★</div>
                        <div class="hero-stat-label">Đánh Giá TB</div>
                    </div>
                </div>
            </div>
            <!-- Hero Illustration -->
            <div style="display:flex;align-items:center;justify-content:center;">
                <div style="width:400px;height:400px;position:relative;">
                    <div style="position:absolute;inset:0;background:rgba(0,102,204,.15);border-radius:50%;animation:pulse 3s infinite;"></div>
                    <div style="position:absolute;inset:20px;background:rgba(0,102,204,.1);border-radius:50%;animation:pulse 3s infinite .5s;"></div>
                    <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;flex-direction:column;color:rgba(255,255,255,.6);font-size:5rem;">
                        🗺️
                        <div style="font-size:.9rem;margin-top:10px;color:rgba(255,255,255,.4);">Bản đồ du lịch</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- SEARCH BOX -->
<section class="container">
    <div class="search-floating">
        <div class="search-box">
            <div class="search-box-title">🔍 Tìm kiếm tour phù hợp với bạn</div>
            <form action="{{ route('tours.index') }}" method="GET">
                <div class="search-form">
                    <div class="search-field">
                        <label>Tên tour hoặc điểm đến</label>
                        <input type="text" name="search" placeholder="Ví dụ: Hạ Long, Sapa, Đà Nẵng...">
                    </div>
                    <div class="search-field">
                        <label>Danh mục</label>
                        <select name="category">
                            <option value="">Tất cả danh mục</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->category_id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="search-field">
                        <label>Số ngày</label>
                        <select name="duration">
                            <option value="">Bất kỳ</option>
                            <option value="2">2 ngày</option>
                            <option value="3">3 ngày</option>
                            <option value="4">4 ngày</option>
                            <option value="5">5 ngày</option>
                            <option value="7">7 ngày+</option>
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="search-btn">
                            <i class="fas fa-search"></i> Tìm Kiếm
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- CATEGORIES -->
<section class="categories-section" id="about">
    <div class="container">
        <div class="section-header">
            <div>
                <h2 class="section-title">Danh Mục Tour</h2>
                <p class="section-sub">Khám phá theo phong cách bạn yêu thích</p>
            </div>
        </div>
        <div class="categories-grid">
            <a href="{{ route('tours.index') }}" class="category-chip active">
                <i class="fas fa-globe"></i> Tất cả
            </a>
            @foreach($categories as $cat)
            <a href="{{ route('tours.index', ['category' => $cat->category_id]) }}" class="category-chip">
                <i class="fas fa-map-pin"></i> {{ $cat->name }}
            </a>
            @endforeach
            <a href="{{ route('tours.index') }}" class="category-chip">
                <i class="fas fa-mountain"></i> Sinh thái
            </a>
            <a href="{{ route('tours.index') }}" class="category-chip">
                <i class="fas fa-umbrella-beach"></i> Biển đảo
            </a>
            <a href="{{ route('tours.index') }}" class="category-chip">
                <i class="fas fa-city"></i> Thành phố
            </a>
        </div>
    </div>
</section>

<!-- FEATURED TOURS -->
<section class="featured-section" id="featured">
    <div class="container">
        <div class="section-header">
            <div>
                <span class="badge badge-blue" style="margin-bottom:10px;display:inline-block">⭐ NỔI BẬT</span>
                <h2 class="section-title">Tour Được Yêu Thích</h2>
                <p class="section-sub">Những hành trình được khách hàng đánh giá cao nhất</p>
            </div>
            <a href="{{ route('tours.index') }}" class="btn btn-outline">
                Xem tất cả <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        @if($featuredTours->count() > 0)
        <div class="tours-grid">
            @foreach($featuredTours as $i => $tour)
            <a href="{{ route('tours.show', $tour->tour_id) }}" class="tour-card">
                <div class="tour-card-img">
                    @if($tour->image && file_exists(storage_path('app/public/' . $tour->image)))
                        <img src="{{ Storage::url($tour->image) }}" alt="{{ $tour->name }}" loading="lazy">
                    @else
                        <div class="tour-card-img-placeholder" style="background: linear-gradient(135deg,
                            {{ ['#667eea,#764ba2','#f093fb,#f5576c','#4facfe,#00f2fe','#43e97b,#38f9d7','#fa709a,#fee140','#30cfd0,#667eea','#a18cd1,#fbc2eb','#fda085,#f6d365'][$i % 8] }})">
                            <i class="fas fa-map-marked-alt"></i>
                        </div>
                    @endif
                    @if($i < 3)
                        <div class="tag">🔥 HOT</div>
                    @endif
                    <div class="tour-card-overlay">
                        @if($tour->category)
                            <span class="badge badge-blue" style="font-size:.7rem">{{ $tour->category->name }}</span>
                        @endif
                    </div>
                </div>
                <div class="tour-card-body">
                    <div class="tour-card-name">{{ $tour->name }}</div>
                    <div class="tour-card-meta">
                        @if($tour->duration)
                        <span><i class="fas fa-clock"></i> {{ $tour->duration }} ngày</span>
                        @endif
                        @if($tour->max_people)
                        <span><i class="fas fa-users"></i> Tối đa {{ $tour->max_people }}</span>
                        @endif
                    </div>
                    <div class="tour-card-footer">
                        <div class="tour-price">
                            <small>Từ</small>
                            {{ $tour->price ? number_format($tour->price, 0, ',', '.') . ' đ' : 'Liên hệ' }}
                        </div>
                        <span class="btn btn-accent" style="padding:8px 16px; font-size:.8rem">Đặt ngay</span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        @else
        <div style="text-align:center; padding:60px 0; color:var(--text-light);">
            <div style="font-size:4rem; margin-bottom:16px;">😟</div>
            <h3>Chưa có tour nào</h3>
            <p style="margin-top:8px">Vui lòng quay lại sau.</p>
        </div>
        @endif
    </div>
</section>

<!-- WHY US -->
<section class="why-section">
    <div class="container">
        <div style="text-align:center; margin-bottom:48px;">
            <span class="badge badge-blue" style="margin-bottom:12px;display:inline-block">💎 UY TÍN</span>
            <h2 class="section-title" style="color:#fff">Tại Sao Chọn GoTour?</h2>
            <p style="color:#718096">Chúng tôi cam kết mang lại trải nghiệm tuyệt vời nhất cho bạn</p>
        </div>
        <div class="why-grid">
            <div class="why-card">
                <div class="why-icon"><i class="fas fa-shield-alt"></i></div>
                <div class="why-title">Uy Tín & An Toàn</div>
                <div class="why-desc">Cam kết hoàn tiền 100% nếu tour bị hủy. Bảo hiểm du lịch đầy đủ cho mọi chuyến đi.</div>
            </div>
            <div class="why-card">
                <div class="why-icon"><i class="fas fa-dollar-sign"></i></div>
                <div class="why-title">Giá Tốt Nhất</div>
                <div class="why-desc">So sánh và tìm mức giá tốt nhất. Không phụ phí ẩn, minh bạch từ đầu đến cuối.</div>
            </div>
            <div class="why-card">
                <div class="why-icon"><i class="fas fa-user-tie"></i></div>
                <div class="why-title">HDV Chuyên Nghiệp</div>
                <div class="why-desc">Đội ngũ hướng dẫn viên giàu kinh nghiệm, nhiệt tình, thông thạo nhiều ngoại ngữ.</div>
            </div>
            <div class="why-card">
                <div class="why-icon"><i class="fas fa-headset"></i></div>
                <div class="why-title">Hỗ Trợ 24/7</div>
                <div class="why-desc">Đội hỗ trợ sẵn sàng phục vụ bạn mọi lúc, trước, trong và sau chuyến đi.</div>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="cta-section">
    <div class="container">
        <div class="cta-inner">
            <h2>Sẵn Sàng Cho Chuyến Đi Tiếp Theo? ✈️</h2>
            <p>Hàng trăm tour hấp dẫn đang chờ bạn khám phá. Đặt ngay hôm nay!</p>
            <a href="{{ route('tours.index') }}" class="btn btn-white">
                <i class="fas fa-compass"></i> Khám Phá Ngay
            </a>
        </div>
    </div>
</section>

@endsection

@section('scripts')
<script>
// Tạo hiệu ứng particles
const container = document.getElementById('particles');
for (let i = 0; i < 18; i++) {
    const p = document.createElement('div');
    p.className = 'particle';
    const size = Math.random() * 60 + 20;
    p.style.cssText = `
        width:${size}px; height:${size}px;
        left:${Math.random() * 100}%;
        animation-duration:${Math.random() * 15 + 10}s;
        animation-delay:${Math.random() * 10}s;
    `;
    container.appendChild(p);
}
</script>
@endsection
