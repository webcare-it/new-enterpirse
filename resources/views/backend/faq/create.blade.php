@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('Create FAQ') }}</h1>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">

            <form action="{{ route('faq.store') }}" method="POST">
                @csrf

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 h6">{{ translate('FAQ Information') }}</h5>
                        <a href="{{ route('faq.index') }}" class="btn btn-secondary btn-sm">
                            {{ translate('Back') }}
                        </a>
                    </div>

                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">
                                {{ translate('Question') }}
                            </label>

                            <div class="col-md-9">
                                <input type="text" name="question" class="form-control"
                                    placeholder="{{ translate('Enter question') }}" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">
                                {{ translate('Answer') }}
                            </label>

                            <div class="col-md-9">
                                <textarea name="answer" rows="5" class="form-control" placeholder="{{ translate('Enter answer') }}" required></textarea>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="text-right mb-3">
                    <button type="submit" class="btn btn-success">
                        {{ translate('Save FAQ') }}
                    </button>
                </div>

            </form>

        </div>
    </div>
@endsection
