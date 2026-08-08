@php
    $landingIcons = include resource_path('views/admin/landing-pages/_icons.php');
@endphp

<h5 class="mt-3 mb-3"><strong>{{ trans('cruds.landingPage.sections.countdown') }}</strong></h5><div class="form-group {{ $errors->has('countdown_enabled') ? 'has-error' : '' }}">
    <label for="countdown_enabled">{{ trans('cruds.landingPage.fields.countdown_enabled') }}</label>
    <select name="countdown_enabled" id="countdown_enabled" class="form-control">
        <option value="1" {{ (isset($landingPage) && $landingPage->countdown_enabled) ? 'selected' : '' }}>{{ trans('global.yes') }}</option>
        <option value="0" {{ !isset($landingPage) || !$landingPage->countdown_enabled ? 'selected' : '' }}>{{ trans('global.no') }}</option>
    </select>
    @if($errors->has('countdown_enabled'))
        <p class="help-block">
            {{ $errors->first('countdown_enabled') }}
        </p>
    @endif
    <p class="helper-block">
        {{ trans('cruds.landingPage.fields.countdown_enabled_helper') }}
    </p>
</div>
<div class="form-group {{ $errors->has('registration_deadline') ? 'has-error' : '' }}">
    <label for="registration_deadline">{{ trans('cruds.landingPage.fields.registration_deadline') }}</label>
    <input type="datetime-local" id="registration_deadline" name="registration_deadline" class="form-control" value="{{ old('registration_deadline', isset($landingPage) && $landingPage->registration_deadline ? $landingPage->registration_deadline->format('Y-m-d\TH:i') : '') }}">
    @if($errors->has('registration_deadline'))
        <p class="help-block">
            {{ $errors->first('registration_deadline') }}
        </p>
    @endif
    <p class="helper-block">
        {{ trans('cruds.landingPage.fields.registration_deadline_helper') }}
    </p>
</div>

<hr>

<h5 class="mt-3 mb-3"><strong>{{ trans('cruds.landingPage.sections.key_benefits') }}</strong></h5>

