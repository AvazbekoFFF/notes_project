<?php

namespace Tests\Feature\Note;

use App\Models\Note;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class NoteTest extends TestCase
{
    use DatabaseMigrations;

    private function login()
    {
        return $this->post('api/register', [
            'email' => 'test@test.bro.com',
            'password' => 'password',
            'name' => 'test-name'
        ]);
    }

    /**
     * A basic feature test example.
     */
    public function test_default_page(): void
    {
        $response = $this->get('/');
        $response->assertOk();
    }

    /**
     * @group Note
     * get | notes
     */
    public function test_note_index()
    {
        User::factory()->count(5)->create();
        $notes = Note::factory()->count(10)->create();
        $token = $this->login()['access_token'];
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->get(route('notes.index'));
        $response->assertStatus(200);
        foreach ($notes as $note) {
            $response->assertSee($note->title);
        }
    }

    /**
     * @group Note
     * get | note
     */
    public function test_note_find()
    {
        User::factory()->count(5)->create();
        Note::factory()->count(10)->create();
        $token = $this->login()['access_token'];
        $user = User::where('email', 'test@test.bro.com')->first();
        $myNote = Note::factory()->create(['user_id' => $user->id]);
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->get(route('notes.show', $myNote->id));
        $response->assertStatus(200);
        $response->assertSee($myNote->title);
    }

    /**
     * @group Note
     * post | note
     */
    public function test_create_note()
    {
        $token = $this->login()['access_token'];
        $newData = ['title' => 'hello', 'body' => 'description'];
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->post(route('notes.store', $newData));
        $response->assertStatus(200);
    }

    /**
     * @group Note
     * update | note
     */
    public function test_note_update()
    {
        User::factory()->count(5)->create();
        $token = $this->login()['access_token'];
        $user = User::where('email', 'test@test.bro.com')->first();
        $note = Note::factory()->create(['user_id' => $user->id]);
        $newData = [
            'title' => 'Updated Title',
            'body' => 'Updated Body',
        ];
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->json('put', route('notes.update', ['note' => $note->id]), $newData);
        $response->assertStatus(200);
    }

    /**
     * @group Note
     * delete | note
     */
    public function test_note_delete()
    {
        $token = $this->login()['access_token'];
        $newData = ['title' => 'hello', 'body' => 'description'];
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->post(route('notes.store', $newData));
        $response->assertStatus(200);
        $note = $response->json()['note']['id'];
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->delete(route('notes.destroy', $note));
        $response->assertOk();
    }
}
