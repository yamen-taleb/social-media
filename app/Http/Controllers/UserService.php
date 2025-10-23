<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserService
{
    public function search(string $search, int $limit = null)
    {
        $query = User::query()
            ->whereLike('name', "%$search%")
            ->orWhereLike('username', "%$search%");

        if ($limit) {
            return $query->take($limit)->get();
        }

        return $query;
    }
}
