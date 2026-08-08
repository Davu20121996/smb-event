<?php

namespace App\Http\Requests;

use App\Venue;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdateVenueRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('venue_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name'        => [
                'required',
                'string',
                'max:255',
            ],
            'address'     => [
                'required',
                'string',
                'max:500',
            ],
            'latitude'    => [
                'nullable',
                'string',
                'max:50',
            ],
            'longitude'   => [
                'nullable',
                'string',
                'max:50',
            ],
            'google_maps_url' => [
                'nullable',
                'string',
                'max:1000',
            ],
            'description' => [
                'nullable',
                'string',
                'max:65535',
            ],
        ];
    }
}