<div id="key-benefits-rows">
    @if(isset($landingPage) && $landingPage->key_benefits)
        @foreach($landingPage->key_benefits as $kbIndex => $kb)
            <div class="row key-benefit-row mb-2" data-index="{{ $kbIndex }}">
                <div class="col-md-2">
                    <select name="key_benefits[{{ $kbIndex }}][icon]" class="form-control icon-select">
                        @foreach($landingIcons as $iconClass => $iconLabel)
                            <option value="{{ $iconClass }}" {{ (($kb['icon'] ?? 'fa-star') == $iconClass) ? 'selected' : '' }}>{{ $iconLabel }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="text" name="key_benefits[{{ $kbIndex }}][title]" class="form-control" value="{{ tr($kb['title'] ?? '') }}" placeholder="{{ trans('cruds.landingPage.fields.kb_title_ph') }}">
                </div>
                <div class="col-md-6">
                    <input type="text" name="key_benefits[{{ $kbIndex }}][description]" class="form-control" value="{{ tr($kb['description'] ?? '') }}" placeholder="{{ trans('cruds.landingPage.fields.kb_desc_ph') }}">
                </div>
                <div class="col-md-1 text-right">
                    <button type="button" class="btn btn-sm btn-danger remove-row" title="{{ trans('global.remove') }}"><i class="fa fa-minus"></i></button>
                </div>
            </div>
        @endforeach
    @endif
</div>
<div class="mb-3">
    <button type="button" class="btn btn-sm btn-success" id="add-key-benefit-row"><i class="fa fa-plus"></i> {{ trans('cruds.landingPage.add_row') }}</button>
</div>

<hr>

<h5 class="mt-3 mb-3"><strong>{{ trans('cruds.landingPage.sections.agenda') }}</strong></h5>

<div id="agenda-rows">
    @if(isset($landingPage) && $landingPage->agenda)
        @foreach($landingPage->agenda as $agIndex => $ag)
            <div class="agenda-row card mb-2 p-2" data-index="{{ $agIndex }}">
                <div class="row">
                    <div class="col-md-2">
                        <input type="text" name="agenda[{{ $agIndex }}][time]" class="form-control" value="{{ $ag['time'] ?? '' }}" placeholder="{{ trans('cruds.landingPage.fields.ag_time_ph') }}">
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="agenda[{{ $agIndex }}][title]" class="form-control" value="{{ tr($ag['title'] ?? '') }}" placeholder="{{ trans('cruds.landingPage.fields.ag_title_ph') }}">
                    </div>
                    <div class="col-md-5">
                        <input type="text" name="agenda[{{ $agIndex }}][description]" class="form-control" value="{{ tr($ag['description'] ?? '') }}" placeholder="{{ trans('cruds.landingPage.fields.ag_desc_ph') }}">
                    </div>
                    <div class="col-md-1">
                        <input type="text" name="agenda[{{ $agIndex }}][speaker]" class="form-control" value="{{ tr($ag['speaker'] ?? '') }}" placeholder="{{ trans('cruds.landingPage.fields.ag_speaker_ph') }}">
                    </div>
                    <div class="col-md-1 text-right">
                        <button type="button" class="btn btn-sm btn-danger remove-row" title="{{ trans('global.remove') }}"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>
<div class="mb-3">
    <button type="button" class="btn btn-sm btn-success" id="add-agenda-row"><i class="fa fa-plus"></i> {{ trans('cruds.landingPage.add_row') }}</button>
</div>

<hr>

<h5 class="mt-3 mb-3"><strong>{{ trans('cruds.landingPage.sections.speaker') }}</strong></h5>

<div class="form-group {{ $errors->has('speaker_name') ? 'has-error' : '' }}">
    <label for="speaker_name">{{ trans('cruds.landingPage.fields.speaker_name') }}</label>
    <input type="text" id="speaker_name" name="speaker_name" class="form-control" value="{{ old('speaker_name', isset($landingPage) ? $landingPage->speaker_name : '') }}">
    @if($errors->has('speaker_name'))
        <p class="help-block">
            {{ $errors->first('speaker_name') }}
        </p>
    @endif
</div>
<div class="row">
    <div class="col-md-6 form-group {{ $errors->has('speaker_role') ? 'has-error' : '' }}">
        <label for="speaker_role">{{ trans('cruds.landingPage.fields.speaker_role') }}</label>
        <input type="text" id="speaker_role" name="speaker_role" class="form-control" value="{{ old('speaker_role', isset($landingPage) ? $landingPage->speaker_role : '') }}">
        @if($errors->has('speaker_role'))
            <p class="help-block">
                {{ $errors->first('speaker_role') }}
            </p>
        @endif
    </div>
    <div class="col-md-6 form-group {{ $errors->has('speaker_company') ? 'has-error' : '' }}">
        <label for="speaker_company">{{ trans('cruds.landingPage.fields.speaker_company') }}</label>
        <input type="text" id="speaker_company" name="speaker_company" class="form-control" value="{{ old('speaker_company', isset($landingPage) ? $landingPage->speaker_company : '') }}">
        @if($errors->has('speaker_company'))
            <p class="help-block">
                {{ $errors->first('speaker_company') }}
            </p>
        @endif
    </div>
</div>
<div class="form-group {{ $errors->has('speaker_bio') ? 'has-error' : '' }}">
    <label for="speaker_bio">{{ trans('cruds.landingPage.fields.speaker_bio') }}</label>
    <textarea id="speaker_bio" name="speaker_bio" class="form-control" rows="3">{{ old('speaker_bio', isset($landingPage) ? $landingPage->speaker_bio : '') }}</textarea>
    @if($errors->has('speaker_bio'))
        <p class="help-block">
            {{ $errors->first('speaker_bio') }}
        </p>
    @endif
</div>
<div class="form-group {{ $errors->has('speaker_avatar') ? 'has-error' : '' }}">
    <label for="speaker_avatar">{{ trans('cruds.landingPage.fields.speaker_avatar') }}</label>
    <div class="needsclick dropzone" id="speaker-avatar-dropzone">

    </div>
    @if($errors->has('speaker_avatar'))
        <p class="help-block">
            {{ $errors->first('speaker_avatar') }}
        </p>
    @endif
    <p class="helper-block">
        {{ trans('cruds.landingPage.fields.speaker_avatar_helper') }}
    </p>
</div>

<hr>

<h5 class="mt-3 mb-3"><strong>{{ trans('cruds.landingPage.sections.thank_you') }}</strong></h5>

<div class="form-group {{ $errors->has('calendar_enabled') ? 'has-error' : '' }}">
    <label for="calendar_enabled">{{ trans('cruds.landingPage.fields.calendar_enabled') }}</label>
    <select name="calendar_enabled" id="calendar_enabled" class="form-control">
        <option value="1" {{ (isset($landingPage) && $landingPage->calendar_enabled) ? 'selected' : '' }}>{{ trans('global.yes') }}</option>
        <option value="0" {{ !isset($landingPage) || !$landingPage->calendar_enabled ? 'selected' : '' }}>{{ trans('global.no') }}</option>
    </select>
    @if($errors->has('calendar_enabled'))
        <p class="help-block">
            {{ $errors->first('calendar_enabled') }}
        </p>
    @endif
    <p class="helper-block">
        {{ trans('cruds.landingPage.fields.calendar_enabled_helper') }}
    </p>
</div>
<div class="form-group {{ $errors->has('zalo_url') ? 'has-error' : '' }}">
    <label for="zalo_url">{{ trans('cruds.landingPage.fields.zalo_url') }}</label>
    <input type="text" id="zalo_url" name="zalo_url" class="form-control" value="{{ old('zalo_url', isset($landingPage) ? $landingPage->zalo_url : '') }}">
    @if($errors->has('zalo_url'))
        <p class="help-block">
            {{ $errors->first('zalo_url') }}
        </p>
    @endif
</div>
<div class="form-group {{ $errors->has('fanpage_url') ? 'has-error' : '' }}">
    <label for="fanpage_url">{{ trans('cruds.landingPage.fields.fanpage_url') }}</label>
    <input type="text" id="fanpage_url" name="fanpage_url" class="form-control" value="{{ old('fanpage_url', isset($landingPage) ? $landingPage->fanpage_url : '') }}">
    @if($errors->has('fanpage_url'))
        <p class="help-block">
            {{ $errors->first('fanpage_url') }}
        </p>
    @endif
</div>
