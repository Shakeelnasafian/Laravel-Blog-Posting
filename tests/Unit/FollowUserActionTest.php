<?php

namespace Tests\Unit\Actions;

use App\Actions\FollowUserAction;
use App\Models\User;
use App\Models\Follow;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class FollowUserActionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Prevent actual authentication, use actingAs instead
    }

    /** @test */
    public function it_allows_a_user_to_follow_another_user()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $this->actingAs($user);

        $action = new FollowUserAction();
        $action->handle($otherUser);

        $this->assertDatabaseHas('follows', [
            'user_id' => $user->id,
            'followeduser' => $otherUser->id,
        ]);
    }

    /** @test */
    public function it_does_not_allow_a_user_to_follow_themselves()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $action = new FollowUserAction();

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('You cannot follow yourself.');

        $action->handle($user);
    }

    /** @test */
    public function it_does_not_allow_a_user_to_follow_the_same_user_twice()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $this->actingAs($user);

        Follow::create([
            'user_id' => $user->id,
            'followeduser' => $otherUser->id,
        ]);

        $action = new FollowUserAction();

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('You are already following this user.');

        $action->handle($otherUser);
    }
}
