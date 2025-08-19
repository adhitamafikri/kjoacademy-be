<?php

namespace App\Http\Repositories;

use App\Models\User;

class UserRepository
{
    public function findByPhone(string $phone)
    {
        return User::where('phone', $phone)->first();
    }

    public function create(array $data)
    {
        return User::create($data);
    }

    public function update(string $id, array $data)
    {
        $user = User::where('id', $id)->first();
        $user->update($data);
        return $user;
    }
}
