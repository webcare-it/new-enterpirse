<?php

namespace App\Http\Controllers\Admin;

use App\Models\OtpConfiguration;
use App\Models\SmsTemplate;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class OtpController extends Controller
{
    public function activation()
    {
        return view('backend.oto_configurations.activation');
    }

    public function sms_templates()
    {
        $sms_templates = SmsTemplate::all();
        return view('backend.oto_configurations.sms_templates', compact('sms_templates'));
    }

    public function sms_template_store(Request $request)
    {
        $request->validate([
            'identifier' => 'required|unique:sms_templates,identifier',
            'sms_body'   => 'required',
        ]);

        SmsTemplate::create([
            'identifier' => $request->identifier,
            'sms_body'   => $request->sms_body,
            'template_id'=> $request->template_id,
            'status'     => 1,
        ]);

        Artisan::call('cache:clear');
        flash(translate("Template created successfully"))->success();
        return back();
    }

    public function sms_template_update(Request $request, $id)
    {
        $sms_template = SmsTemplate::findOrFail($id);
        $sms_template->sms_body = $request->body;
        $sms_template->template_id = $request->template_id;
        $sms_template->status = $request->has('status') ? 1 : 0;
        $sms_template->save();

        Artisan::call('cache:clear');
        flash(translate("Template updated successfully"))->success();
        return back();
    }

    public function sms_template_destroy($id)
    {
        SmsTemplate::findOrFail($id)->delete();

        Artisan::call('cache:clear');
        flash(translate("Template deleted successfully"))->success();
        return back();
    }

    public function update_activation(Request $request)
    {
        $config = OtpConfiguration::where('type', 'active_provider')->first();
        if ($config) {
            $config->value = $request->value;
            $config->save();
        } else {
            OtpConfiguration::create([
                'type'  => 'active_provider',
                'value' => $request->value,
            ]);
        }

        Artisan::call('cache:clear');
        return '1';
    }
}
