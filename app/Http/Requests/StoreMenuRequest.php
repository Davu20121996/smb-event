<?php

namespace App\Http\Requests;

use App\Menu;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreMenuRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('menu_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'label' => [
                'required',
            ],
            'url'   => [
                'nullable',
            ],
        ];
    }
}
