<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserService
{
    public function search($search)
    {
        return User::query()
            ->whereLike('name', "%$search%")
            ->orWhereLike('username', "%$search%")
            ->take(3)
            ->get();
    }
}
