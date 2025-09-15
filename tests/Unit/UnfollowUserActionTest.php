<?php

namespace Tests\Unit\Actions;

use App\Actions\UnfollowUserAction;
use App\Models\User;
use App\Models\Follow;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;


class UnfollowUserActionTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function it_deletes_the_follow_record_when_user_unfollows()
    {
        $authUser = User::factory()->create();
        $otherUser = User::factory()->create();

        Auth::login($authUser);

        $follow = Follow::create([
            'user_id' => $authUser->id,
            'followeduser' => $otherUser->id,
        ]);

        $this->assertDatabaseHas('follows', [
            'user_id' => $authUser->id,
            'followeduser' => $otherUser->id,
        ]);

        $action = new UnfollowUserAction();
        $action->handle($otherUser);

        $this->assertDatabaseMissing('follows', [
            'user_id' => $authUser->id,
            'followeduser' => $otherUser->id,
        ]);
    }

    /** @test */
    public function it_does_nothing_if_no_follow_record_exists()
    {
        $authUser = User::factory()->create();
        $otherUser = User::factory()->create();

        Auth::login($authUser);

        $this->assertDatabaseMissing('follows', [
            'user_id' => $authUser->id,
            'followeduser' => $otherUser->id,
        ]);

        $action = new UnfollowUserAction();
        $action->handle($otherUser);

        // Still missing, nothing should be created or deleted
        $this->assertDatabaseMissing('follows', [
            'user_id' => $authUser->id,
            'followeduser' => $otherUser->id,
        ]);
    }
}
