<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $validated = request()->validate([
            'page' => 'nullable|integer',
            'pageSize' => 'nullable|integer',
        ]);


        $pageSize = request()->input('pageSize', 8);

        if ((int)$pageSize === 0) {
            $pageSize = 99999;
        }

        $users = User::paginate($pageSize);

        return $users;

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $file = $request['image'];

        $name = $file->hashName();
        Storage::putFile('images/users', $file);

        User::create([
            'name' => $request['name'],
            'url' => $name,
        ]);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return $user;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->validated();

        $user->update($data);
        return $user;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

    }
}
