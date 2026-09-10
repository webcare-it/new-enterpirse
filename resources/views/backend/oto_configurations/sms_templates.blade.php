@extends('backend.layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{translate('SMS Templates')}}</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-3">
                            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                @foreach ($sms_templates as $key => $sms_template)
                                    <a class="nav-link @if($key == 0) active @endif" id="v-pills-tab-{{ $sms_template->id }}" data-toggle="pill" href="#v-pills-{{ $sms_template->id }}" role="tab" aria-controls="v-pills-profile" aria-selected="false">{{ translate(ucwords(str_replace('_', ' ', $sms_template->identifier))) }}</a>
                                @endforeach
                                <a class="nav-link" data-toggle="pill" href="#v-pills-new" role="tab"><i class="las la-plus"></i> {{ translate('Add New') }}</a>
                            </div>
                        </div>
                        <div class="col-9">
                            <div class="tab-content" id="v-pills-tabContent">
                                @foreach ($sms_templates as $key => $sms_template)
                                    <div class="tab-pane fade show @if($key == 0) active @endif" id="v-pills-{{ $sms_template->id }}" role="tabpanel">
                                        <form action="{{ route('sms-templates.update', $sms_template->id) }}" method="POST">
                                            <input name="_method" type="hidden" value="PATCH">
                                            @csrf
                                            <div class="form-group row">
                                                <label class="col-md-2 col-form-label">{{translate('Identifier')}}</label>
                                                <div class="col-md-10">
                                                    <input type="text" class="form-control" value="{{ $sms_template->identifier }}" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-md-2">
                                                    <label class="col-from-label">{{translate('Activation')}}</label>
                                                </div>
                                                <div class="col-md-10">
                                                    <label class="aiz-switch aiz-switch-success mb-0">
                                                        <input value="1" name="status" type="checkbox" @if ($sms_template->status == 1) checked @endif>
                                                        <span class="slider round"></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-2 col-form-label">{{translate('SMS Body')}}</label>
                                                <div class="col-md-10">
                                                    <textarea name="body" class="form-control" placeholder="Type.." rows="6" required>{{ $sms_template->sms_body }}</textarea>
                                                    <small class="form-text text-danger">{{ ('**N.B : Do Not Change The Variables Like [[ ____ ]].**') }}</small>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-2 col-form-label">{{translate('Template ID')}}</label>
                                                <div class="col-md-10">
                                                    <input type="text" name="template_id" value="{{ $sms_template->template_id }}" class="form-control" placeholder="{{translate('Template Id')}}">
                                                </div>
                                            </div>
                                            <div class="form-group mb-3 text-right">
                                                <button type="submit" class="btn btn-primary">{{translate('Update')}}</button>
                                                <button type="button" class="btn btn-danger" onclick="deleteTemplate({{ $sms_template->id }})">{{translate('Delete')}}</button>
                                            </div>
                                        </form>
                                    </div>
                                @endforeach

                                <div class="tab-pane fade" id="v-pills-new" role="tabpanel">
                                    <form action="{{ route('sms-templates.store') }}" method="POST">
                                        @csrf
                                        <div class="form-group row">
                                            <label class="col-md-2 col-form-label">{{translate('Identifier')}}</label>
                                            <div class="col-md-10">
                                                <input type="text" name="identifier" class="form-control" placeholder="e.g. order_placement" required>
                                                <small class="form-text text-muted">{{translate('Unique key like: phone_number_verification, order_placement, etc.')}}</small>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-2 col-form-label">{{translate('SMS Body')}}</label>
                                            <div class="col-md-10">
                                                <textarea name="sms_body" class="form-control" rows="6" required></textarea>
                                                <small class="form-text text-danger">{{ ('**Use variables like [[code]], [[order_code]], [[site_name]]**') }}</small>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-2 col-form-label">{{translate('Template ID')}}</label>
                                            <div class="col-md-10">
                                                <input type="text" name="template_id" class="form-control" placeholder="{{translate('Template Id')}}">
                                            </div>
                                        </div>
                                        <div class="form-group mb-3 text-right">
                                            <button type="submit" class="btn btn-success">{{translate('Create Template')}}</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form id="delete-form" method="POST" style="display:none;">
        @csrf
        <input type="hidden" name="_method" value="DELETE">
    </form>
@endsection

@section('script')
    <script>
        function deleteTemplate(id) {
            if(confirm('{{ translate("Are you sure you want to delete this template?") }}')) {
                var form = document.getElementById('delete-form');
                form.action = '{{ url("admin/otp/sms-templates") }}/' + id;
                form.submit();
            }
        }
    </script>
@endsection
