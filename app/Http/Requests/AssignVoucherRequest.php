<?php

namespace App\Http\Requests;

use App\Voucher;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class AssignVoucherRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('voucher_assign'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'attendee_ids'   => ['required', 'array', 'min:1'],
            'attendee_ids.*' => ['integer', 'exists:attendees,id'],
            'send_email'     => ['nullable', 'boolean'],
            'note'           => ['nullable', 'string', 'max:500'],
        ];
    }
}