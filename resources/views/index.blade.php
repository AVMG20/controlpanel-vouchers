@extends('layouts.dashboard')

@section('content')
    <div class="main py-4">

        @can('admin.vouchers.write')
            <div class="d-flex justify-content-end my-3">
                <a href="{{route('controlpanel.vouchers.create')}}" class="btn btn-primary"><i
                        class="fa fas fa-money-bill pe-2"></i>{{__('Create voucher')}}</a>
            </div>
        @endcan

        <div class="card card-body border-0 shadow table-wrapper table-responsive">
            <h2 class="mb-4 h5">{{ __('Vouchers') }}</h2>

            {!! $html->table() !!}

        </div>
    </div>
@endsection

@section('scripts')
    {!! $html->scripts() !!}
@endsection


