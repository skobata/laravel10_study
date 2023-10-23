<?php

namespace Tests\Feature\Auth\Admin;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    private Admin $admin;

    public function setUp(): void
    {
        parent::setUp();
        $this->admin = Admin::factory()->create([
            'login_id' => 'hoge',
            'password' => Hash::make('hogehoge'),
        ]);

    }

    /**
     * @test
     */
    public function ログイン画面の表示(): void
    {
        $this->get(route('admin.create'))->assertOk();
    }

    /**
     * @test
     */
    public function ログイン成功(): void
    {
        $this->post(route('admin.store'), [
            'login_id' => 'hoge',
            'password' => 'hogehoge',
        ])->assertRedirect(route('book.index'));

        $this->assertAuthenticatedAs($this->admin, 'admin');
    }

    /**
     * @test
     */
    public function ログイン失敗(): void
    {
        $this->from(route('admin.store'))
            ->post(route('admin.store'), [
                'login_id' => 'fuga',
                'password' => 'hogehoge',
            ])->assertRedirect(route('admin.create'))
            ->assertInvalid(['login_id' => 'These credentials do not']);

        $this->from(route('admin.store'))
            ->post(route('admin.store'), [
                'login_id' => 'hoge',
                'password' => 'fugafuga',
            ])->assertRedirect(route('admin.create'))
            ->assertInvalid(['login_id' => 'These credentials do not']);

        $this->assertGuest('admin');
    }

    /**
     * @test
     */
    public function バリデーション(): void
    {
        $url = route('admin.store');

        $this->from(route('admin.create'))
            ->post($url, ['login_id' => ''])
            ->assertRedirect(route('admin.create'));

        $this->post($url, ['login_id' => ''])
            ->assertInvalid(['login_id' => 'login id は必須']);

        $this->post($url, ['login_id' => 'a'])
            ->assertValid('login_id');

        $this->post($url, ['password' => ''])
            ->assertInvalid(['password' => 'password は必須']);

        $this->post($url, ['password' => 'a'])
            ->assertValid('password');
    }
}
