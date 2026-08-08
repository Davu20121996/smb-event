<?php

namespace App\Http\Requests;

use App\Speaker;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdateSpeakerRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('speaker_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name'             => ['required', 'string', 'max:255'],
            'role'             => ['nullable', 'string', 'max:255'],
            'company'          => ['nullable', 'string', 'max:255'],
            'twitter'          => ['nullable', 'string', 'max:255'],
            'facebook'         => ['nullable', 'string', 'max:255'],
            'linkedin'         => ['nullable', 'string', 'max:255'],
            'description'      => ['nullable', 'string'],
            'full_description' => ['nullable', 'string'],
        ];
    }
}
