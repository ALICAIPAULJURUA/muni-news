<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function index(): View
    {
        $settings = Setting::pluck('value','key')->toArray();
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'site_name' => ['nullable','string','max:255'],
            'site_tagline' => ['nullable','string','max:255'],
            'site_email' => ['nullable','email'],
            'site_domain' => ['nullable','string'],
            'meta_title' => ['nullable','string','max:100'],
            'meta_description' => ['nullable','string','max:200'],
            'mail_mailer' => ['nullable','string'],
            'mail_host' => ['nullable','string'],
            'mail_port' => ['nullable','integer'],
            'mail_username' => ['nullable','string'],
            'mail_password' => ['nullable','string'],
            'mail_encryption' => ['nullable','string'],
            'mail_from_address' => ['nullable','email'],
            'mail_from_name' => ['nullable','string'],
        ]);

        foreach($request->except('_token') as $key=>$value){
            if($value===null) continue;
            Setting::updateOrCreate(['key'=>$key], ['value'=>$value]);
        }

        // If mail settings provided, optionally update .env? For now just store in settings
        return back()->with('success','Settings updated.');
    }

    public function testEmail(Request $request): RedirectResponse
    {
        $request->validate(['test_email'=>['required','email']]);
        try {
            Mail::raw('Test email from Muni University News Portal - SMTP configuration is working.', function($msg) use($request){
                $msg->to($request->test_email)->subject('Muni News SMTP Test');
            });
            return back()->with('success','Test email sent to '.$request->test_email);
        } catch(\Exception $e){
            return back()->with('error','Failed to send: '.$e->getMessage());
        }
    }
}
