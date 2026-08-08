@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        Contact Message #{{ $contact->id }}
    </div>

    <div class="card-body">
        <div class="mb-2">
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th style="width: 180px;">
                            ID
                        </th>
                        <td>
                            {{ $contact->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Event
                        </th>
                        <td>
                            {{ $contact->source_label }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Name
                        </th>
                        <td>
                            {{ $contact->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Email
                        </th>
                        <td>
                            <a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Subject
                        </th>
                        <td>
                            {{ $contact->subject }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Message
                        </th>
                        <td>
                            {{ $contact->message }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Received
                        </th>
                        <td>
                            {{ $contact->created_at ? $contact->created_at->format('d M Y H:i') : '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Status
                        </th>
                        <td>
                            @if($contact->read_at)
                                <span class="badge badge-pill" style="background: var(--green-light); color: var(--primary-active);">Read</span>
                            @else
                                <span class="badge badge-pill" style="background: var(--primary); color: #fff;">New</span>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
            <a style="margin-top:20px;" class="btn btn-default" href="{{ url()->previous() }}">
                {{ trans('global.back_to_list') }}
            </a>
        </div>


    </div>
</div>
@endsection
