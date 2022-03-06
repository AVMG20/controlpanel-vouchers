@extends('layouts.dashboard')

@section('content')
    <div class="main py-4">

        <div class="row">
            <div class="col-lg-6">

                <div class="card card-body border-0 shadow table-wrapper table-responsive">
                    <h2 class="mb-4 h5">{{ isset($voucher) ?  __('Edit voucher') : __('Create voucher') }}</h2>

                    <form method="post"
                          action="{{isset($voucher) ? route('controlpanel.vouchers.update', $voucher->id) : route('controlpanel.vouchers.store')}}">
                        @csrf
                        @isset($voucher)
                            @method('PATCH')
                        @endisset


                        <x-input.text label="{{(__('Memo'))}}"
                                      name="memo"
                                      tooltip="{{__('Only admins can see this, used for identifying vouchers')}}"
                                      value="{{ isset($voucher) ? $voucher->memo : null}}"/>

                        <x-input.number label="* {{ $settings->credits_display_name }}"
                                        name="credits"
                                        min="0"
                                        max="9999999999999"
                                        step=".000001"
                                        value="{{ isset($voucher) ? $voucher->credits : 0}}"/>

                        <x-input.text label="* {{(__('Code'))}}"
                                      name="code"
                                      tooltip="{{__('The code used to redeem vouchers')}}"
                                      value="{{ isset($voucher) ? $voucher->code : $random}}"/>

                        <x-input.number label="* {{(__('Uses'))}}"
                                        name="uses"
                                        min="0"
                                        max="2147483647"
                                        tooltip="{{__('A voucher can only be used 1 time per user. Uses specifies the amount of different users that can use this voucher')}}"
                                        value="{{ isset($voucher) ? $voucher->uses : 1}}"/>

                        <x-input.text label="{{(__('Expires at'))}}"
                                      name="expires_at"
                                      tooltip="{{__('A voucher is expired at and after this date')}}"
                                      type="date"
                                      value="{{ isset($voucher) ? $voucher->expires_at->format('Y-m-d') : null}}"/>


                        <div class="form-group d-flex justify-content-end mt-3">
                            <button name="submit" type="submit" class="btn btn-primary">{{__('Submit')}}</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>

    </div>
@endsection

