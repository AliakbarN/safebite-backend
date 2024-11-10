<?php

namespace App\Repositories;

use App\Models\UserHistory;

class UserHistoryRepository
{
    public function store(int $userId): UserHistory
    {
        return UserHistory::firstOrCreate([
            'user_id' => $userId,
        ]);
    }

    public function getHistory(int $userId): UserHistory
    {
        return UserHistory::where('user_id')->first();
    }
}
