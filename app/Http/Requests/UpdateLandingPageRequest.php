<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdateLandingPageRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('landing_page_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'title'                 => ['required', 'string', 'max:255'],
            'slug'                  => ['nullable', 'string', 'max:255', 'unique:landing_pages,slug,' . $this->route('landing_page')->id],
            'content'               => ['nullable', 'string'],
            'form_title'            => ['nullable', 'string', 'max:255'],
            'button_title'          => ['nullable', 'string', 'max:255'],
            'crm_tag'               => ['nullable', 'string', 'max:100'],
            'pdf_url'               => ['nullable', 'url', 'starts_with:http://,https://'],
            'report_url'            => ['nullable', 'url', 'starts_with:http://,https://'],
            'zalo_url'              => ['nullable', 'url', 'starts_with:http://,https://'],
            'fanpage_url'           => ['nullable', 'url', 'starts_with:http://,https://'],
            'registration_deadline' => ['nullable', 'date'],
            'speaker_name'          => ['nullable', 'string', 'max:255'],
            'speaker_role'          => ['nullable', 'string', 'max:255'],
            'speaker_company'       => ['nullable', 'string', 'max:255'],
            'speaker_bio'           => ['nullable', 'string'],
        ];
    }
}
