<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdateEventRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('event_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name'                  => ['required', 'string', 'max:255'],
            'slug'                  => ['nullable', 'string', 'max:255'],
            'description'           => ['nullable', 'string'],
            'start_date'            => ['nullable', 'date'],
            'end_date'              => ['nullable', 'date'],
            'is_active'             => ['nullable', 'boolean'],
            'countdown_enabled'     => ['nullable', 'boolean'],
            'about_description'     => ['nullable', 'string'],
            'about_where'           => ['nullable', 'string'],
            'about_when'            => ['nullable', 'string'],
            'registration_deadline' => ['nullable', 'date'],
            'meta_title'            => ['nullable', 'string', 'max:255'],
            'meta_description'      => ['nullable', 'string', 'max:500'],
            'calendar_enabled'      => ['nullable', 'boolean'],
            'zalo_url'              => ['nullable', 'url', 'starts_with:http://,https://'],
            'fanpage_url'           => ['nullable', 'url', 'starts_with:http://,https://'],
            'show_gallery'          => ['nullable', 'boolean'],
            'show_sponsors'         => ['nullable', 'boolean'],
            'show_tickets'          => ['nullable', 'boolean'],
            'mobile_bg_image'       => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'pc_bg_image'           => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];
    }
}
