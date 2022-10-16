<?php

namespace App\Service;

use App\Models\Note;

class NoteCreateService
{
    public function create(array $data): array
    {
        $notes = [];

        foreach ($data as $datum) {
            $notes[] = Note::create($datum);
        }

        return $notes;
    }
}
