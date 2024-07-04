<?php

namespace App\Repositories\Interfaces;


use App\Models\Note;
use Illuminate\Database\Eloquent\Collection;

interface NoteRepositoryInterface
{
    public function all(): Collection;

    public function find(Note $note): ?Note;

    public function create(array $attributes): Note;

    public function update(Note $note, array $attributes): ?Note;

    public function delete(Note $note): bool;
}
