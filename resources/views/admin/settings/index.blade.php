@extends('layouts.admin')
@section('content')
<h2 class="text-xl font-bold mb-6" style="font-family:var(--font-heading); color: var(--muni-red-dark);">Settings</h2>

<div class="grid lg:grid-cols-2 gap-6">
    <form method="POST" action="{{ route('admin.settings.update') }}" class="bg-white rounded-sm shadow-sm p-6 border space-y-4">
        @csrf
        <h3 class="font-bold" style="color: var(--muni-red-dark);">General</h3>
        <div><label class="text-sm font-bold">Site Name</label><input type="text" name="site_name" value="{{ old('site_name', $settings['site_name'] ?? '') }}" class="w-full border-2 rounded-sm px-3 py-2"></div>
        <div><label class="text-sm font-bold">Tagline</label><input type="text" name="site_tagline" value="{{ old('site_tagline', $settings['site_tagline'] ?? '') }}" class="w-full border-2 rounded-sm px-3 py-2"></div>
        <div><label class="text-sm font-bold">Site Email</label><input type="email" name="site_email" value="{{ old('site_email', $settings['site_email'] ?? '') }}" class="w-full border-2 rounded-sm px-3 py-2"></div>
        <div><label class="text-sm font-bold">Domain</label><input type="text" name="site_domain" value="{{ old('site_domain', $settings['site_domain'] ?? '') }}" class="w-full border-2 rounded-sm px-3 py-2" placeholder="news.muni.ac.ug"></div>

        <h3 class="font-bold pt-4" style="color: var(--muni-red-dark);">SEO</h3>
        <div><label class="text-sm font-bold">Meta Title</label><input type="text" name="meta_title" value="{{ old('meta_title', $settings['meta_title'] ?? '') }}" maxlength="100" class="w-full border-2 rounded-sm px-3 py-2"></div>
        <div><label class="text-sm font-bold">Meta Description</label><textarea name="meta_description" rows="2" maxlength="200" class="w-full border-2 rounded-sm px-3 py-2">{{ old('meta_description', $settings['meta_description'] ?? '') }}</textarea></div>

        <button type="submit" class="btn-muni">Save Settings</button>
    </form>

    <div class="space-y-6">
        <form method="POST" action="{{ route('admin.settings.update') }}" class="bg-white rounded-sm shadow-sm p-6 border space-y-4">
            @csrf
            <h3 class="font-bold" style="color: var(--muni-red-dark);">Email (SMTP)</h3>
            <div class="grid grid-cols-2 gap-3">
                <div><label class="text-sm font-bold">Mailer</label><input type="text" name="mail_mailer" value="{{ old('mail_mailer', $settings['mail_mailer'] ?? 'smtp') }}" class="w-full border-2 rounded-sm px-3 py-2"></div>
                <div><label class="text-sm font-bold">Host</label><input type="text" name="mail_host" value="{{ old('mail_host', $settings['mail_host'] ?? '') }}" class="w-full border-2 rounded-sm px-3 py-2"></div>
                <div><label class="text-sm font-bold">Port</label><input type="number" name="mail_port" value="{{ old('mail_port', $settings['mail_port'] ?? '587') }}" class="w-full border-2 rounded-sm px-3 py-2"></div>
                <div><label class="text-sm font-bold">Encryption</label><input type="text" name="mail_encryption" value="{{ old('mail_encryption', $settings['mail_encryption'] ?? 'tls') }}" class="w-full border-2 rounded-sm px-3 py-2"></div>
            </div>
            <div><label class="text-sm font-bold">Username</label><input type="text" name="mail_username" value="{{ old('mail_username', $settings['mail_username'] ?? '') }}" class="w-full border-2 rounded-sm px-3 py-2"></div>
            <div><label class="text-sm font-bold">Password</label><input type="password" name="mail_password" value="{{ old('mail_password', $settings['mail_password'] ?? '') }}" class="w-full border-2 rounded-sm px-3 py-2"></div>
            <div class="grid grid-cols-2 gap-3">
                <div><label class="text-sm font-bold">From Address</label><input type="email" name="mail_from_address" value="{{ old('mail_from_address', $settings['mail_from_address'] ?? '') }}" class="w-full border-2 rounded-sm px-3 py-2"></div>
                <div><label class="text-sm font-bold">From Name</label><input type="text" name="mail_from_name" value="{{ old('mail_from_name', $settings['mail_from_name'] ?? '') }}" class="w-full border-2 rounded-sm px-3 py-2"></div>
            </div>
            <button type="submit" class="btn-muni">Save Email Settings</button>
        </form>

        <form method="POST" action="{{ route('admin.settings.test-email') }}" class="bg-white rounded-sm shadow-sm p-6 border">
            @csrf
            <h3 class="font-bold mb-3" style="color: var(--muni-red-dark);">Test Email</h3>
            <div class="flex gap-2">
                <input type="email" name="test_email" required placeholder="test@example.com" class="flex-1 border-2 rounded-sm px-3 py-2">
                <button type="submit" class="btn-muni text-sm">Send Test</button>
            </div>
            <p class="text-xs text-gray-500 mt-2">Sends via current SMTP config (logs to <code>MAIL_MAILER=log</code> in dev).</p>
        </form>
    </div>
</div>
@endsection
