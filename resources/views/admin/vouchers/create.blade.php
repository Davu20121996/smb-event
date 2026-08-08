@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        Tạo Voucher mới
    </div>

    <div class="card-body">
        <form action="{{ route('admin.vouchers.store') }}" method="POST">
            @csrf
            @include('admin.vouchers._form')
            <div>
                <input class="btn btn-danger" type="submit" value="Tạo Voucher">
                <a class="btn btn-default" href="{{ route('admin.vouchers.index') }}">Hủy</a>
            </div>
        </form>
    </div>
</div>

@endsection