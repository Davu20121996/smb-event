<?php

namespace App\Http\Requests;

use App\Voucher;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdateVoucherRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('voucher_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'event_id'      => ['nullable', 'integer', 'exists:events,id'],
            'code'          => ['required', 'string', 'max:50'],
            'name'          => ['required', 'string', 'max:255'],
            'type'          => ['required', 'in:discount_percent,discount_fixed,free_ticket,gift,priority_seat'],
            'value'         => ['required_if:type,discount_percent,discount_fixed', 'numeric', 'min:0'],
            'description'   => ['nullable', 'string'],
            'max_uses'      => ['nullable', 'integer', 'min:1'],
            'is_single_use' => ['nullable', 'boolean'],
            'is_assignable' => ['nullable', 'boolean'],
            'valid_from'    => ['nullable', 'date'],
            'valid_until'   => ['nullable', 'date', 'after:valid_from'],
            'status'        => ['required', 'in:active,inactive,expired'],
        ];
    }
}