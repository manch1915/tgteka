<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();

        return UserResource::collection($users);
    }

    public function store(Request $request)
    {
    }

    public function show(User $user)
    {
    }

    public function update(Request $request, User $user) {
        $moderatorRole = Role::findByName('Moderator');

        if ($user->hasRole('Moderator')) {
            $user->removeRole($moderatorRole);
        } else {
            $user->assignRole($moderatorRole);
        }
        return response()->json(['status' => 'Роль успешно обновлена']);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->json('Пользователь успешно удален');
    }
}
