@extends('layouts.admin')
@section('styles')
<style>
/* ── Sticky Save button ── */
.sticky-save-btn {
    position: fixed;
    bottom: 28px;
    right: 28px;
    z-index: 9999;
    box-shadow: 0 4px 16px rgba(0,0,0,.25);
    border-radius: 50px;
    padding: 10px 24px;
    font-size: 15px;
    font-weight: 700;
}

/* ── Sticky section nav (đính lên khi cuộn qua header) ── */
html, body, .wrapper, .card-body, .content-wrapper, .content {
    overflow-x: clip !important;
}
.event-section-nav {
    position: sticky;
    top: 56px;
    z-index: 1031;
    background: #fff;
    border-bottom: 2px solid #e9ecef;
    padding: 12px 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,.06);
    display: flex;
    align-items: center;
    gap: 10px;
    margin: 0 -20px 20px -20px;
}
.event-section-nav .nav-sections {
    flex: 1 1 auto;
    overflow-x: auto;
    white-space: nowrap;
}
.event-section-nav .nav-save-btn {
    flex: 0 0 auto;
}
.event-section-nav a {
    display: inline-block;
    padding: 6px 14px;
    margin-right: 6px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
    color: #495057;
    background: #f1f3f5;
    text-decoration: none;
    transition: all .2s;
}
.event-section-nav a:hover,
.event-section-nav a.active {
    background: #007bff;
    color: #fff;
}
.event-section-nav a .badge {
    font-size: 11px;
    margin-left: 4px;
    vertical-align: middle;
}

