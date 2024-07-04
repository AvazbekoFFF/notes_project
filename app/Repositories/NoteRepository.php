<?php

namespace App\Repositories;

use App\Models\Note;
use App\Repositories\Interfaces\NoteRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class NoteRepository implements NoteRepositoryInterface
{
    protected $note;

    public function __construct(Note $note)
    {
        $this->note = $note;
    }

    /**
     * @return Collection
     */
    public function all(): Collection
    {
        Log::info('Retrieving all notes from database');
        return Cache::remember('notes.all', 60, function () {
            return $this->note->all();
        });
    }

    /**
     * @param Note $note
     * @return Note|null
     */
    public function find(Note $note): ?Note
    {
        Log::info("Retrieving note with ID {$note->id} from database");
        return Cache::remember("notes.{$note->id}", 60, function () use ($note) {
            return $note;
        });
    }

    /**
     * @param array $attributes
     * @return Note
     */
    public function create(array $attributes): Note
    {
        Log::info('Creating a new note', ['attributes' => $attributes]);
        $note = $this->note->create($attributes);
        Cache::forget('notes.all');
        return $note;
    }

    /**
     * @param Note $note
     * @param array $attributes
     * @return Note|null
     */
    public function update(Note $note, array $attributes): ?Note
    {
        Log::info("Updating note with ID {$note->id}", ['attributes' => $attributes]);
        $note->update($attributes);
        Cache::forget("notes.{$note->id}");
        Cache::forget('notes.all');
        return $note;
    }

    /**
     * @param Note $note
     * @return bool
     */
    public function delete(Note $note): bool
    {
        $deleted = $note->delete();
        if ($deleted) {
            Log::info("Deleting note with ID {$note->id}");
            Cache::forget("notes.{$note->id}");
            Cache::forget('notes.all');
        }
        return $deleted;
    }
}
