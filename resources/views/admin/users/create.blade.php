@extends('layouts.admin')
@section('content')
<h2 class="text-xl font-bold mb-6" style="font-family:var(--font-heading); color: var(--muni-red-dark);">Create User</h2>
<form method="POST" action="{{ route('admin.users.store') }}" class="bg-white rounded-sm shadow-sm p-6 border space-y-4 max-w-2xl">
@csrf
<div class="grid grid-cols-2 gap-4">
    <div><label class="text-sm font-bold">Username *</label><input type="text" name="username" value="{{ old('username') }}" required class="w-full border-2 rounded-sm px-3 py-2" style="min-height:44px;"></div>
    <div><label class="text-sm font-bold">Full Name *</label><input type="text" name="full_name" value="{{ old('full_name') }}" required class="w-full border-2 rounded-sm px-3 py-2" style="min-height:44px;"></div>
</div>
<div><label class="text-sm font-bold">Email *</label><input type="email" name="email" value="{{ old('email') }}" required class="w-full border-2 rounded-sm px-3 py-2"></div>
<div class="grid grid-cols-2 gap-4">
    <div><label class="text-sm font-bold">Password *</label><input type="password" name="password" required class="w-full border-2 rounded-sm px-3 py-2"></div>
    <div><label class="text-sm font-bold">Confirm Password *</label><input type="password" name="password_confirmation" required class="w-full border-2 rounded-sm px-3 py-2"></div>
</div>
<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="text-sm font-bold">Role *</label>
        <select name="role" required class="w-full border-2 rounded-sm px-3 py-2 bg-white" style="min-height:44px;">
            @foreach($roles as $role)<option value="{{ $role->name }}">{{ $role->name }}</option>@endforeach
        </select>
    </div>
    <div class="flex items-end">
        <label class="flex items-center gap-2"><input type="checkbox" name="is_active" value="1" checked> <span class="text-sm">Active</span></label>
    </div>
</div>
<button type="submit" class="btn-muni">Create User</button>
</form>
@endsection
