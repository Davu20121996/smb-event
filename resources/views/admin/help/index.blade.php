@extends('layouts.admin')

@section('styles')
@parent
@verbatim
<style>
  .g-guide{--bg:#f2f5f8;--card:#fff;--ink:#1c2733;--ink-2:#4a5a6a;--ink-3:#8595a5;
    --line:#e3e9f0;--primary:#0a6e55;--primary-2:#0d8a6b;--accent:#e8590c;
    --soft:#eef7f3;--danger:#c92a2a;--shadow:0 6px 22px rgba(16,42,67,.08);
    --radius:14px;max-width:1180px;margin:0 auto;padding:2px 2px 30px;
    font-family:"Segoe UI",system-ui,Roboto,"Source Sans Pro",sans-serif;color:var(--ink);line-height:1.55}
  .g-guide *{box-sizing:border-box}
  .g-toolbar{background:linear-gradient(120deg,#0a3d31,#0a6e55 55%,#0d8a6b);color:#fff;
    border-radius:var(--radius);box-shadow:var(--shadow);padding:16px 20px 14px;margin-bottom:16px}
  .g-toolbar h1{font-size:20px;letter-spacing:.2px;margin:0;font-weight:700;display:flex;gap:10px;align-items:center;flex-wrap:wrap}
  .g-toolbar h1 .g-badge{background:rgba(255,255,255,.16);font-size:11px;font-weight:600;padding:3px 10px;border-radius:99px;letter-spacing:.4px}
  .g-toolbar .g-sub{font-size:12.5px;color:#cfe8df;margin-top:2px}
  .g-controls{display:flex;gap:10px;align-items:center;flex-wrap:wrap;margin-top:12px}
  .g-searchbox{flex:1 1 260px;position:relative;min-width:220px}
  .g-searchbox input{width:100%;padding:9px 14px 9px 38px;border:none;border-radius:10px;font-size:14px;background:#fff;color:var(--ink);outline:none;box-shadow:0 2px 8px rgba(0,0,0,.14)}
  .g-searchbox .g-mag{position:absolute;left:13px;top:50%;transform:translateY(-50%);font-size:15px;opacity:.55;pointer-events:none}
  .g-zoom{display:flex;align-items:center;gap:6px;background:rgba(255,255,255,.14);border-radius:10px;padding:4px}
  .g-zoom button{width:32px;height:32px;border:none;border-radius:8px;background:#fff;color:var(--ink);font-size:15px;font-weight:700;cursor:pointer;line-height:1}
  .g-zoom button:hover{background:#e3f2ec}
  .g-zoom .g-zval{min-width:50px;text-align:center;font-size:12.5px;font-weight:700;color:#fff}
  .g-zoom input[type=range]{width:104px;accent-color:#fff}
  .g-count{font-size:12px;background:rgba(255,255,255,.16);padding:5px 12px;border-radius:99px}
  .g-chips{display:flex;gap:8px;flex-wrap:wrap;margin-top:12px}
  .g-chips button{border:1px solid rgba(255,255,255,.35);background:transparent;color:#eaf5f0;font-size:12.5px;padding:5px 12px;border-radius:99px;cursor:pointer;transition:.15s}
  .g-chips button.active,.g-chips button:hover{background:#fff;color:var(--primary);border-color:#fff;font-weight:600}
  .g-section-head{display:flex;align-items:center;gap:10px;margin:22px 0 12px;font-size:16px;font-weight:700;color:var(--primary)}
  .g-section-head::before{content:"";width:6px;height:20px;border-radius:3px;background:var(--primary)}
  .g-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:16px}
  @media(max-width:1024px){.g-grid{grid-template-columns:repeat(2,1fr)}}
  @media(max-width:640px){.g-grid{grid-template-columns:1fr}}
  .g-card{background:var(--card);border:1px solid var(--line);border-radius:var(--radius);box-shadow:var(--shadow);
    padding:18px 18px 16px;cursor:pointer;transition:.18s;display:flex;flex-direction:column;gap:10px}
  .g-card:hover{transform:translateY(-3px);border-color:var(--primary-2);box-shadow:0 10px 26px rgba(16,42,67,.14)}
  .g-card.g-hidden{display:none}
  .g-card .g-ic{width:46px;height:46px;border-radius:12px;background:var(--soft);display:flex;align-items:center;justify-content:center;font-size:22px}
  .g-card .g-c-title{font-size:15.5px;font-weight:700;color:var(--ink);line-height:1.3}
  .g-card .g-c-summary{font-size:12.8px;color:var(--ink-2);flex:1}
  .g-card .g-meta{display:flex;gap:6px;flex-wrap:wrap;align-items:center}
  .g-tag{font-size:10.5px;font-weight:600;padding:2px 9px;border-radius:99px;background:#eef2f7;color:var(--ink-3);letter-spacing:.3px}
  .g-tag.g{background:var(--soft);color:var(--primary)}
  .g-tag.o{background:#fff1e6;color:var(--accent)}
  .g-tag.r{background:#ffe9e9;color:var(--danger)}
  .g-card .g-open-hint{font-size:11.5px;color:var(--primary);font-weight:700;margin-top:4px}
  .g-empty{display:none;text-align:center;color:var(--ink-3);padding:50px 10px;font-size:14px}
  .g-empty.g-show{display:block}
  .g-overlay{position:fixed;inset:0;background:rgba(15,30,45,.55);z-index:10000;display:none;align-items:flex-start;justify-content:center;padding:30px 16px;overflow:auto}
  .g-overlay.g-open{display:flex}
  .g-modal{background:#fff;border-radius:16px;max-width:860px;width:100%;box-shadow:0 20px 60px rgba(0,0,0,.35);animation:gpop .18s ease}
  @keyframes gpop{from{transform:scale(.97);opacity:0}to{transform:scale(1);opacity:1}}
  .g-modal-head{position:sticky;top:0;border-radius:16px 16px 0 0;background:#fff;z-index:2;border-bottom:1px solid var(--line);padding:18px 24px;display:flex;gap:14px;align-items:flex-start}
  .g-modal-head .g-ic{width:48px;height:48px;border-radius:12px;background:var(--soft);display:flex;align-items:center;justify-content:center;font-size:24px;flex-shrink:0}
  .g-modal-head h2{font-size:19px;line-height:1.25;color:var(--ink)}
  .g-modal-head .g-sec{font-size:12px;color:var(--ink-3);font-weight:600;letter-spacing:.4px;text-transform:uppercase}
  .g-modal-close{margin-left:auto;flex-shrink:0;width:34px;height:34px;border:none;border-radius:9px;background:#f0f3f6;font-size:16px;cursor:pointer;color:var(--ink-2);line-height:1}
  .g-modal-close:hover{background:#ffe9e9;color:var(--danger)}
  .g-modal-body{padding:6px 24px 26px;font-size:14px;color:var(--ink-2)}
  .g-modal-body .g-warn{background:#fff7ec;border:1px solid #f4d9a8;border-left:4px solid var(--accent);color:#7a4a00;border-radius:10px;padding:12px 16px;font-size:13px;margin:16px 0}
  .g-modal-body .g-tip{background:var(--soft);border:1px solid #cfe7dc;border-left:4px solid var(--primary);color:#0a4a3a;border-radius:10px;padding:12px 16px;font-size:13px;margin:16px 0}
  .g-grp{margin-top:18px}
  .g-grp h3{font-size:13px;font-weight:800;color:var(--ink);letter-spacing:.2px;margin-bottom:8px;display:flex;align-items:center;gap:8px}
  .g-grp h3 .g-step-badge{background:var(--primary);color:#fff;font-size:10px;font-weight:700;width:20px;height:20px;border-radius:6px;display:inline-flex;align-items:center;justify-content:center;flex-shrink:0}
  .g-grp ol{list-style:none;counter-reset:li;margin:0;padding:0}
  .g-grp li{position:relative;padding:7px 0 7px 34px;counter-increment:li;border-left:2px solid var(--line);margin-left:9px}
  .g-grp li::before{content:counter(li);position:absolute;left:-13px;top:8px;width:24px;height:24px;border-radius:50%;background:#eef2f7;color:var(--primary);font-size:11px;font-weight:700;display:flex;align-items:center;justify-content:center;border:2px solid #fff;box-shadow:0 0 0 1px var(--line)}
  .g-grp li b{color:var(--ink)}
  .g-grp li code{background:#eef2f7;border:1px solid var(--line);border-radius:5px;padding:1px 6px;font-size:12px;color:#0a4a3a}
  .g-modal-foot{border-top:1px solid var(--line);padding:14px 24px;display:flex;gap:10px;justify-content:flex-end;flex-wrap:wrap}
</style>
@endverbatim
@endsection

@section('content')
@verbatim
<div class="g-guide" id="guide">
  <div class="g-toolbar">
    <h1>📘 Hướng dẫn sử dụng hệ thống <span class="g-badge">Quản trị</span></h1>
    <div class="g-sub">Toàn bộ thao tác quản trị website sự kiện: nội dung, đăng ký, liên hệ &amp; tài khoản</div>
    <div class="g-controls">
      <div class="g-searchbox">
        <span class="g-mag">🔍</span>
        <input type="text" id="g-q" placeholder="Tìm nhanh chức năng… (vd: thêm diễn giả, đổi sự kiện, xóa ảnh)">
      </div>
      <div class="g-zoom" title="Thu phóng nội dung">
        <button id="g-zminus" aria-label="Thu nhỏ">−</button>
        <span class="g-zval" id="g-zval">100%</span>
        <button id="g-zplus" aria-label="Phóng to">＋</button>
        <input type="range" id="g-zrange" min="70" max="160" value="100" step="5">
      </div>
      <span class="g-count" id="g-count"></span>
    </div>
    <nav class="g-chips" id="g-chips"></nav>
  </div>
  <div id="g-content"></div>
  <div class="g-empty" id="g-empty">Không tìm thấy chức năng nào khớp với từ khóa.<br>Hãy thử gõ từ khóa khác (ví dụ: &quot;diễn giả&quot;, &quot;xóa&quot;, &quot;ảnh&quot;).</div>
</div>
<div class="g-overlay" id="g-overlay"><div class="g-modal" id="g-modal"></div></div>
@endverbatim
@endsection

@section('scripts')
@parent
@verbatim
<script>
/* ===== DỮ LIỆU HƯỚNG DẪN ===== */
const GUIDE_DATA = [

  {section:"Bắt đầu", icon:"🔐", title:"Đăng nhập hệ thống",
    summary:"Cách vào trang quản trị bằng tài khoản được cấp.",
    tags:["admin","login"],
    groups:[{h:"Bước thực hiện", items:[
      "Mở trình duyệt (Chrome / Edge / Cốc Cốc), gõ địa chỉ website quản trị, ví dụ <code>https://tenmien.vn/admin</code>",
      "Trang <b>Đăng nhập</b> hiện ra. Nhập <b>Email</b> và <b>Mật khẩu</b> do quản trị viên cấp.",
      "Nhấn nút <b>Đăng nhập</b>.",
      "Thành công sẽ vào trang <b>Tổng quan hệ thống</b> (Dashboard)."
    ]}],
    warn:"Nếu nhập sai 5 lần trong 1 phút, hệ thống tạm khóa tài khoản 1 phút để chống dò mật khẩu — hãy chờ rồi thử lại. Giữ mật khẩu bí mật.",
    tip:"Nếu quên mật khẩu, dùng chức năng <b>Quên mật khẩu</b> ở trang đăng nhập (xem thẻ riêng)."},

  {section:"Bắt đầu", icon:"🚪", title:"Đăng xuất",
    summary:"Thoát tài khoản an toàn khi dùng xong.",
    tags:["admin","logout","thoát"],
    groups:[
      {h:"Bước thực hiện", items:[
        "Ở menu bên trái, cuộn xuống dưới cùng.",
        "Bấm mục <b>Đăng xuất</b> (Logout).",
        "Hệ thống đưa bạn về trang đăng nhập."
      ]}],
    tip:"Nếu dùng máy tính chung hoặc nơi công cộng, luôn đăng xuất và đóng trình duyệt sau khi xong."},

  {section:"Bắt đầu", icon:"🔑", title:"Quên mật khẩu",
    summary:"Lấy lại mật khẩu qua email khi quên.",
    tags:["password","reset","email"],
    groups:[
      {h:"Bước thực hiện", items:[
        "Tại trang Đăng nhập, bấm liên kết <b>Quên mật khẩu</b>.",
        "Nhập <b>email</b> đã đăng ký, bấm <b>Gửi</b>.",
        "Mở hộp thư email, bấm liên kết trong thư để đặt lại mật khẩu.",
        "Nhập mật khẩu mới hai lần rồi <b>Lưu</b>, sau đó đăng nhập lại với mật khẩu mới."
      ]}],
    warn:"Thư đặt lại mật khẩu chỉ có hiệu lực trong thời gian ngắn. Nếu không thấy thư, kiểm tra cả hộp thư rác (Spam)."},

  {section:"Bắt đầu", icon:"🌐", title:"Đổi ngôn ngữ (Tiếng Việt / English)",
    summary:"Chuyển giao diện website công khai giữa 2 ngôn ngữ.",
    tags:["language","ngôn ngữ","vi","en"],
    groups:[
      {h:"Bước thực hiện", items:[
        "Ở trang website công khai (trang chủ, trang sự kiện…), tìm nút chọn ngôn ngữ <b>VI / EN</b> trên thanh đầu trang.",
        "Bấm chọn ngôn ngữ mong muốn.",
        "Trang tải lại và hiển thị đúng ngôn ngữ đã chọn.",
        "Lựa chọn được ghi nhớ cho các lần truy cập sau."
      ]}],
    tip:"Hệ thống hỗ trợ 2 ngôn ngữ: Tiếng Việt (vi) và Tiếng Anh (en). Nội dung dạng nhiều ngôn ngữ (JSON) sẽ hiện đúng theo ngôn ngữ đang chọn."},

  {section:"Tổng quan & Điều hướng", icon:"📊", title:"Bảng điều khiển (Dashboard)",
    summary:"Trang đầu tiên sau khi đăng nhập, xem nhanh số liệu toàn hệ thống.",
    tags:["dashboard","tổng quan","thống kê","home"],
    groups:[
      {h:"Xem gì trên Dashboard", items:[
        "4 thẻ số liệu: <b>Lượt đăng ký sự kiện</b>, <b>Lead từ Landing Page</b>, <b>Liên hệ từ trang chủ</b>, <b>Event đang active</b>.",
        "<b>Thống kê Landing Page</b>: tổng trang, số trang publish/draft, tổng lead.",
        "<b>Thống kê Event</b>: tổng event, active, sắp diễn ra, đã kết thúc, tổng đăng ký.",
        "Bảng <b>Danh sách Event</b> và <b>Landing Page</b>: bấm tên để xem chi tiết.",
        "Cột <b>Đăng ký gần đây</b> và <b>Lead gần đây</b>: theo dõi hoạt động mới nhất."
      ]}],
    tip:"Các số liệu chỉ mang tính thống kê nhanh. Muốn quản lý chi tiết hãy vào từng module ở menu bên trái."},

  {section:"Tổng quan & Điều hướng", icon:"📅", title:"Chọn sự kiện đang quản lý (quan trọng)",
    summary:"Toàn bộ nội dung sự kiện (diễn giả, lịch trình, địa điểm…) đều theo sự kiện đang chọn.",
    tags:["event","sự kiện","chuyển","switch","chọn","active"],
    groups:[
      {h:"Cách 1 — Chọn ở thanh menu", items:[
        "Ở <b>đầu menu bên trái</b>, dưới Dashboard, có ô chọn <b>Sự kiện</b>.",
        "Bấm vào ô, chọn tên sự kiện muốn làm việc, hệ thống tự chuyển.",
        "Tên sự kiện đang chọn được đánh dấu là mục hiện hành."
      ]},
      {h:"Cách 2 — Bấm nút Select trong danh sách Sự kiện", items:[
        "Vào menu <b>Sự kiện</b>.",
        "Tại dòng sự kiện chưa active có nút <b>Select</b> — bấm vào.",
        "Sự kiện đó trở thành Active và được chọn làm mặc định."
      ]}],
    warn:"Quan trọng: Khi thêm diễn giả, lịch trình, địa điểm, khách sạn, nhà tài trợ, FAQ, tiện ích, bảng giá… hệ thống <b>tự gán vào sự kiện đang chọn</b>. Luôn kiểm tra đã chọn đúng sự kiện trước khi tạo nội dung."},

  {section:"Nội dung sự kiện", icon:"🗓️", title:"Sự kiện (Events) — Quản lý chung",
    summary:"Tạo, sửa, xóa, kích hoạt sự kiện; cài thông tin chung, giới thiệu, đếm ngược, SEO.",
    tags:["event","sự kiện","tạo","slug","countdown","seo"],
    groups:[
      {h:"Tạo sự kiện mới", items:[
        "Vào menu <b>Sự kiện</b>, bấm nút xanh <b>＋ Thêm Sự kiện</b>.",
        "Nhập <b>Tên</b> (bắt buộc).",
        "<b>Slug</b>: phần mở rộng đường dẫn (vd <code>su-kien-2026</code>). Để trống sẽ tự tạo từ tên.",
        "<b>Mô tả</b>: đoạn giới thiệu ngắn.",
        "<b>Ngày bắt đầu / Ngày kết thúc</b>: bấm chọn từ lịch.",
        "<b>Hoạt động (is_active)</b>: chọn Có để sự kiện xuất hiện trên website."
      ]},
      {h:"Mục Giới thiệu sự kiện", items:[
        "<b>Giới thiệu về sự kiện</b>: đoạn mô tả dài, cho phép định dạng.",
        "<b>Địa điểm</b> / <b>Thời gian</b>: hiển thị ở phần About."
      ]},
      {h:"Mục Đếm ngược (Countdown)", items:[
        "<b>Bật đếm ngược</b>: chọn Có/Không.",
        "<b>Hạn chót đăng ký</b>: chọn ngày giờ."
      ]},
      {h:"Mục SEO", items:[
        "<b>Meta title / Meta description</b>: dành cho công cụ tìm kiếm.",
        "<b>Favicon URL</b> / <b>Ảnh OG</b>: đường dẫn icon và ảnh chia sẻ mạng xã hội."
      ]},
      {h:"Mục Thank-you", items:[
        "<b>Bật lịch</b>, <b>Zalo URL</b>, <b>Fanpage URL</b>."
      ]},
      {h:"Lưu", items:["Bấm nút đỏ <b>Lưu</b> ở cuối form."]}
    ],
    warn:"URL Zalo/Fanpage chỉ chấp nhận địa chỉ http:// hoặc https:// an toàn; link không hợp lệ sẽ bị chặn.",
    tip:"Muốn xem thống kê của một sự kiện: vào Dashboard, bấm tên sự kiện trong bảng Danh sách Event."},

  {section:"Nội dung sự kiện", icon:"🎤", title:"Diễn giả (Speakers)",
    summary:"Thêm diễn giả cho sự kiện: tên, chức danh, công ty, mạng xã hội, tiểu sử, ảnh.",
    tags:["speaker","diễn giả","ảnh"],
    groups:[
      {h:"Thêm diễn giả mới", items:[
        "Chọn đúng <b>Sự kiện</b> ở menu trái (xem thẻ Chọn sự kiện).",
        "Vào menu <b>Diễn giả</b>, bấm <b>＋ Thêm Diễn giả</b>.",
        "Nhập <b>Tên</b> (bắt buộc).",
        "Nhập <b>Chức danh</b> (vd: CEO), <b>Công ty</b>.",
        "Nhập link <b>Twitter / Facebook / LinkedIn</b> nếu có.",
        "<b>Mô tả</b>: giới thiệu ngắn.<br><b>Mô tả đầy đủ</b>: phần viết dài cho trang chi tiết diễn giả."
      ]},
      {h:"Tải ảnh đại diện", items:[
        "Ở ô <b>Ảnh (photo)</b>, bấm vào khung hoặc kéo-thả ảnh vào.",
        "Chấp nhận <code>jpg / png / gif</code>, tối đa <b>2MB</b>, chỉ 1 ảnh.",
        "Đợi ảnh hiện trong khung rồi bấm <b>Lưu</b>."
      ]}],
    warn:"Ảnh phải tải lên xong rồi mới bấm Lưu, nếu không sẽ mất ảnh.",
    tips:"Diễn giả thuộc về sự kiện đang chọn. Muốn chia theo từng sự kiện, hãy đổi sự kiện trước khi thêm."},

  {section:"Nội dung sự kiện", icon:"🕐", title:"Lịch trình (Schedules)",
    summary:"Tạo khung chương trình theo ngày và giờ, gắn diễn giả cho từng tiết mục.",
    tags:["schedule","lịch trình","lịch","chương trình","giờ"],
    groups:[
      {h:"Thêm mục lịch trình", items:[
        "Chọn đúng Sự kiện ở menu trái.",
        "Vào menu <b>Lịch trình</b>, bấm <b>＋ Thêm Lịch trình</b>.",
        "<b>Tiêu đề</b> (bắt buộc): tên tiết mục.",
        "<b>Phụ đề</b>: dòng mô tả phụ.",
        "<b>Ngày</b> (day_number): số thứ tự ngày (1, 2, 3…).",
        "<b>Giờ bắt đầu</b>: định dạng <code>Giờ:Phút:Giây</code>, ví dụ <code>09:00:00</code>.",
        "<b>Diễn giả</b>: chọn từ danh sách diễn giả của sự kiện hiện tại.",
        "<b>Mô tả</b>: nội dung chi tiết.",
        "Bấm <b>Lưu</b>."
      ]}],
    warn:"Giờ bắt đầu bắt buộc có <b>đủ giây</b>: ví dụ <code>10:00:00</code>, không phải <code>10:00</code>. Nhập sai hệ thống báo lỗi và không lưu được."},

  {section:"Nội dung sự kiện", icon:"⭐", title:"Lợi ích chính (Key Benefits)",
    summary:"Các điểm nổi bật của sự kiện hiển thị trên trang sự kiện.",
    tags:["key benefit","lợi ích","benefit"],
    groups:[
      {h:"Thêm lợi ích", items:[
        "Chọn đúng Sự kiện ở menu trái.",
        "Vào menu <b>Lợi ích</b>, bấm <b>＋ Thêm Lợi ích</b>.",
        "<b>Icon</b>: tên biểu tượng, ví dụ <code>fa-star</code>.",
        "<b>Tiêu đề</b> (bắt buộc).",
        "<b>Mô tả</b>: nội dung (cho phép định dạng).",
        "<b>Thứ tự</b> (sort_order): số càng nhỏ hiện càng trước.",
        "Bấm <b>Lưu</b>."
      ]}],
    tip:"Để biết icon có sẵn, truy cập thư viện Font Awesome và dùng tên lớp như <code>fa-rocket</code>, <code>fa-gem</code>…"},

  {section:"Nội dung sự kiện", icon:"📍", title:"Địa điểm (Venues)",
    summary:"Địa điểm tổ chức kèm tọa độ GPS và bộ ảnh.",
    tags:["venue","địa điểm","tọa độ","lat","long","nhiều ảnh"],
    groups:[
      {h:"Thêm địa điểm", items:[
        "Chọn đúng Sự kiện ở menu trái.",
        "Vào menu <b>Địa điểm</b>, bấm <b>＋ Thêm Địa điểm</b>.",
        "<b>Tên</b>, <b>Địa chỉ</b>, <b>Mô tả</b> (cho phép định dạng).",
        "<b>Vĩ độ</b>: số từ -90 đến 90.<br><b>Kinh độ</b>: số từ -180 đến 180.",
        "<b>Ảnh (photos)</b>: tải <b>nhiều ảnh</b> cùng lúc.",
        "Bấm <b>Lưu</b>."
      ]}],
    warn:"Vĩ độ/Kinh độ ngoài phạm vi sẽ báo lỗi. Có thể lấy tọa độ bằng cách bấm chuột phải vào vị trí trên Google Maps."},

  {section:"Nội dung sự kiện", icon:"🏨", title:"Khách sạn (Hotels)",
    summary:"Khách sạn gợi ý cho khách tham dự, kèm đánh giá và ảnh.",
    tags:["hotel","khách sạn","rating"],
    groups:[
      {h:"Thêm khách sạn", items:[
        "Chọn đúng Sự kiện ở menu trái.",
        "Vào menu <b>Khách sạn</b>, bấm <b>＋ Thêm Khách sạn</b>.",
        "<b>Tên</b> (bắt buộc), <b>Đánh giá</b> (vd 4.5), <b>Địa chỉ</b>, <b>Mô tả</b>.",
        "<b>Ảnh (photo)</b>: tải 1 ảnh (jpg/png/gif, ≤2MB).",
        "Bấm <b>Lưu</b>."
      ]}],
    tips:"Rating nên là số (vd 5, 4.5) — hệ thống lưu dạng số."},

  {section:"Nội dung sự kiện", icon:"🖼️", title:"Thư viện ảnh (Galleries)",
    summary:"Bộ ảnh sự kiện, mỗi gallery chứa nhiều ảnh.",
    tags:["gallery","thư viện","ảnh","nhiều ảnh"],
    groups:[
      {h:"Tạo gallery mới", items:[
        "Chọn đúng Sự kiện ở menu trái.",
        "Vào menu <b>Thư viện ảnh</b>, bấm <b>＋ Thêm Thư viện ảnh</b>.",
        "Nhập <b>Tên</b> (bắt buộc).",
        "Ở ô <b>Ảnh</b>, kéo-thả hoặc chọn <b>nhiều ảnh</b> (jpg/png/gif, mỗi ảnh ≤2MB).",
        "Bấm <b>Lưu</b>."
      ]}],
    warn:"Ảnh đang hiện nhưng chưa Lưu thì chưa được gắn. Bấm nút × trên ảnh để bỏ trước khi Lưu."},

  {section:"Nội dung sự kiện", icon:"🤝", title:"Nhà tài trợ (Sponsors)",
    summary:"Danh sách nhà tài trợ kèm link website và logo.",
    tags:["sponsor","tài trợ","logo","link"],
    groups:[
      {h:"Thêm nhà tài trợ", items:[
        "Chọn đúng Sự kiện ở menu trái.",
        "Vào menu <b>Nhà tài trợ</b>, bấm <b>＋ Thêm Nhà tài trợ</b>.",
        "<b>Tên</b>, <b>Link</b> (phải bắt đầu bằng <code>http://</code> hoặc <code>https://</code>).",
        "<b>Logo</b>: tải 1 ảnh (jpg/png/gif, ≤2MB).",
        "Bấm <b>Lưu</b>."
      ]}],
    warn:"Link chỉ chấp nhận địa chỉ web công khai (http/https). Địa chỉ nội bộ, localhost hoặc dạng javascript: sẽ bị chặn."},

  {section:"Nội dung sự kiện", icon:"❓", title:"Câu hỏi thường gặp (FAQs)",
    tags:["faq","câu hỏi","hỏi đáp"],
    groups:[
      {h:"Thêm FAQ", items:[
        "Chọn đúng Sự kiện ở menu trái.",
        "Vào menu <b>FAQ</b>, bấm <b>＋ Thêm FAQ</b>.",
        "Nhập <b>Câu hỏi</b> và <b>Trả lời</b>.",
        "Bấm <b>Lưu</b>."
      ]}]
  },

  {section:"Nội dung sự kiện", icon:"✅", title:"Tiện ích (Amenities)",
    tags:["amenity","tiện ích"],
    groups:[
      {h:"Thêm tiện ích", items:[
        "Chọn đúng Sự kiện ở menu trái.",
        "Vào menu <b>Tiện ích</b>, bấm <b>＋ Thêm Tiện ích</b>.",
        "Nhập <b>Tên</b> tiện ích.",
        "Bấm <b>Lưu</b>."
      ]}],
    tips:"Tiện ích dùng lại ở mục Bảng giá (chọn nhiều tiện ích cho từng gói vé)."},

  {section:"Nội dung sự kiện", icon:"💲", title:"Bảng giá (Prices)",
    tags:["price","bảng giá","giá","gói vé","tiện ích"],
    groups:[
      {h:"Thêm gói giá", items:[
        "Chọn đúng Sự kiện ở menu trái.",
        "Vào menu <b>Bảng giá</b>, bấm <b>＋ Thêm Bảng giá</b>.",
        "Nhập <b>Tên</b> gói (vd: Vé Standard, Vé VIP).",
        "Nhập <b>Giá</b>.",
        "<b>Tiện ích</b>: bấm chọn một hoặc nhiều tiện ích.",
        "Bấm <b>Lưu</b>."
      ]}],
    tips:"Dùng nút <b>Chọn tất cả / Bỏ chọn tất cả</b> cạnh ô Tiện ích để thao tác nhanh."},

  {section:"Nội dung sự kiện", icon:"⚙️", title:"Cài đặt (Settings)",
    summary:"Các biến cấu hình chung và theo sự kiện (dạng khóa – giá trị).",
    tags:["setting","cài đặt","key","value"],
    groups:[
      {h:"Thêm biến cấu hình", items:[
        "Vào menu <b>Cài đặt</b>. Danh sách hiện cả cấu hình chung và của sự kiện đang chọn.",
        "Bấm <b>＋ Thêm Cài đặt</b>.",
        "<b>Khóa (key)</b>: tên biến, ví dụ <code>company_phone</code>.",
        "<b>Giá trị (value)</b>: nội dung.",
        "Bấm <b>Lưu</b>."
      ]}],
    warn:"Mục nâng cao. Chỉ đổi khi biết rõ ý nghĩa của khóa — nhập sai có thể làm lỗi hiển thị website."
  },

  {section:"Nội dung website", icon:"📰", title:"Tin tức (Posts)",
    summary:"Bài viết tin tức cho website, không theo sự kiện.",
    tags:["post","tin tức","bài viết","publish"],
    groups:[
      {h:"Viết bài mới", items:[
        "Vào menu <b>Tin tức</b>, bấm <b>＋ Thêm Tin tức</b>.",
        "<b>Tiêu đề</b> (bắt buộc).",
        "<b>Slug</b>: đường dẫn bài viết, để trống tự tạo.",
        "<b>Tag</b>: từ khóa phân loại (vd: su-kien, cong-nghe).",
        "<b>Tóm tắt</b>: đoạn ngắn hiển thị ở danh sách.",
        "<b>Nội dung</b>: soạn bài, hỗ trợ định dạng.",
        "<b>Xuất bản (is_published)</b>: chọn Có để hiện trên website.",
        "Tải <b>Ảnh bìa</b> và <b>Ảnh thumbnail</b> (mỗi ảnh 1 file, ≤2MB).",
        "Bấm <b>Lưu</b>."
      ]}],
    tips:"Chỉ bài viết có trạng thái <b>Xuất bản</b> mới hiện trên trang công khai; bài Nháp chỉ admin thấy."
  },

  {section:"Nội dung website", icon:"🧭", title:"Menu điều hướng",
    summary:"Quản lý menu trên thanh đầu trang của website.",
    tags:["menu","navigation","điều hướng"],
    groups:[
      {h:"Thêm mục menu", items:[
        "Vào menu <b>Menu</b>, bấm <b>＋ Thêm Menu</b>.",
        "<b>Nhãn (label)</b> (bắt buộc): chữ hiển thị, ví dụ Trang chủ.",
        "<b>URL</b>: địa chỉ liên kết.",
        "<b>Menu cha</b> (parent): chọn nếu đây là menu con.",
        "<b>Thứ tự</b>: số nhỏ hiện trước.",
        "<b>Hoạt động (is_active)</b>: đánh dấu để hiện trên website.",
        "Bấm <b>Lưu</b>."
      ]}],
    tips:"Có thể tạo menu 2 cấp: tạo mục cha trước, rồi thêm mục con và chọn nó làm Menu cha."},

  {section:"Nội dung website", icon:"📄", title:"Trang chia sẻ (Landing Pages)",
    summary:"Trang đăng ký nhận tài liệu/ebook riêng cho từng chiến dịch.",
    tags:["landing page","trang chia sẻ","tài liệu","lead","crm"],
    groups:[
      {h:"Thông tin chung", items:[
        "Vào menu <b>Trang chia sẻ</b>, bấm <b>＋ Thêm Trang chia sẻ</b>.",
        "<b>Tiêu đề</b> (bắt buộc), <b>Slug</b> (đường dẫn, để trống tự tạo).",
        "<b>Nội dung</b>: nội dung chính của trang.",
        "<b>Tiêu đề form / Tiêu đề nút</b>: chữ trên form đăng ký.",
        "<b>Tag CRM</b>: mã nhận diện chiến dịch.",
        "<b>Xuất bản</b>: chọn Có để trang truy cập công khai."
      ]},
      {h:"Tài liệu tải về (PDF)", items:[
        "<b>Bật tài liệu</b>: chọn Có để hiện chức năng tải.",
        "<b>Nguồn tài liệu</b>: URL (dán link) hoặc Upload (tải file lên).",
        "Nếu Upload: file <b>PDF</b>, tối đa <b>10MB</b>.",
        "Nếu dùng URL: dán vào <b>PDF URL</b> (chỉ nhận http/https an toàn).",
        "Đặt <b>Tiêu đề tải</b> và <b>Tiêu đề nút tải</b>."
      ]},
      {h:"Diễn giả của trang", items:[
        "Nhập <b>Tên diễn giả, Chức danh, Công ty, Tiểu sử</b>, tải <b>Ảnh đại diện diễn giả</b>."
      ]},
      {h:"Cài đặt khác", items:[
        "<b>Đếm ngược, Hạn chót đăng ký, Bật lịch, Zalo URL, Fanpage URL</b>.",
        "<b>Ảnh bìa, Ảnh thumbnail</b>.",
        "Bấm <b>Lưu</b>."
      ]}],
    warn:"Mỗi người điền form sẽ tạo 1 <b>lead</b> (có thể đồng bộ CRM). Đảm bảo Tag CRM đúng tên chiến dịch.",
    tips:"Đường dẫn công khai: <code>/chia-se/ten-slug</code>. Sau khi đăng ký khách được chuyển sang trang cảm ơn."
  },

  {section:"Nội dung website", icon:"🏢", title:"Hồ sơ công ty (Company Profile)",
    summary:"Nội dung phần giới thiệu công ty trên trang chủ.",
    tags:["company profile","hồ sơ","công ty","section","items"],
    groups:[
      {h:"Sửa nội dung", items:[
        "Vào menu <b>Company Profile</b>.",
        "Trang hiện các ô nhập theo phần: thông tin liên hệ, các mục (sec_*).",
        "Sửa xong bấm nút <b>Lưu</b>."
      ]},
      {h:"Quản lý phần tử theo mục (Section Items)", items:[
        "Vào menu <b>Company Profile</b>, bấm <b>Section Items</b> (hoặc Add Item).",
        "Chọn tab mục để lọc.",
        "Bấm <b>Add Item</b> để mở form.",
        "Chọn <b>Mục</b>, nhập <b>Tiêu đề</b> (bắt buộc), <b>Nhóm/Phân loại</b>, <b>Link</b>, <b>Thứ tự</b>, <b>Mô tả</b>.",
        "Tải <b>Ảnh/Logo</b> nếu cần, bấm <b>Add Item</b>."
      ]},
      {h:"Thay đổi thứ tự", items:[
        "Mỗi dòng có nút <b>Up</b> (lên) và <b>Down</b> (xuống) để sắp xếp."
      ]}]
  },

  {section:"Nội dung website", icon:"📬", title:"Hộp thư liên hệ (Contact Messages)",
    summary:"Xem và quản lý tin nhắn liên hệ, đăng ký sự kiện.",
    tags:["contact","liên hệ","tin nhắn","message","đăng ký"],
    groups:[
      {h:"Xem tin nhắn", items:[
        "Vào menu <b>Contact Messages</b> ở cuối menu trái.",
        "Danh sách hiện các tin: nguồn (Trang chủ / Sự kiện), tên, email, nội dung.",
        "Bấm <b>Xem</b> để đọc chi tiết.",
        "Bấm <b>Xóa</b> nếu cần."
      ]},
      {h:"Xóa nhiều tin cùng lúc", items:[
        "Tích vào ô đầu các dòng muốn xóa.",
        "Bấm nút <b>Xóa các dòng đã chọn</b> ở đầu bảng.",
        "Xác nhận OK."
      ]}],
    warn:"Tin từ trang chủ có event_id = 0; từ trang sự kiện gắn tên sự kiện."
  },

  {section:"Quản lý tài khoản", icon:"🔓", title:"Quyền (Permissions)",
    tags:["permission","quyền","access"],
    groups:[
      {h:"Thêm quyền", items:[
        "Vào menu <b>User Management → Quyền</b>.",
        "Bấm <b>＋ Thêm Quyền</b>.",
        "Nhập <b>Tiêu đề</b> quyền, ví dụ <code>speaker_access</code>.",
        "Bấm <b>Lưu</b>."
      ]}],
    warn:"Mục nâng cao. Quyền thường theo khuôn <code>tên_module_access / create / edit / show / delete</code>."
  },

  {section:"Quản lý tài khoản", icon:"💼", title:"Vai trò (Roles)",
    tags:["role","vai trò","nhóm quyền"],
    groups:[
      {h:"Tạo vai trò mới", items:[
        "Vào menu <b>User Management → Vai trò</b>.",
        "Bấm <b>＋ Thêm Vai trò</b>.",
        "Nhập <b>Tiêu đề</b> (bắt buộc), ví dụ Editor.",
        "<b>Quyền</b>: tích chọn các quyền nhóm được phép (bắt buộc ≥ 1).",
        "Dùng <b>Chọn tất cả</b> để cấp toàn quyền.",
        "Bấm <b>Lưu</b>."
      ]}],
    tips:"Nguyên tắc: tạo vai trò → gán quyền cho vai trò → gán vai trò cho người dùng."},

  {section:"Quản lý tài khoản", icon:"👤", title:"Người dùng (Users)",
    summary:"Tạo tài khoản đăng nhập và gán vai trò.",
    tags:["user","người dùng","tài khoản","mật khẩu"],
    groups:[
      {h:"Thêm người dùng", items:[
        "Vào menu <b>User Management → Người dùng</b>.",
        "Bấm <b>＋ Thêm Người dùng</b>.",
        "<b>Tên</b>, <b>Email</b>, <b>Mật khẩu</b> (bắt buộc).",
        "<b>Vai trò</b>: chọn 1 hoặc nhiều vai trò (bắt buộc).",
        "Bấm <b>Lưu</b>."
      ]},
      {h:"Đổi mật khẩu", items:[
        "Mở trang <b>Sửa</b> của người dùng.",
        "Nhập mật khẩu mới rồi <b>Lưu</b>.",
        "Để trống = giữ nguyên mật khẩu cũ."
      ]}],
    warn:"Không tạo tài khoản trùng email. Giao mật khẩu qua kênh an toàn."
  },

  {section:"Website công khai", icon:"🏠", title:"Trang chủ & các trang website",
    tags:["home","trang chủ","posts","dự án","website"],
    groups:[
      {h:"Các trang công khai", items:[
        "<b>Trang chủ</b> (<code>/</code>): hero, giới thiệu công ty (từ Company Profile), tin mới.",
        "<b>Tin tức</b> (<code>/posts</code>): bài viết đã xuất bản; <code>/posts/tag/...</code> lọc theo tag.",
        "<b>Dự án</b> (<code>/du-an</code>): các case study.",
        "<b>Trang chia sẻ</b> (<code>/chia-se</code>): danh sách tài liệu/ebook.",
        "<b>Trang sự kiện</b> (<code>/event</code>): sự kiện đang active.",
        "<b>Chi tiết diễn giả</b> (<code>/speaker/...</code>): tiểu sử đầy đủ."
      ]}],
    tips:"Nội dung lấy trực tiếp từ dữ liệu nhập ở admin. Sửa ở admin là website đổi ngay (tải lại trang)."
  },

  {section:"Website công khai", icon:"🎟️", title:"Trang sự kiện & đăng ký tham dự",
    tags:["event","đăng ký","ticket","sự kiện"],
    groups:[
      {h:"Trang sự kiện hiển thị gì", items:[
        "Sự kiện <b>đang active</b> sẽ hiện trên <code>/event</code>.",
        "Trang gồm: giới thiệu, đếm ngược, diễn giả, lịch trình, địa điểm, khách sạn, thư viện ảnh, nhà tài trợ, FAQ, gửi."
      ]},
      {h:"Form đăng ký", items:[
        "Khách nhập <b>Tên, Email</b>, chọn <b>Loại vé</b>.",
        "Bấm <b>Đăng ký</b>.",
        "Thành công chuyển sang trang cảm ơn.",
        "Thông tin tự ghi vào <b>Contact Messages</b>."
      ]}],
    warn:"Form giới hạn tối đa 10 lần/phút mỗi IP để chống spam. Bị chặn thì chờ 1 phút rồi thử lại."
  },

  {section:"Website công khai", icon:"📥", title:"Form liên hệ trang chủ",
    tags:["contact","liên hệ","form"],
    groups:[
      {h:"Cách hoạt động", items:[
        "Khách điền <b>Tên, Email, Tiêu đề, Nội dung</b>, chọn sự kiện.",
        "Bấm <b>Gửi</b>.",
        "Tin được lưu vào <b>Contact Messages</b> (nguồn: Trang chủ)."
      ]}],
    warn:"Tên ≥ 4 ký tự, tiêu đề ≥ 4 ký tự. Nhập thiếu hệ thống từ chối."
  },

  {section:"Thao tác chung", icon:"✏️", title:"Thêm mới bản ghi",
    tags:["thêm","create","add","new"],
    groups:[
      {h:"Các bước chung", items:[
        "Vào đúng module ở menu trái.",
        "Bấm nút xanh <b>＋ Thêm [Tên module]</b> ở trên danh sách.",
        "Điền các trường. Trường có dấu <b>*</b> là bắt buộc.",
        "Nếu có phần tải ảnh: tải ảnh xong hãy bấm Lưu.",
        "Bấm nút đỏ <b>Lưu</b> ở cuối form.",
        "Quay về danh sách, bản ghi mới xuất hiện ở trên."
      ]}],
    tips:"Thiếu trường bắt buộc sẽ hiện lỗi đỏ ngay dưới trường và chưa lưu — sửa rồi Lưu lại."
  },

  {section:"Thao tác chung", icon:"🛠️", title:"Sửa bản ghi",
    tags:["sửa","edit","update","chỉnh sửa"],
    groups:[
      {h:"Các bước chung", items:[
        "Vào danh sách của module.",
        "Tìm dòng cần sửa, bấm nút <b>Sửa</b>.",
        "Sửa các trường cần thay đổi.",
        "Ảnh mới sẽ thay ảnh cũ; để trống thì giữ ảnh cũ.",
        "Bấm <b>Lưu</b>."
      ]}],
    warn:"Ảnh cũ giữ nguyên nếu không tải ảnh mới."
  },

  {section:"Thao tác chung", icon:"🗑️", title:"Xóa bản ghi & Xóa hàng loạt",
    tags:["xóa","delete","mass","hàng loạt"],
    groups:[
      {h:"Xóa một bản ghi", items:[
        "Trong danh sách, bấm nút <b>Xóa</b> (đỏ) ở dòng cần xóa.",
        "Xác nhận OK.",
        "Bản ghi bị xóa (mềm), không còn hiển thị."
      ]},
      {h:"Xóa nhiều bản ghi", items:[
        "Tích vào ô đầu các dòng muốn xóa.",
        "Bấm nút <b>Xóa các dòng đã chọn</b> ở đầu bảng.",
        "Xác nhận OK."
      ]}],
    warn:"Có hộp thoại xác nhận để tránh xóa nhầm. Không khôi phục được từ giao diện."
  },

  {section:"Thao tác chung", icon:"🖼️", title:"Tải ảnh / file lên (Dropzone)",
    tags:["ảnh","upload","dropzone","file","hình"],
    groups:[
      {h:"Tải 1 ảnh", items:[
        "Bấm vào khung hoặc kéo-thả file vào.",
        "Đợi ảnh hiện trong khung.",
        "Bấm <b>Lưu</b> để gắn ảnh."
      ]},
      {h:"Tải nhiều ảnh (địa điểm / thư viện)", items:[
        "Kéo-thả nhiều file cùng lúc hoặc bấm chọn nhiều file.",
        "Mỗi ảnh hiện một thumbnail.",
        "Bấm × để bỏ một ảnh trước khi lưu.",
        "Bấm <b>Lưu</b>."
      ]}],
    warn:"Giới hạn mỗi ảnh ≤ 2MB. Định dạng: <code>jpg, png, gif</code>. File quá lớn hoặc sai định dạng bị từ chối."
  },

  {section:"Thao tác chung", icon:"🌐", title:"Nội dung đa ngôn ngữ (Việt / Anh)",
    summary:"Nhập một trường hiển thị được cả 2 ngôn ngữ bằng định dạng JSON.",
    tags:["đa ngôn ngữ","ngôn ngữ","vi","en","json","tiếng việt","english"],
    groups:[
      {h:"Cách nhập", items:[
        "Trong các ô nội dung (mô tả, tiêu đề, câu hỏi…), gõ định dạng JSON với 2 khóa <code>vi</code> và <code>en</code>, ngăn cách bằng dấu phẩy:",
        "<code>{&quot;vi&quot;:&quot;Nội dung tiếng Việt&quot;,&quot;en&quot;:&quot;English content&quot;}</code>",
        "Nhớ gõ đúng dấu ngoặc nhọn <b>{ }</b>, dấu hai chấm <b>:</b>, và dấu phẩy <b>,</b> giữa 2 ngôn ngữ.",
        "Lưu lại."
      ]},
      {h:"Nhập 1 ngôn ngữ (không JSON)", items:[
        "Nếu chỉ gõ chữ thường không có dấu <code>{</code> ở đầu, giá trị đó hiển thị cho <b>cả 2 ngôn ngữ</b>.",
        "Ưu tiên: dùng JSON nếu bạn có nội dung riêng cho từng ngôn ngữ."
      ]},
      {h:"Ví dụ", items:[
        "Đúng: <code>{&quot;vi&quot;:&quot;Chào mừng&quot;,&quot;en&quot;:&quot;Welcome&quot;}</code>",
        "Sai (thiếu dấu): <code>{&quot;vi&quot;:&quot;Chào mừng&quot; &quot;en&quot;:&quot;Welcome&quot;}</code>"
      ]}],
    warn:"Viết sai JSON (thiếu dấu ngoặc, dấu phẩy, dấu hai chấm) sẽ làm trường trống hoặc hiển thị nguyên chuỗi. Các trường nội dung dài (mô tả, câu hỏi FAQ, về sự kiện) đều hỗ trợ định dạng này.",
    tips:"Xem xem ngôn ngữ đang hiển thị: nút VI / EN trên website công khai. Kiểm tra sau khi lưu bằng cách đổi ngôn ngữ trên website."
  },

  {section:"Thao tác chung", icon:"🔎", title:"Tìm kiếm & sắp xếp trong danh sách",
    tags:["tìm","search","sắp xếp","bảng"],
    groups:[
      {h:"Cách dùng", items:[
        "Ở góc phải trên mỗi bảng danh sách có ô <b>Search</b>.",
        "Gõ từ khóa để lọc nhanh.",
        "Bấm vào tiêu đề cột để sắp xếp tăng/giảm.",
        "Mỗi trang hiện mặc định 100 dòng, có phân trang."
      ]}]
  },

  {section:"Khắc phục sự cố", icon:"🧯", title:"Không lưu được",
    tags:["lỗi","không lưu","error"],
    groups:[
      {h:"Kiểm tra theo thứ tự", items:[
        "<b>Thiếu trường bắt buộc</b>: tìm các ô có dấu * và chữ lỗi đỏ.",
        "<b>Sai định dạng</b>: giờ phải đủ giây (<code>09:00:00</code>), vĩ độ/kinh độ trong phạm vi, link http(s).",
        "<b>Email trùng</b> (Người dùng): dùng email khác.",
        "<b>Ảnh quá 2MB hoặc sai định dạng</b>: nén lại.",
        "<b>Phiên hết hạn</b>: đăng nhập lại rồi thử lại."
      ]}]
  },

  {section:"Khắc phục sự cố", icon:"📵", title:"Website không hiển thị",
    tags:["lỗi","website","hiển thị","cache","xuất bản"],
    groups:[
      {h:"Kiểm tra", items:[
        "Bấm <b>F5</b> hoặc <b>Ctrl + F5</b>, xóa bộ nhớ đệm trình duyệt.",
        "Đảm bảo bản ghi có trạng thái hiển thị: sự kiện = Active, bài viết = Xuất bản, menu = Hoạt động.",
        "Đảm bảo xem đúng sự kiện.",
        "Nếu dùng máy chủ có cache, liên hệ quản trị để xóa."
      ]}]
  }

];

/* ===== RENDER ===== */
(function(){
  const content = document.getElementById("g-content");
  const overlay = document.getElementById("g-overlay");
  const modal = document.getElementById("g-modal");
  const q = document.getElementById("g-q");
  const zrange = document.getElementById("g-zrange");
  const zval = document.getElementById("g-zval");
  const chipsEl = document.getElementById("g-chips");
  const countEl = document.getElementById("g-count");
  const emptyEl = document.getElementById("g-empty");
  const guide = document.getElementById("guide");

  let currentSection = "Tất cả";

  const sections = ["Tất cả", ...Array.from(new Set(GUIDE_DATA.map(c=>c.section)))];

  function tagText(c){ return c.tags ? c.tags.join(" ") : ""; }

  function renderChips(){
    chipsEl.innerHTML = "";
    sections.forEach(sec=>{
      const b = document.createElement("button");
      b.textContent = sec;
      if(sec===currentSection) b.classList.add("active");
      b.addEventListener("click", ()=>{ currentSection=sec; renderChips(); renderCards(); });
      chipsEl.appendChild(b);
    });
  }

  function cardHTML(c){
    const t = (c.tags||[]).map(x=>`<span class="g-tag">${x}</span>`).join("");
    return `<article class="g-card">
      <div class="g-ic">${c.icon}</div>
      <div class="g-c-title">${c.title}</div>
      <div class="g-c-summary">${c.summary||""}</div>
      <div class="g-meta">${t}</div>
      <div class="g-open-hint">Xem hướng dẫn chi tiết →</div>
    </article>`;
  }

  function renderCards(){
    const secs = {};
    const term = q.value.trim().toLowerCase();
    let shown = 0;
    GUIDE_DATA.forEach(c=>{
      const hay = (c.title+" "+(c.summary||"")+" "+tagText(c)).toLowerCase();
      const hit = !term || hay.includes(term);
      const secHit = currentSection==="Tất cả" || c.section===currentSection;
      (secs[c.section]=secs[c.section]||[]).push({c,show:hit&&secHit});
      if(hit&&secHit) shown++;
    });
    content.innerHTML = "";
    for(const sec of Object.keys(secs)){
      if(!secs[sec].some(x=>x.show)) continue;
      const head = document.createElement("div");
      head.className = "g-section-head"; head.textContent = sec;
      content.appendChild(head);
      const grid = document.createElement("div");
      grid.className = "g-grid";
      secs[sec].forEach(({c,show})=>{
        const el = document.createElement("div");
        el.innerHTML = cardHTML(c);
        const card = el.firstElementChild;
        card.classList.toggle("g-hidden", !show);
        card.addEventListener("click", ()=>openModal(c));
        grid.appendChild(card);
      });
      content.appendChild(grid);
    }
    countEl.textContent = shown + " / " + GUIDE_DATA.length + " chức năng";
    emptyEl.classList.toggle("g-show", shown===0);
  }

  function openModal(c){
    const steps = (c.groups||[]).map((g,i)=>`
      <div class="g-grp">
        <h3><span class="g-step-badge">${i+1}</span> ${g.h}</h3>
        <ol>${g.items.map(it=>`<li>${it}</li>`).join("")}</ol>
      </div>`).join("");
    const warn = c.warn ? `<div class="g-warn">⚠️ ${c.warn}</div>` : "";
    const tip = c.tips ? `<div class="g-tip">💡 ${c.tips}</div>` : "";
    const t = (c.tags||[]).map(x=>`<span class="g-tag">${x}</span>`).join("");
    modal.innerHTML = `
      <div class="g-modal-head">
        <div class="g-ic">${c.icon}</div>
        <div>
          <div class="g-sec">${c.section}</div>
          <h2>${c.title}</h2>
        </div>
        <button class="g-modal-close" id="g-close" aria-label="Đóng">✕</button>
      </div>
      <div class="g-modal-body">${warn}${steps}${tip}</div>
      <div class="g-modal-foot">${t}</div>`;
    document.getElementById("g-close").addEventListener("click", closeModal);
    overlay.classList.add("g-open");
    document.body.style.overflow = "hidden";
  }
  function closeModal(){
    overlay.classList.remove("g-open");
    document.body.style.overflow = "";
  }
  overlay.addEventListener("click", e=>{ if(e.target===overlay) closeModal(); });
  document.addEventListener("keydown", e=>{ if(e.key==="Escape") closeModal(); });

  q.addEventListener("input", renderCards);
  zrange.addEventListener("input", ()=>{
    guide.style.zoom = (zrange.value/100);
    zval.textContent = zrange.value + "%";
  });
  document.getElementById("g-zplus").addEventListener("click", ()=>{ zrange.value=Math.min(160,+zrange.value+10); zrange.dispatchEvent(new Event("input")); });
  document.getElementById("g-zminus").addEventListener("click", ()=>{ zrange.value=Math.max(70,+zrange.value-10); zrange.dispatchEvent(new Event("input")); });

  renderChips();
  renderCards();
})();
</script>
@endverbatim
@endsection