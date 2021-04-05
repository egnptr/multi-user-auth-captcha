<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();

        return view('users.index', [
            'users' => $users
        ]);
    }

    public function create()
    {
        abort_if(Gate::denies('admin'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('users.create');
    }

    public function store(StoreUserRequest $request)
    {
        //dd($request);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
            'captcha' => $request->captcha,
        ]);

        return redirect()->route('users');
    }

    public function show($id)
    {
        $user = User::findOrFail($id);

        return view('users.show', [
            'user' => $user
        ]);
    }

    public function edit($id)
    {
        if (Gate::any(['admin', 'editor'])) {
            return view('users.edit', [
                'user' => User::findOrFail($id)
            ]);
        } else {
            abort(403);
        }
    }

    public function update(Request $request, $id)
    {
        //dd($request);
        $user = User::findOrFail($id);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
        ]);

        return redirect()->route('users');
    }

    public function destroy($id)
    {
        abort_if(Gate::denies('admin'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user = User::findOrFail($id);

        $user->delete();

        return back();
    }
}
