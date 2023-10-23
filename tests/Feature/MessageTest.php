<?php

namespace Tests\Feature;

use App\Models\Message;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MessageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function メッセージ一覧表示のテスト(): void
    {
        Message::create(['body' => 'Hello world']);
        Message::create(['body' => 'Hello Laravel']);
        $this->get('messages')
            ->assertOk()
            ->assertSeeInOrder([
                'Hello world',
                'Hello Laravel',
            ]);
    }
}
