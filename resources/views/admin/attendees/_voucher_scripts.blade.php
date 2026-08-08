<script>
    $(function () {
        let avAttendeeId = null;
        let avForce = false;

        function openVoucherModal(name, company, attendeeId) {
            avAttendeeId = attendeeId;
            avForce = false;
            $('#avModalAttendee').text(name);
            $('#avModalCompany').text(company || '');
            $('#avNote').val('');
            $('#avError').hide();
            $('#avPreview').hide();
            $('#avVoucherSelect').val('');
            $('#activeVoucherModal').modal('show');
        }

        $(document).on('click', '.btn-active-voucher, .btn-open-active-voucher', function () {
            openVoucherModal($(this).data('name'), $(this).data('company'), $(this).data('attendee'));
        });

        $('#avVoucherSelect').on('change', function () {
            var opt = $(this).find(':selected');
            if (opt.val()) {
                $('#avPreviewText').text(opt.data('name') + ' — ' + opt.data('label') + ' (còn ' + opt.data('remaining') + ' lượt)');
                $('#avPreview').show();
            } else {
                $('#avPreview').hide();
            }
        });

        $('#avConfirm').on('click', function () {
            var voucherId = $('#avVoucherSelect').val();
            if (!voucherId) { alert('Vui lòng chọn voucher.'); return; }
            $('#avError').hide();
            $.ajax({
                headers: { 'x-csrf-token': _token },
                method: 'POST',
                url: '{{ route('admin.attendees.activateVoucher', '__ATTENDEE__') }}'.replace('__ATTENDEE__', avAttendeeId),
                data: {
                    voucher_id: voucherId,
                    send_email: $('#avSendEmail').is(':checked') ? 1 : 0,
                    note: $('#avNote').val(),
                    force: avForce ? 1 : 0
                },
                success: function (res) {
                    if (res.status === 'success') {
                        $('#activeVoucherModal').modal('hide');
                        alert(res.message);
                        location.reload();
                    }
                },
                error: function (xhr) {
                    var res = xhr.responseJSON;
                    if (res && res.code === 'ALREADY_HAS_VOUCHER') {
                        avForce = true;
                        if (confirm(res.message + ' Chọn OK để thay thế voucher cũ.')) {
                            $('#avConfirm').trigger('click');
                        } else {
                            avForce = false;
                        }
                        return;
                    }
                    $('#avError').text((res && res.message) ? res.message : 'Kích hoạt voucher thất bại.').show();
                }
            });
        });

        $(document).on('click', '.btn-revoke-voucher', function () {
            if (!confirm('Thu hồi voucher của khách hiện tại?')) return;
            var attendeeId = $(this).data('attendee');
            $.ajax({
                headers: { 'x-csrf-token': _token },
                method: 'POST',
                url: '{{ route('admin.attendees.revokeVoucher', '__ATTENDEE__') }}'.replace('__ATTENDEE__', attendeeId),
                data: { reason: '' },
                success: function (res) {
                    alert(res.message);
                    location.reload();
                },
                error: function (xhr) {
                    alert((xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Thu hồi thất bại.');
                }
            });
        });
    });
</script>