/* ── Section cards ── */
.event-section-card {
    border: 1px solid #dee2e6;
    border-radius: 8px;
    margin-bottom: 20px;
    overflow: hidden;
}
.event-section-card .section-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    padding: 14px 20px;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    user-select: none;
    border-bottom: 1px solid #dee2e6;
    transition: background .2s;
}
.event-section-card .section-header:hover {
    background: linear-gradient(135deg, #e9ecef 0%, #dee2e6 100%);
}
.event-section-card .section-header h5 {
    margin: 0;
    font-size: 15px;
    font-weight: 700;
    color: #212529;
}
.event-section-card .section-header .section-meta {
    display: flex;
    align-items: center;
    gap: 10px;
}
.event-section-card .section-header .badge {
    font-size: 12px;
    padding: 4px 10px;
}
.event-section-card .section-header .collapse-icon {
    transition: transform .3s;
    font-size: 14px;
    color: #6c757d;
}
.event-section-card .section-header.collapsed .collapse-icon {
    transform: rotate(-90deg);
}
.event-section-card .section-body {
    padding: 20px;
}

/* ── Smooth scroll offset for sticky nav ── */
.scroll-anchor {
    scroll-margin-top: 120px;
}
</style>
@endsection

@section('content')

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>{{ trans('global.edit') }} {{ trans('cruds.event.title_singular') }}: <strong>{{ $event->name }}</strong></span>
        <div>
            @if($event->slug)
                <a href="{{ url('/') }}/event/{{ $event->slug }}" target="_blank" class="btn btn-sm btn-outline-info"><i class="fa fa-external-link"></i> Xem trang sự kiện</a>
            @endif
        </div>
    </div>

    <div class="card-body" style="padding-top: 0;">

        {{-- ── STICKY NAVIGATION BAR ── --}}
        <nav class="event-section-nav" id="sectionNav">
            <div class="nav-sections">
                <a href="#sec-basic" class="active"><i class="fa fa-info-circle"></i> Thông tin</a>
                <a href="#sec-speakers"><i class="fa fa-users"></i> Diễn giả <span class="badge badge-secondary">{{ $event->speakers->count() }}</span></a>
                <a href="#sec-schedules"><i class="fa fa-clock-o"></i> Lịch trình <span class="badge badge-secondary">{{ $event->schedules->count() }}</span></a>
                <a href="#sec-benefits"><i class="fa fa-star"></i> Lợi ích <span class="badge badge-secondary">{{ $event->keyBenefits->count() }}</span></a>
                <a href="#sec-venues"><i class="fa fa-map-marker"></i> Địa điểm <span class="badge badge-secondary">{{ $event->venues->count() }}</span></a>
                <a href="#sec-hotels"><i class="fa fa-bed"></i> Khách sạn <span class="badge badge-secondary">{{ $event->hotels->count() }}</span></a>
                <a href="#sec-galleries"><i class="fa fa-picture-o"></i> Thư viện <span class="badge badge-secondary">{{ $event->galleries->count() }}</span></a>
                <a href="#sec-sponsors"><i class="fa fa-handshake-o"></i> Nhà tài trợ <span class="badge badge-secondary">{{ $event->sponsors->count() }}</span></a>
                <a href="#sec-faqs"><i class="fa fa-question-circle"></i> FAQ <span class="badge badge-secondary">{{ $event->faqs->count() }}</span></a>
                <a href="#sec-amenities"><i class="fa fa-check-square"></i> Tiện ích <span class="badge badge-secondary">{{ $event->amenities->count() }}</span></a>
                <a href="#sec-prices"><i class="fa fa-ticket"></i> Giá vé <span class="badge badge-secondary">{{ $event->prices->count() }}</span></a>
            </div>
            <button type="button" class="btn btn-danger btn-sm nav-save-btn" id="navSaveBtn" title="Lưu tất cả thông tin"><i class="fa fa-save"></i> Lưu</button>
        </nav>

        {{-- ═══════════════ SECTION: THÔNG TIN SỰ KIỆN ═══════════════ --}}
        <div id="sec-basic" class="scroll-anchor event-section-card">
            <div class="section-header" data-toggle="collapse" data-target="#body-basic">
                <h5><i class="fa fa-info-circle text-primary"></i> Thông tin sự kiện</h5>
                <div class="section-meta">
                    <span class="collapse-icon"><i class="fa fa-chevron-down"></i></span>
                </div>
            </div>
            <div class="collapse show section-body" id="body-basic">
                <form action="{{ route("admin.events.update", [$event->id]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    @include('admin.partials.multilang_hint', ['mlFields' => ['name', 'description', 'about_description', 'about_where', 'about_when'], 'isCreate' => false])
                    @include('admin.events._form_fields')
                    <div class="mt-3">
                        <button class="btn btn-danger" type="submit"><i class="fa fa-save"></i> {{ trans('global.save') }}</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ═══════════════ SECTION: DIỄN GIẢ ═══════════════ --}}
        <div id="sec-speakers" class="scroll-anchor event-section-card">
            <div class="section-header" data-toggle="collapse" data-target="#body-speakers">
                <h5><i class="fa fa-users text-info"></i> Diễn giả</h5>
                <div class="section-meta">
                    <span class="badge badge-info">{{ $event->speakers->count() }}</span>
                    <span class="collapse-icon"><i class="fa fa-chevron-down"></i></span>
                </div>
            </div>
            <div class="collapse show section-body" id="body-speakers">
                @include('admin.events._child', [
                    'module' => 'speakers',
                    'title'  => 'Diễn giả',
                    'items'  => $event->speakers,
                    'columns' => ['name' => 'Tên', 'role' => 'Chức danh', 'company' => 'Công ty', 'photo' => 'Ảnh'],
                ])
            </div>
        </div>

        {{-- ═══════════════ SECTION: LỊCH TRÌNH ═══════════════ --}}
        <div id="sec-schedules" class="scroll-anchor event-section-card">
            <div class="section-header" data-toggle="collapse" data-target="#body-schedules">
                <h5><i class="fa fa-clock-o text-warning"></i> Lịch trình</h5>
                <div class="section-meta">
                    <span class="badge badge-warning">{{ $event->schedules->count() }}</span>
                    <span class="collapse-icon"><i class="fa fa-chevron-down"></i></span>
                </div>
            </div>
            <div class="collapse show section-body" id="body-schedules">
                @include('admin.events._child', [
                    'module' => 'schedules',
                    'title'  => 'Lịch trình',
                    'items'  => $event->schedules,
                    'columns' => ['day_number' => 'Ngày', 'start_time' => 'Giờ', 'title' => 'Tiêu đề', 'speaker_name' => 'Diễn giả'],
                ])
            </div>
        </div>

        {{-- ═══════════════ SECTION: LỢI ÍCH ═══════════════ --}}
        <div id="sec-benefits" class="scroll-anchor event-section-card">
            <div class="section-header" data-toggle="collapse" data-target="#body-benefits">
                <h5><i class="fa fa-star text-success"></i> Lợi ích chính</h5>
                <div class="section-meta">
                    <span class="badge badge-success">{{ $event->keyBenefits->count() }}</span>
                    <span class="collapse-icon"><i class="fa fa-chevron-down"></i></span>
                </div>
            </div>
            <div class="collapse show section-body" id="body-benefits">
                @include('admin.events._child', [
                    'module' => 'key-benefits',
                    'title'  => 'Lợi ích chính',
                    'items'  => $event->keyBenefits,
                    'columns' => ['sort_order' => 'Thứ tự', 'icon' => 'Icon', 'title' => 'Tiêu đề'],
                ])
            </div>
        </div>

        {{-- ═══════════════ SECTION: ĐỊA ĐIỂM ═══════════════ --}}
        <div id="sec-venues" class="scroll-anchor event-section-card">
            <div class="section-header" data-toggle="collapse" data-target="#body-venues">
                <h5><i class="fa fa-map-marker text-danger"></i> Địa điểm</h5>
                <div class="section-meta">
                    <span class="badge badge-danger">{{ $event->venues->count() }}</span>
                    <span class="collapse-icon"><i class="fa fa-chevron-down"></i></span>
                </div>
            </div>
            <div class="collapse show section-body" id="body-venues">
                @include('admin.events._child', [
                    'module' => 'venues',
                    'title'  => 'Địa điểm',
                    'items'  => $event->venues,
                    'columns' => ['name' => 'Tên', 'address' => 'Địa chỉ'],
                ])
            </div>
        </div>

        {{-- ═══════════════ SECTION: KHÁCH SẠN ═══════════════ --}}
        <div id="sec-hotels" class="scroll-anchor event-section-card">
            <div class="section-header" data-toggle="collapse" data-target="#body-hotels">
                <h5><i class="fa fa-bed text-purple"></i> Khách sạn</h5>
                <div class="section-meta">
                    <span class="badge badge-secondary">{{ $event->hotels->count() }}</span>
                    <span class="collapse-icon"><i class="fa fa-chevron-down"></i></span>
                </div>
            </div>
            <div class="collapse show section-body" id="body-hotels">
                @include('admin.events._child', [
                    'module' => 'hotels',
                    'title'  => 'Khách sạn',
                    'items'  => $event->hotels,
                    'columns' => ['name' => 'Tên', 'rating' => 'Sao', 'address' => 'Địa chỉ'],
                ])
            </div>
        </div>

        {{-- ═══════════════ SECTION: THƯ VIỆN ═══════════════ --}}
        <div id="sec-galleries" class="scroll-anchor event-section-card">
            <div class="section-header" data-toggle="collapse" data-target="#body-galleries">
                <h5><i class="fa fa-picture-o text-info"></i> Thư viện ảnh</h5>
                <div class="section-meta">
                    <span class="badge badge-info">{{ $event->galleries->count() }}</span>
                    <span class="collapse-icon"><i class="fa fa-chevron-down"></i></span>
                </div>
            </div>
            <div class="collapse show section-body" id="body-galleries">
                @include('admin.events._child', [
                    'module' => 'galleries',
                    'title'  => 'Thư viện ảnh',
                    'items'  => $event->galleries,
                    'columns' => ['name' => 'Tên', 'photos_count' => 'Số ảnh'],
                ])
            </div>
        </div>

        {{-- ═══════════════ SECTION: NHÀ TÀI TRỢ ═══════════════ --}}
        <div id="sec-sponsors" class="scroll-anchor event-section-card">
            <div class="section-header" data-toggle="collapse" data-target="#body-sponsors">
                <h5><i class="fa fa-handshake-o text-warning"></i> Nhà tài trợ</h5>
                <div class="section-meta">
                    <span class="badge badge-warning">{{ $event->sponsors->count() }}</span>
                    <span class="collapse-icon"><i class="fa fa-chevron-down"></i></span>
                </div>
            </div>
            <div class="collapse show section-body" id="body-sponsors">
                @include('admin.events._child', [
                    'module' => 'sponsors',
                    'title'  => 'Nhà tài trợ',
                    'items'  => $event->sponsors,
                    'columns' => ['name' => 'Tên', 'link' => 'Link', 'logo' => 'Logo'],
                ])
            </div>
        </div>

        {{-- ═══════════════ SECTION: FAQ ═══════════════ --}}
        <div id="sec-faqs" class="scroll-anchor event-section-card">
            <div class="section-header" data-toggle="collapse" data-target="#body-faqs">
                <h5><i class="fa fa-question-circle text-success"></i> Câu hỏi thường gặp</h5>
                <div class="section-meta">
                    <span class="badge badge-success">{{ $event->faqs->count() }}</span>
                    <span class="collapse-icon"><i class="fa fa-chevron-down"></i></span>
                </div>
            </div>
            <div class="collapse show section-body" id="body-faqs">
                @include('admin.events._child', [
                    'module' => 'faqs',
                    'title'  => 'Câu hỏi thường gặp',
                    'items'  => $event->faqs,
                    'columns' => ['question' => 'Câu hỏi'],
                ])
            </div>
        </div>

        {{-- ═══════════════ SECTION: TIỆN ÍCH ═══════════════ --}}
        <div id="sec-amenities" class="scroll-anchor event-section-card">
            <div class="section-header" data-toggle="collapse" data-target="#body-amenities">
                <h5><i class="fa fa-check-square text-primary"></i> Tiện ích</h5>
                <div class="section-meta">
                    <span class="badge badge-primary">{{ $event->amenities->count() }}</span>
                    <span class="collapse-icon"><i class="fa fa-chevron-down"></i></span>
                </div>
            </div>
            <div class="collapse show section-body" id="body-amenities">
                @include('admin.events._child', [
                    'module' => 'amenities',
                    'title'  => 'Tiện ích',
                    'items'  => $event->amenities,
                    'columns' => ['name' => 'Tên'],
                ])
            </div>
        </div>

        {{-- ═══════════════ SECTION: GIÁ VÉ ═══════════════ --}}
        <div id="sec-prices" class="scroll-anchor event-section-card">
            <div class="section-header" data-toggle="collapse" data-target="#body-prices">
                <h5><i class="fa fa-ticket text-danger"></i> Giá vé</h5>
                <div class="section-meta">
                    <span class="badge badge-danger">{{ $event->prices->count() }}</span>
                    <span class="collapse-icon"><i class="fa fa-chevron-down"></i></span>
                </div>
            </div>
            <div class="collapse show section-body" id="body-prices">
                @include('admin.events._child', [
                    'module' => 'prices',
                    'title'  => 'Giá vé',
                    'items'  => $event->prices,
                    'columns' => ['name' => 'Tên gói', 'price' => 'Giá', 'amenities' => 'Tiện ích'],
                    'amenityList' => $event->amenities,
                    'speakerList' => $event->speakers,
                ])
            </div>
        </div>

    </div>{{-- card-body --}}
