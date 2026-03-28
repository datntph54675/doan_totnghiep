@extends('layouts.user')

@section('title', 'Giới thiệu - VietTour')
@section('meta_description', 'VietTour - Đồng hành cùng bạn khám phá Việt Nam. Sứ mệnh, giá trị và cam kết chất lượng dịch vụ du lịch.')

@section('styles')
<style>
.page-hero {
    position: relative; min-height: 52vh;
    background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 45%, #0c4a6e 100%);
    display: flex; align-items: center; overflow: hidden;
}
.page-hero::before {
    content: ''; position: absolute; inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='80' height='80' viewBox='0 0 80 80' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%2360a5fa' fill-opacity='0.05'%3E%3Cpath d='M48 44v-6h-4v6h-6v4h6v6h4v-6h6v-4h-6zm0-40V0h-4v4h-6v4h6v6h4V8h6V4h-6zM8 44v-6H4v6H0v4h4v6h4v-6h6v-4H8zM8 4V0H4v4H0v4h4v6h4V8h6V4H8z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    opacity: .9;
}
.page-hero-glow {
    position: absolute; width: 480px; height: 480px; border-radius: 50%;
    background: radial-gradient(circle, rgba(0,102,204,.35) 0%, transparent 70%);
    top: -120px; right: -80px; pointer-events: none;
}
.page-hero-inner { position: relative; z-index: 2; padding: 100px 0 88px; color: #fff; }
.page-hero-inner .eyebrow {
    display: inline-flex; align-items: center; gap: 8px;
    font-size: .8rem; font-weight: 600; letter-spacing: .06em; text-transform: uppercase;
    color: rgba(255,255,255,.75); margin-bottom: 16px;
}
.page-hero-inner h1 {
    font-size: clamp(2rem, 4.5vw, 3.25rem); font-weight: 800; line-height: 1.15;
    margin-bottom: 16px; max-width: 640px;
}
.page-hero-inner h1 span { color: #7dd3fc; }
.page-hero-inner p {
    font-size: 1.05rem; color: rgba(255,255,255,.78); max-width: 560px; line-height: 1.75;
}

.story-section { padding: 88px 0; background: var(--bg-white); }
.story-grid {
    display: grid; grid-template-columns: 1.1fr 1fr; gap: 56px; align-items: center;
}
.story-visual {
    position: relative; border-radius: var(--radius-lg); overflow: hidden;
    min-height: 380px;
    background: linear-gradient(145deg, #e8f2ff 0%, #dbeafe 50%, #fef3c7 100%);
    box-shadow: var(--shadow-lg);
}
.story-visual::after {
    content: '✈'; position: absolute; font-size: 8rem; opacity: .12;
    right: 8%; bottom: 5%; transform: rotate(-12deg);
}
.story-badge {
    position: absolute; top: 24px; left: 24px;
    background: rgba(255,255,255,.95); backdrop-filter: blur(8px);
    padding: 10px 18px; border-radius: 50px; font-size: .8rem; font-weight: 700;
    color: var(--primary); box-shadow: var(--shadow-sm);
}
.story-content h2 { font-size: 2rem; font-weight: 800; margin-bottom: 16px; color: var(--text-dark); }
.story-content p { color: var(--text-mid); line-height: 1.85; margin-bottom: 14px; font-size: .98rem; }

.values-section {
    padding: 88px 0;
    background: linear-gradient(180deg, var(--bg-light) 0%, #fff 100%);
}
.values-header { text-align: center; max-width: 560px; margin: 0 auto 48px; }
.values-grid {
    display: grid; grid-template-columns: repeat(4, 1fr); gap: 22px;
}
.value-card {
    background: #fff; border-radius: var(--radius-md); padding: 28px 22px;
    border: 1px solid var(--border); box-shadow: var(--shadow-sm);
    transition: transform var(--transition), box-shadow var(--transition), border-color var(--transition);
}
.value-card:hover {
    transform: translateY(-6px); box-shadow: var(--shadow-lg); border-color: transparent;
}
.value-icon {
    width: 52px; height: 52px; border-radius: 14px;
    background: linear-gradient(135deg, var(--primary-light), #dbeafe);
    display: flex; align-items: center; justify-content: center;
    color: var(--primary); font-size: 1.25rem; margin-bottom: 16px;
}
.value-card h3 { font-size: 1.05rem; font-weight: 700; margin-bottom: 8px; color: var(--text-dark); }
.value-card p { font-size: .875rem; color: var(--text-light); line-height: 1.65; }

.stats-strip {
    padding: 56px 0;
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
    position: relative; overflow: hidden;
}
.stats-strip::before {
    content: ''; position: absolute; inset: 0;
    background: radial-gradient(circle at 20% 50%, rgba(255,255,255,.12) 0%, transparent 45%);
}
.stats-grid {
    display: grid; grid-template-columns: repeat(4, 1fr); gap: 32px;
    position: relative; z-index: 1; text-align: center; color: #fff;
}
.stat-item-num { font-size: 2.5rem; font-weight: 800; line-height: 1; }
.stat-item-num small { font-size: 1rem; font-weight: 600; opacity: .85; }
.stat-item-label { font-size: .85rem; opacity: .88; margin-top: 8px; }

.timeline-section { padding: 88px 0; background: var(--bg-white); }
.timeline-header { text-align: center; margin-bottom: 48px; }
.timeline {
    max-width: 800px; margin: 0 auto; position: relative;
    padding-left: 28px; border-left: 3px solid var(--primary-light);
}
.timeline-item { position: relative; padding-bottom: 36px; padding-left: 28px; }
.timeline-item:last-child { padding-bottom: 0; }
.timeline-item::before {
    content: ''; position: absolute; left: -39px; top: 4px;
    width: 16px; height: 16px; border-radius: 50%;
    background: var(--primary); border: 3px solid #fff; box-shadow: 0 0 0 3px var(--primary-light);
}
.timeline-year {
    display: inline-block; font-size: .75rem; font-weight: 700;
    color: var(--primary); background: var(--primary-light);
    padding: 4px 12px; border-radius: 50px; margin-bottom: 8px;
}
.timeline-item h3 { font-size: 1.1rem; font-weight: 700; margin-bottom: 6px; color: var(--text-dark); }
.timeline-item p { font-size: .9rem; color: var(--text-mid); line-height: 1.7; }

.cta-about {
    padding: 72px 0 96px;
    background: var(--bg-light);
}
.cta-about-box {
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
    border-radius: var(--radius-lg); padding: 48px 40px; text-align: center;
    position: relative; overflow: hidden;
}
.cta-about-box::before {
    content: ''; position: absolute; top: -40%; right: -10%;
    width: 300px; height: 300px; border-radius: 50%; background: rgba(0,102,204,.2);
}
.cta-about-box h2 { color: #fff; font-size: 1.75rem; font-weight: 800; margin-bottom: 12px; position: relative; z-index: 1; }
.cta-about-box p { color: rgba(255,255,255,.75); margin-bottom: 24px; position: relative; z-index: 1; }
.cta-about-box .btn-row { display: flex; gap: 14px; justify-content: center; flex-wrap: wrap; position: relative; z-index: 1; }

@media (max-width: 1024px) {
    .story-grid { grid-template-columns: 1fr; }
    .story-visual { min-height: 300px; }
    .values-grid { grid-template-columns: repeat(2, 1fr); }
    .stats-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 480px) {
    .values-grid { grid-template-columns: 1fr; }
    .stats-grid { grid-template-columns: 1fr; }
}
</style>
@endsection

@section('content')

<section class="page-hero">
    <div class="page-hero-glow"></div>
    <div class="container page-hero-inner">
        <div class="eyebrow"><i class="fas fa-compass"></i> Về chúng tôi</div>
        <h1>Đồng hành cùng bạn <span>khám phá Việt Nam</span></h1>
        <p>VietTour ra đời từ niềm đam mê du lịch và mong muốn mang đến những hành trình trọn vẹn — an toàn, chân thực và đáng nhớ cho mọi người.</p>
    </div>
</section>

<section class="story-section">
    <div class="container">
        <div class="story-grid">
            <div class="story-visual">
                <div class="story-badge"><i class="fas fa-heart"></i> Tận tâm từng chuyến đi</div>
            </div>
            <div class="story-content">
                <span class="badge badge-blue" style="margin-bottom:12px;display:inline-block">Câu chuyện</span>
                <h2>Sứ mệnh của VietTour</h2>
                <p>Chúng tôi tin rằng mỗi chuyến đi không chỉ là điểm đến, mà là khoảnh khắc kết nối con người với thiên nhiên, văn hóa và cộng đồng địa phương.</p>
                <p>VietTour kết nối khách hàng với các tour được tuyển chọn kỹ, hướng dẫn viên chuyên nghiệp và quy trình đặt chỗ minh bạch — giúp bạn yên tâm từ lúc lên kế hoạch đến khi trở về.</p>
                <p style="margin-bottom:0">Đội ngũ của chúng tôi luôn lắng nghe phản hồi để không ngừng cải thiện trải nghiệm, xứng đáng với niềm tin bạn đặt vào chúng tôi.</p>
            </div>
        </div>
    </div>
</section>

<section class="values-section">
    <div class="container">
        <div class="values-header">
            <span class="badge badge-orange" style="margin-bottom:10px;display:inline-block">Giá trị cốt lõi</span>
            <h2 class="section-title">Điều chúng tôi cam kết</h2>
            <p class="section-sub" style="margin-bottom:0">Bốn trụ cột định hình mọi dịch vụ tại VietTour</p>
        </div>
        <div class="values-grid">
            <div class="value-card">
                <div class="value-icon"><i class="fas fa-handshake"></i></div>
                <h3>Uy tín & minh bạch</h3>
                <p>Thông tin tour, giá và điều kiện được công bố rõ ràng. Không phụ phí ẩn, không hứa suông.</p>
            </div>
            <div class="value-card">
                <div class="value-icon"><i class="fas fa-leaf"></i></div>
                <h3>Bền vững & địa phương</h3>
                <p>Ưu tiên hợp tác cùng cộng đồng địa phương và gợi ý lịch trình thân thiện môi trường.</p>
            </div>
            <div class="value-card">
                <div class="value-icon"><i class="fas fa-shield-alt"></i></div>
                <h3>An toàn trên mọi hành trình</h3>
                <p>Đối tác được kiểm duyệt, HDV được đào tạo và quy trình hỗ trợ khi có sự cố.</p>
            </div>
            <div class="value-card">
                <div class="value-icon"><i class="fas fa-smile-beam"></i></div>
                <h3>Trải nghiệm trọn vẹn</h3>
                <p>Từ tư vấn ban đầu đến sau tour — chúng tôi đồng hành để bạn chỉ việc tận hưởng.</p>
            </div>
        </div>
    </div>
</section>

<section class="stats-strip">
    <div class="container">
        <div class="stats-grid">
            <div>
                <div class="stat-item-num">{{ $stats['tours'] }}+</div>
                <div class="stat-item-label">Tour đang mở đặt</div>
            </div>
            <div>
                <div class="stat-item-num">{{ $stats['customers'] }}+</div>
                <div class="stat-item-label">Khách hàng đồng hành</div>
            </div>
            <div>
                <div class="stat-item-num">{{ $stats['years'] }}<small> năm</small></div>
                <div class="stat-item-label">Kinh nghiệm vận hành</div>
            </div>
            <div>
                <div class="stat-item-num">24<small>/7</small></div>
                <div class="stat-item-label">Hỗ trợ &amp; tư vấn</div>
            </div>
        </div>
    </div>
</section>

<section class="timeline-section">
    <div class="container">
        <div class="timeline-header">
            <span class="badge badge-green" style="margin-bottom:10px;display:inline-block">Hành trình</span>
            <h2 class="section-title">Một vài cột mốc</h2>
            <p class="section-sub" style="margin-bottom:0">Phát triển không ngừng cùng khách hàng</p>
        </div>
        <div class="timeline">
            <div class="timeline-item">
                <span class="timeline-year">Khởi đầu</span>
                <h3>Thành lập &amp; định hướng</h3>
                <p>VietTour bắt đầu từ nhóm bạn đam mê du lịch, tập trung các tour nội địa chất lượng và dịch vụ tận tâm.</p>
            </div>
            <div class="timeline-item">
                <span class="timeline-year">Mở rộng</span>
                <h3>Đa dạng hóa sản phẩm</h3>
                <p>Bổ sung tour theo vùng miền, trải nghiệm văn hóa và các gói phù hợp gia đình, nhóm bạn.</p>
            </div>
            <div class="timeline-item">
                <span class="timeline-year">Hiện tại</span>
                <h3>Nền tảng đặt tour trực tuyến</h3>
                <p>Website giúp bạn tra cứu, so sánh và đặt chỗ nhanh chóng, kết nối trực tiếp với lịch khởi hành.</p>
            </div>
        </div>
    </div>
</section>

<section class="cta-about">
    <div class="container">
        <div class="cta-about-box">
            <h2>Sẵn sàng viết câu chuyện của riêng bạn?</h2>
            <p>Hàng trăm hành trình đang chờ — khám phá tour phù hợp hoặc liên hệ để được tư vấn.</p>
            <div class="btn-row">
                <a href="{{ route('tours.index') }}" class="btn btn-accent"><i class="fas fa-map-marked-alt"></i> Xem danh sách tour</a>
                <a href="{{ route('contact') }}" class="btn" style="background:rgba(255,255,255,.12);color:#fff;border:1.5px solid rgba(255,255,255,.35);"><i class="fas fa-envelope"></i> Liên hệ tư vấn</a>
            </div>
        </div>
    </div>
</section>

@endsection
