@php
    $mlFields = $mlFields ?? [];
    $isCreate = $isCreate ?? (($createPage ?? false) === true);
    $presetEventId = $presetEventId ?? request('event_id', null);
@endphp
@if($presetEventId)
    <input type="hidden" name="event_id" value="{{ $presetEventId }}">
@endif
<p class="helper-block multilang-hint">
    <i class="fa fa-globe"></i>
    <strong>{{ trans('global.multilang_hint_title') }}</strong>:
    {{ trans('global.multilang_hint') }}
    <code>{"en":"English","vi":"Tiếng Việt"}</code>
</p>
@if($isCreate && !empty($mlFields))
<script>
    (function () {
        var fields = {!! json_encode($mlFields) !!};
        var template = '{"en":"","vi":""}';
        var form = document.querySelector('form');
        if (!form || !fields.length) return;

        fields.forEach(function (name) {
            var el = form.querySelector('[name="' + name + '"]');
            if (!el) return;
            if (el.classList.contains('ckeditor')) return;           // rich editor: keep raw
            var raw = el.value === undefined ? (el.textContent || '') : el.value;
            if (String(el.tagName).toUpperCase() === 'TEXTAREA') {
                if ((el.textContent || '').trim() === '') el.value = template;
            } else if (raw.trim() === '') {
                el.value = template;
            }
        });
    })();
</script>
@endif