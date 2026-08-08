<?php

namespace App\Http\Requests;

use App\Voucher;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class ActivateVoucherRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('voucher_assign'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'voucher_id' => ['required', 'integer', 'exists:vouchers,id'],
            'send_email' => ['nullable', 'boolean'],
            'force'      => ['nullable', 'boolean'],
            'note'       => ['nullable', 'string', 'max:500'],
        ];
    }
}