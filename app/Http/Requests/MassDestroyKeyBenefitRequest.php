<?php

namespace App\Http\Requests;

use App\KeyBenefit;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyKeyBenefitRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('key_benefit_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:key_benefits,id',
        ];
    }
}
