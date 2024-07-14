<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $pageSize = $request->input('pageSize', 15);

        $query = User::withCount([ 'channels', 'orders', 'patterns']);

        $searchParams = $request->all();

        $searchableFields = [
            'username',
            'email',
            'mobile_number',
            'balance',
        ];

        foreach ($searchableFields as $field) {
            if (isset($searchParams[$field])) {
                $query->where($field, 'LIKE', '%' . $searchParams[$field] . '%');
            }
        }

        $users = $query->paginate($pageSize);

        $users->map(function ($user) {
            $user->is_moderator = $user->hasRole('Moderator');
            return $user;
        });

        return response()->json($users);
    }

    public function store(Request $request)
    {
    }

    public function show(User $user)
    {
    }
    public function edit(User $user)
    {
        return inertia('Admin/UsersShow', ['user' => $user]);
    }

    public function update(UserUpdateRequest $request, User $user) {
        if ($request->has('moderator_update')){
            $moderatorRole = Role::findByName('Moderator');

            if ($user->hasRole('Moderator')) {
                $user->removeRole($moderatorRole);
            } else {
                $user->assignRole($moderatorRole);
            }

            return response()->json(['status' => 'Роль успешно обновлена']);
        }

        $inputData = $request->validated();

        $user->update($inputData);
        return response()->json(['status' => 'Пользователь успешно обновлен']);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->json('Пользователь успешно удален');
    }
}
