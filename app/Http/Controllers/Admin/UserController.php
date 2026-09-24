<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::with('roles')->latest();
        if($q=$request->input('q')){
            $query->where(fn($qq)=>$qq->where('full_name','like',"%{$q}%")->orWhere('username','like',"%{$q}%")->orWhere('email','like',"%{$q}%"));
        }
        $users = $query->paginate(15)->withQueryString();
        $roles = Role::all();
        return view('admin.users.index', compact('users','roles'));
    }

    public function create(): View { $roles=Role::all(); return view('admin.users.create', compact('roles')); }

    public function store(UserRequest $request): RedirectResponse
    {
        $data=$request->validated();
        $role=$data['role']; unset($data['role']);
        $data['password']=Hash::make($data['password']);
        $user=User::create($data);
        $user->assignRole($role);
        return redirect()->route('admin.users.index')->with('success','User created.');
    }

    public function edit(User $user): View { $roles=Role::all(); return view('admin.users.edit', compact('user','roles')); }

    public function update(UserRequest $request, User $user): RedirectResponse
    {
        $data=$request->validated();
        $role=$data['role']; unset($data['role']);
        if(empty($data['password'])) unset($data['password']); else $data['password']=Hash::make($data['password']);
        $user->update($data);
        $user->syncRoles([$role]);
        return redirect()->route('admin.users.index')->with('success','User updated.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if($user->id===auth()->id()) return back()->with('error','Cannot delete yourself.');
        $user->delete();
        return back()->with('success','User deleted.');
    }

    public function toggleActive(User $user): RedirectResponse
    {
        $user->update(['is_active'=>! $user->is_active]);
        return back()->with('success','User status updated.');
    }
}
