<?php

namespace Tests\Feature;

use App\Models\Note;
use App\Models\User;
use App\Models\Restaurant;
use Tests\AbstractTestCase;

class NoteControllerTest extends AbstractTestCase
{

    private User $user;
    private Restaurant $restaurant;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user  = User::first();
        $this->restaurant  = Restaurant::first();
    }

    public function test_create_note(): void
    {
        $userDesc = 'Note about first user';
        $restaurantDesc = 'Note about first restaurant';

        $response = $this->postJson(
            route('api.notes.store'),
            [
                'notes' => [
                    [
                        'description' => $userDesc,
                        'user_id' => $this->user->id,
                        'restaurant_id' => null,
                    ],
                    [
                        'description' => $restaurantDesc,
                        'user_id' => null,
                        'restaurant_id' => $this->restaurant->id,
                    ],
                ]
            ],
            ['Authorization' => $this->getToken()]
        );

        $response->assertStatus(201);
        $response->assertJsonStructure([
            [
                'user_id',
                'restaurant_id',
                'description'
            ]
        ]);
        $userNote = Note::find(3);
        $this->assertEquals($this->user->id, $userNote->user_id);
        $this->assertEquals(null, $userNote->restaurant_id);
        $this->assertEquals($userDesc, $userNote->description);

        $restaurantNote = Note::find(4);
        $this->assertEquals(null, $restaurantNote->user_id);
        $this->assertEquals($this->restaurant->id, $restaurantNote->restaurant_id);
        $this->assertEquals($restaurantDesc, $restaurantNote->description);

    }

    public function test_create_note_validation(): void
    {
        $response = $this->postJson(
            route('api.notes.store'),
            [
                'notes' => [
                    [
                        'description' => 'notes without assigment',
                    ],
                ]
            ],
            ['Authorization' => $this->getToken()]
        );

        $response->assertStatus(422);

        $response = $this->postJson(
            route('api.notes.store'),
            [
                'notes' => [
                    [
                        'description' => 'notes with both restaurant and user assigment',
                        'user_id' => $this->user->id,
                        'restaurant_id' => $this->restaurant->id,
                    ],
                ]
            ],
            ['Authorization' => $this->getToken()]
        );

        $response->assertStatus(422);
    }

    public function test_index(): void
    {
        $response = $this->getJson(route('api.notes.index'), ['Authorization' => $this->getToken()]);

        $response->assertStatus(200);
    }

    public function test_pagination(): void
    {

        $response = $this->getJson(
            route('api.notes.index', ['limit' => 2]),
            ['Authorization' => $this->getToken()]);
        $note = json_decode($response->getContent())->data[1];
        $response = $this->getJson(
            route('api.notes.index', ['page' => 2, 'limit' => 1]),
            ['Authorization' => $this->getToken()]);
        $secondNote = json_decode($response->getContent())->data[0];

        $this->assertSame($note->id, $secondNote->id);
    }


    public function test_filters(): void
    {
        $response = $this->getJson(
            route('api.notes.index', ['user_id' => $this->user->id]),
            ['Authorization' => $this->getToken()]
        );
        $note = json_decode($response->getContent())->data[0];
        $this->assertSame($note->user_id, $this->user->id);

        $response = $this->getJson(
            route('api.notes.index', ['restaurant_id' => $this->restaurant->id]),
            ['Authorization' => $this->getToken()]
        );
        $note = json_decode($response->getContent())->data[0];
        $this->assertSame($note->restaurant_id, $this->restaurant->id);

    }
}
