<?php

namespace App\Repository;

use App\Constant\PaginationConstant;
use App\Models\Note;
use Illuminate\Pagination\LengthAwarePaginator;

class NoteRepository
{
    public function getNotes(
        ?int $limit,
        ?int $userId,
        ?int $restaurantId
    ): LengthAwarePaginator
    {
        $notes = Note::select();

        if ($userId) {
            $notes->where('user_id', $userId);
        }

        if ($restaurantId) {
            $notes->where('restaurant_id', $restaurantId);
        }

        return $notes->paginate($limit ?? PaginationConstant::DEFAULT_LIMIT);
    }
}
