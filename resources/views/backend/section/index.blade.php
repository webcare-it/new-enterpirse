@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="h3">{{ translate('Website Pages') }}</h1>
            </div>
        </div>
    </div>

    @include('backend.section._tabs')
@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection
