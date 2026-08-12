@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('Edit FAQ') }}</h1>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">

            <form action="{{ route('faq.update', $faq->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 h6">{{ translate('FAQ Information') }}</h5>
                        <a href="{{ route('faq.index') }}" class="btn btn-secondary btn-sm">
                            {{ translate('Back') }}
                        </a>
                    </div>

                    <div class="card-body">

                        {{-- Question --}}
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">
                                {{ translate('Question') }}
                            </label>

                            <div class="col-md-9">
                                <input type="text" name="question" value="{{ old('question', $faq->question) }}"
                                    class="form-control" required>
                            </div>
                        </div>

                        {{-- Answer --}}
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">
                                {{ translate('Answer') }}
                            </label>

                            <div class="col-md-9">
                                <textarea name="answer" rows="5" class="form-control" required>{{ old('answer', $faq->answer) }}</textarea>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Submit --}}
                <div class="text-right mb-3">
                    <button type="submit" class="btn btn-success">
                        {{ translate('Update FAQ') }}
                    </button>
                </div>

            </form>

        </div>
    </div>
@endsection
