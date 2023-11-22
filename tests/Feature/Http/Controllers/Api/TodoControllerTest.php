<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Todo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TodoControllerTest extends TestCase
{
    /** @test */
    public function it_should_return_a_collection_of_todos(): void
    {
        $this->getJson('/api/todos')
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'user_id',
                        'title',
                        'status',
                        'created_at',
                        'updated_at',
                    ]
                ]
            ]);
    }

    /** @test */
    public function it_should_return_a_show_todo(): void
    {
        $user = User::factory()->createOne();
        $todo =  Todo::factory()->create(
            [
                'user_id' => $user->id,
                'title' => 'Test',
                'status' => true,
            ]
       );

       $this->getJson("/api/todos/{$todo->id}")
           ->assertOk()
           ->assertJsonStructure([
               'data' => [
                   'id',
                   'user_id',
                   'title',
                   'status',
                   'created_at',
                   'updated_at',
               ]
           ]);
    }

    /** @test */
    public function it_should_create_a_new_todo(): void
    {
        $user = User::factory()->createOne();
        $data = [
            'user_id' => $user->id,
            'title' => 'Test',
            'status' => true,
        ];

        $this->postJson('/api/todos', $data)
            ->assertCreated();

        $this->assertDatabaseHas('todos', $data);
    }

    /** @test */
    public function it_should_update_a_todo(): void
    {
        $user = User::factory()->createOne();
        $todo =  Todo::factory()->create(
            [
                'user_id' => $user->id,
                'title' => 'Test One',
                'status' => true,
            ]
        );

        $data = [
            'user_id' => $user->id,
            'title' => 'Test New',
            'status' => false,
        ];

        $this->putJson("/api/todos/{$todo->id}", $data)
            ->assertNoContent();

        $this->assertDatabaseHas('todos', $data);
    }

    /** @test */
    public function it_should_delete_a_todo(): void
    {
        $user = User::factory()->createOne();
        $todo =  Todo::factory()->create(
            [
                'user_id' => $user->id,
                'title' => 'Test One',
                'status' => true,
            ]
        );

        $this->deleteJson("/api/todos/{$todo->id}")
            ->assertNoContent();

        $this->assertDatabaseMissing('todos', $todo->toArray());
    }
}
