<?php $__env->startSection('content'); ?>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <i class="fa fa-camera"></i> Quét mã QR Check-in
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="checkin_event">Sự kiện (để lọc & thống kê)</label>
                    <select id="checkin_event" class="form-control">
                        <option value="">Tất cả sự kiện</option>
                        <?php $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($event->id); ?>"><?php echo e($event->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="text-center mb-3">
                    <button type="button" class="btn btn-primary btn-lg" id="btnStartCamera">
                        <i class="fas fa-camera"></i> Mở Camera
                    </button>
                    <button type="button" class="btn btn-secondary btn-lg" id="btnStopCamera" style="display:none;">
                        <i class="fas fa-stop"></i> Tắt Camera
                    </button>
                    <button type="button" class="btn btn-warning" id="btnTorch" style="display:none;">
                        <i class="fas fa-lightbulb"></i> Đèn
                    </button>
                </div>

                <div id="qr-reader" style="width:100%; max-width:480px; margin:0 auto;"></div>

                <div class="text-center mt-3">
                    <strong>- HOẶC nhập mã check-in -</strong>
                    <form id="manualForm" class="form-inline justify-content-center mt-2">
                        <input type="text" id="manualCode" class="form-control" placeholder="ATT-2026-XXXXXXX"
                               style="min-width:220px; text-transform:uppercase;" autocomplete="off">
                        <button type="submit" class="btn btn-success ml-2">
                            <i class="fas fa-check"></i> Check-in
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-info-circle"></i> Kết quả
            </div>
            <div class="card-body" id="resultArea">
                <p class="text-muted mb-0">Quét mã QR hoặc nhập mã để check-in.</p>
            </div>
        </div>
    </div>
</div>

<div id="checkinToast" class="checkin-toast" style="display:none;">
    <div class="checkin-toast-icon"><i class="fas fa-check-circle"></i></div>
    <div class="checkin-toast-body">
        <h5 id="checkinToastTitle">Check-in thành công</h5>
        <p id="checkinToastMsg"></p>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('styles'); ?>
