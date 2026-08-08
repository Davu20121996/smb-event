<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StorePostRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('post_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'title'        => ['required', 'string', 'max:255'],
            'slug'         => ['nullable', 'string', 'max:255'],
            'tag'          => ['nullable', 'string', 'max:255'],
            'excerpt'      => ['nullable', 'string', 'max:1000'],
            'content'      => ['nullable', 'string'],
            'is_published' => ['sometimes', 'boolean'],
        ];
    }
}
