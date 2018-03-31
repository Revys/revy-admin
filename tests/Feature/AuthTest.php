<?php

namespace Revys\RevyAdmin\Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Revys\RevyAdmin\Tests\Languages;
use Revys\RevyAdmin\App\User;
use Revys\RevyAdmin\Tests\TestCase;
use function GuzzleHttp\Psr7\uri_for;

class AuthTest extends  TestCase
{
    use DatabaseMigrations, Languages;

    public function setUp()
    {
        parent::setUp();

        self::mockLocale();
    }

    /** @test */
    public function admin_panel_can_be_accessed()
    {
        $this->withExceptionHandling();

        $route = route('admin::core');

        $this->get($route)->assertRedirect();

        self::signIn(null, false);

        $this->get($route)->assertStatus(403);

        self::signIn(null, true);

        $this->get($route)->assertSuccessful();
    }

    /** @test */
    public function authenticated_user_cant_visit_login_page()
    {
        $this->withExceptionHandling();

        self::signIn();

        $this->get(route('admin::login::login-form'))->assertRedirect();
    }

    /** @test */
    public function admin_user_can_sign_in()
    {
        $user = create(User::class, [
            'admin' => true
        ]);

        // Using Email
        $this->postJson(route('admin::login::signin'), [
            'id' => $user->email,
            'password' => 'secret'
        ])
            ->assertSuccessful()
            ->assertJsonMissing(['error']);

        // Using Login
        $this->postJson(route('admin::login::signin'), [
            'id' => $user->login,
            'password' => 'secret'
        ])
            ->assertSuccessful()
            ->assertJsonMissing(['error']);
    }

    /** @test */
    public function not_admin_user_can_not_sign_in()
    {
        $user = create(User::class, [
            'admin' => false
        ]);

        $this->postJson(route('admin::login::signin'), [
            'id' => $user->email,
            'password' => 'secret'
        ])
            ->assertStatus(403);
    }

    /** @test */
    public function user_is_redirected_to_signin_page()
    {
        $path = uri_for(route('admin::list', 'admin_menu'))->getPath();

        $this->get($path)->assertRedirect(route('admin::login::login-form', ['redirect' => $path]));

        $path = route('admin::login::login-form');

        $this->get($path)->assertSuccessful();
    }
}