<?php echo \Illuminate\View\Factory::parentPlaceholder('styles'); ?>
<style>
    #qr-reader video { border-radius: 8px; }
    #resultArea .result-card { border-radius: 8px; padding: 16px; border: 1px solid #e5e5e5; }
    #resultArea .result-success { background: #d4edda; border-color: #c3e6cb; color: #155724; }
    #resultArea .result-error { background: #f8d7da; border-color: #f5c6cb; color: #842029; }
    #resultArea .result-already { background: #fff3cd; border-color: #ffeeba; color: #856404; }
    .checkin-toast { position: fixed; top: 50%; left: 50%; transform: translate(-50%,-50%); z-index: 1080;
        background: #fff; border-radius: 16px; box-shadow: 0 15px 50px rgba(0,0,0,.30);
        padding: 24px 34px; display: flex; align-items: center; gap: 16px; min-width: 300px; text-align:left;
        border: 3px solid #28a745; }
    .checkin-toast-icon { font-size: 48px; color: #28a745; flex-shrink: 0; }
    .checkin-toast-body h5 { margin: 0 0 4px; color: #155724; font-weight: 700; }
    .checkin-toast-body p { margin: 0; color: #155724; font-size: 16px; }
    .checkin-toast.checkin-toast-already { border-color: #ffc107; }
    .checkin-toast.checkin-toast-already .checkin-toast-icon { color: #ffc107; }
    .checkin-toast.checkin-toast-already .checkin-toast-body h5,
    .checkin-toast.checkin-toast-already .checkin-toast-body p { color: #856404; }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<?php echo \Illuminate\View\Factory::parentPlaceholder('scripts'); ?>
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
    $(function () {
        var html5QrCode = null;
        var currentScanning = false;

        function beep(ok) {
            try {
                var ctx = new (window.AudioContext || window.webkitAudioContext)();
                var osc = ctx.createOscillator();
                var gain = ctx.createGain();
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.frequency.value = ok ? 880 : 220;
                osc.type = 'square';
                gain.gain.setValueAtTime(0.3, ctx.currentTime);
                osc.start();
                osc.stop(ctx.currentTime + (ok ? 0.25 : 0.4));
            } catch (e) {}
        }

        function showResult(payload) {
            var area = $('#resultArea');
            area.empty();

            if (payload.success) {
                beep(true);
                showToast('Check-in thành công', payload.message, '');
                var cardClass = 'result-card result-success';
                var title = '<h5 class="mb-2"><i class="fas fa-check-circle"></i> Check-in thành công</h5>';
            } else if (payload.already) {
                showToast('Đã check-in trước đó', payload.message, 'checkin-toast-already');
                var cardClass = 'result-card result-already';
                var title = '<h5 class="mb-2"><i class="fas fa-clock"></i> Đã check-in trước đó</h5>';
            } else {
                beep(false);
                var cardClass = 'result-card result-error';
                var title = '<h5 class="mb-2"><i class="fas fa-times-circle"></i> Lỗi</h5>';
            }

            var a = payload.attendee || {};
            var html = '<div class="' + cardClass + '">' + title +
                '<p class="mb-2">' + payload.message + '</p>' +
                (a.name ? '<table class="table table-sm table-borderless mb-0 small">' +
                    '<tr><th>Họ tên</th><td><strong>' + a.name + '</strong></td></tr>' +
                    (a.event ? '<tr><th>Sự kiện</th><td>' + a.event + '</td></tr>' : '') +
                    (a.email ? '<tr><th>Email</th><td>' + a.email + '</td></tr>' : '') +
                    (a.company ? '<tr><th>Công ty</th><td>' + a.company + '</td></tr>' : '') +
                    (a.company_size_label ? '<tr><th>Quy mô</th><td>' + a.company_size_label + '</td></tr>' : '') +
                    (a.ticket_type ? '<tr><th>Vé</th><td>' + a.ticket_type + '</td></tr>' : '') +
                    (a.checked_in_at ? '<tr><th>Check-in lúc</th><td><strong>' + a.checked_in_at + '</strong></td></tr>' : '') +
                '</table>' : '') +
            '</div>';
            area.html(html);
        }

                function showToast(title, msg, cls) {
            $('#checkinToastTitle').text(title);
            $('#checkinToastMsg').text(msg);
            var toast = $('#checkinToast');
            toast.removeClass('checkin-toast-already').addClass(cls || '');
            toast.show();
            clearTimeout(toast.data('timer'));
            toast.data('timer', setTimeout(function () { toast.fadeOut(300); }, 2500));
        }

        function submitCode(code) {
            if (!code) return;
            code = String(code).trim();
            $('#manualCode').val(code);

            $.ajax({
                headers: { 'x-csrf-token': _token },
                url: '<?php echo e(route("admin.checkin.scan")); ?>',
                method: 'POST',
                data: { qr: code },
                success: function (res) { showResult(res); },
                error: function (xhr) {
                    var res = xhr.responseJSON || {};
                    showResult({ success: false, message: res.message || 'Lỗi máy chủ.' });
                }
            });
        }

        function startScanner() {
            if (!window.Html5Qrcode) {
                alert('Không tải được thư viện quét QR. Kiểm tra kết nối internet.');
                return;
            }
            $('#qr-reader').show();
            html5Qrcode = new Html5Qrcode('qr-reader');
            html5Qrcode.start(
                { facingMode: 'environment' },
                { fps: 10, qrbox: { width: 250, height: 250 } },
                function (decodedText) {
                    submitCode(decodedText);
                    setTimeout(function () {
                        if (html5Qrcode && html5Qrcode.isScanning) {
                            html5Qrcode.pause();
                            html5Qrcode.resume();
                        }
                    }, 1200);
                },
                function () {}
            ).then(function () {
                $('#btnStartCamera').hide();
                $('#btnStopCamera').show();
                $('#btnTorch').show();
            }).catch(function (err) {
                console.error(err);
                alert('Không thể mở camera: ' + err);
            });
        }

        function stopScanner() {
            if (html5Qrcode && html5Qrcode.isScanning) {
                html5Qrcode.stop().then(function () {
                    html5Qrcode.clear();
                    html5Qrcode = null;
                    $('#btnStartCamera').show();
                    $('#btnStopCamera').hide();
                    $('#btnTorch').hide();
                });
            } else {
                $('#btnStartCamera').show();
                $('#btnStopCamera').hide();
                $('#btnTorch').hide();
            }
        }

        $('#btnStartCamera').on('click', function (e) { e.preventDefault(); startScanner(); });
        $('#btnStopCamera').on('click', function (e) { e.preventDefault(); stopScanner(); });
        $('#btnTorch').on('click', function (e) {
            e.preventDefault();
            if (html5Qrcode && html5Qrcode.isScanning) {
                html5Qrcode.applyVideoConstraints({ advanced: [{ torch: true }] }).catch(function () {});
            }
        });
        $('#manualForm').on('submit', function (e) {
            e.preventDefault();
            submitCode($('#manualCode').val());
        });

        $(window).on('beforeunload', function () { if (html5Qrcode) { try { html5Qrcode.stop(); } catch (e) {} } });
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laragon\www\laravel\EMS-smb-v3\EMS-smb\resources\views/admin/checkin/index.blade.php ENDPATH**/ ?>