</div>{{-- card --}}

{{-- ── Nút Save sticky luôn hiển thị ── --}}
<button type="button" class="btn btn-danger sticky-save-btn" id="stickySaveBtn" title="Lưu thông tin sự kiện">
    <i class="fa fa-save"></i> Lưu thông tin
</button>

@endsection

@section('scripts')
@parent
<script>
// ── Sticky Save buttons → submit form thông tin sự kiện ──
function submitEventForm() {
    var form = document.querySelector('#body-basic form');
    if (form) {
        // Đảm bảo section đang mở
        var body = document.getElementById('body-basic');
        if (body && !body.classList.contains('show')) {
            body.classList.add('show');
        }
        // requestSubmit() preserves the browser's required-field validation.
        if (form.requestSubmit) {
            form.requestSubmit();
        } else {
            form.submit();
        }
    }
}
document.getElementById('stickySaveBtn').addEventListener('click', submitEventForm);
var navSaveBtn = document.getElementById('navSaveBtn');
if (navSaveBtn) {
    navSaveBtn.addEventListener('click', submitEventForm);
}
</script>
<script>
$(function () {
  $('.datepicker').datetimepicker({
    format: 'YYYY-MM-DD',
    icons: {
      up: 'fa fa-chevron-up',
      down: 'fa fa-chevron-down',
      previous: 'fa fa-chevron-left',
      next: 'fa fa-chevron-right'
    }
  });
  $('.datetimepicker').datetimepicker({
    format: 'YYYY-MM-DD HH:mm:ss',
    icons: {
      up: 'fa fa-chevron-up',
      down: 'fa fa-chevron-down',
      previous: 'fa fa-chevron-left',
      next: 'fa fa-chevron-right'
    }
  });
});

</script>

{{-- ── Sticky nav: highlight active section on scroll ── --}}
<script>
$(function () {
    var $nav = $('#sectionNav');
    var $links = $nav.find('a');
    var sections = [];

    $links.each(function () {
        var id = $(this).attr('href');
        if (id && id.charAt(0) === '#') {
            var $sec = $(id);
            if ($sec.length) sections.push({ link: $(this), el: $sec });
        }
    });

    // Click handler: smooth scroll
    $links.on('click', function (e) {
        e.preventDefault();
        var target = $($(this).attr('href'));
        if (target.length) {
            $('html, body').animate({ scrollTop: target.offset().top - 116 }, 400);
        }
    });

    // Scroll spy
    $(window).on('scroll', function () {
        var scrollTop = $(window).scrollTop() + 90;
        var current = null;
        for (var i = 0; i < sections.length; i++) {
            if (sections[i].el.offset().top <= scrollTop) {
                current = sections[i].link;
            }
        }
        if (current) {
            $links.removeClass('active');
            current.addClass('active');
        }
    });

    // Collapse icon rotation
    $('.section-header').on('click', function () {
        $(this).toggleClass('collapsed');
    });
});
</script>

{{-- ── Nút Save sticky — giữ lại ── --}}

@endsection
