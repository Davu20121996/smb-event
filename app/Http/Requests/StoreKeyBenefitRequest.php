<?php

namespace App\Http\Requests;

use App\KeyBenefit;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreKeyBenefitRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('key_benefit_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'title' => [
                'required',
            ],
        ];
    }
}
