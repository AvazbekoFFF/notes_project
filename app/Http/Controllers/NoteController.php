<?php

namespace App\Http\Controllers;

use App\Http\Requests\Note\NoteCreateRequest;
use App\Http\Requests\Note\NoteUpdateRequest;
use App\Http\Resources\Note\NoteResource;
use App\Models\Note;
use App\Repositories\Interfaces\NoteRepositoryInterface;
use Illuminate\Http\JsonResponse;

class NoteController extends Controller
{

    protected $noteRepository;

    public function __construct(NoteRepositoryInterface $noteRepository)
    {
        $this->noteRepository = $noteRepository;
    }

    public function index(): JsonResponse
    {
        $notes = $this->noteRepository->all();
        return response()->json([
            'message' => 'Success',
            'notes' => NoteResource::collection($notes)
        ]);
    }

    public function store(NoteCreateRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $note = $this->noteRepository->create($validated);
        return response()->json([
            'message' => 'Success',
            'note' => new NoteResource($note)
        ]);
    }

    public function show(Note $note): JsonResponse
    {
        $this->noteRepository->find($note);
        return response()->json($note);
    }

    public function update(NoteUpdateRequest $request, Note $note): JsonResponse
    {
        $this->authorize('update-note', $note);
        $updated_note = $this->noteRepository->update($note, $request->validated());
        return response()->json([
            'message' => 'Success',
            'note' => new NoteResource($updated_note)
        ]);
    }

    public function destroy(Note $note): JsonResponse
    {
        $this->authorize('delete-note', $note);
        $deleted = $this->noteRepository->delete($note);
        if ($deleted) {
            return response()->json(['message' => 'Note deleted successfully']);
        }

        return response()->json(['error' => 'Failed to delete the note']);
    }
}
