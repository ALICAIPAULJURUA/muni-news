@extends('layouts.admin')
@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-xl font-bold" style="font-family:var(--font-heading); color: var(--muni-red-dark);">Users</h2>
    <a href="{{ route('admin.users.create') }}" class="btn-muni text-sm"><i class="fa-solid fa-user-plus me-1"></i> New User</a>
</div>

<form method="GET" class="bg-white rounded-sm shadow-sm p-4 mb-4 flex gap-3 items-end border">
    <div class="flex-1">
        <label class="text-xs font-bold uppercase">Search</label>
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Name, username, email..." class="w-full border-2 rounded-sm px-3 py-2 text-sm" style="min-height:44px;">
    </div>
    <button type="submit" class="btn-muni text-sm">Search</button>
</form>

<div class="bg-white rounded-sm shadow-sm border overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-white text-xs uppercase" style="background: var(--muni-red-dark);">
                    <th class="px-4 py-3 text-left" style="border-bottom:3px solid var(--muni-gold);">User</th>
                    <th class="px-4 py-3 text-left" style="border-bottom:3px solid var(--muni-gold);">Role</th>
                    <th class="px-4 py-3 text-left" style="border-bottom:3px solid var(--muni-gold);">Status</th>
                    <th class="px-4 py-3 text-right" style="border-bottom:3px solid var(--muni-gold);">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($users as $user)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            @if($user->avatar)
                                <img src="{{ asset('storage/'.$user->avatar) }}" alt="" class="w-8 h-8 rounded-full object-cover">
                            @else
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold" style="background: var(--muni-red);">{{ strtoupper(substr($user->full_name ?? $user->username,0,1)) }}</div>
                            @endif
                            <div>
                                <div class="font-semibold" style="color: var(--muni-red-dark);">{{ $user->full_name }} <span class="text-xs text-gray-500">({{ $user->username }})</span></div>
                                <div class="text-xs text-gray-500">{{ $user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3"><span class="px-2 py-1 rounded-sm text-xs font-bold" style="background: var(--muni-blue); color:#fff;">{{ $user->getRoleNames()->first() ?? 'no role' }}</span></td>
                    <td class="px-4 py-3">@if($user->is_active)<span class="px-2 py-1 rounded-sm text-xs font-bold bg-green-600 text-white">Active</span> @else <span class="px-2 py-1 rounded-sm text-xs font-bold bg-gray-400 text-white">Inactive</span>@endif</td>
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('admin.users.edit', $user) }}" class="text-xs px-2 py-1 rounded-sm" style="background: var(--muni-blue); color:#fff;">Edit</a>
                        <form method="POST" action="{{ route('admin.users.toggle-active', $user) }}" class="inline">@csrf <button class="text-xs px-2 py-1 rounded-sm border ms-1">{{ $user->is_active ? 'Deactivate' : 'Activate' }}</button></form>
                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE') <button class="text-xs px-2 py-1 rounded-sm bg-red-600 text-white ms-1">Delete</button></form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-4 py-12 text-center text-gray-500">No users.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t">{{ $users->links('pagination::bootstrap-5') }}</div>
</div>
@endsection
