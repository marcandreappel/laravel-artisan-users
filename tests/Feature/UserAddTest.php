<?php
declare(strict_types=1);

namespace MarcAndreAppel\ArtisanUsers\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use MarcAndreAppel\ArtisanUsers\ArtisanUsers;
use MarcAndreAppel\ArtisanUsers\Tests\Fake\User;
use MarcAndreAppel\ArtisanUsers\Tests\TestCase;

class UserAddTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function creates_standard_user_account()
    {
        $this->migrateFreshUsing();

        $presets = [
            'name'     => $this->faker->name,
            'email'    => $this->faker->email,
            'password' => $this->faker->password,
        ];

        Config::set('artisan_users.use_model', User::class);

        $this->artisan('user:add')
            ->expectsQuestion('First and last name', $presets['name'])
            ->expectsQuestion('Email address', $presets['email'])
            ->expectsQuestion('Password', $presets['password'])
            ->assertExitCode(0);

        $this->assertDatabaseHas('users', ['email' => $presets['email']],);
    }

    /** @test */
    public function creates_role_based_user_account()
    {
        $this->migrateFreshUsing();

        $presets = [
            'name'     => $this->faker->name,
            'email'    => $this->faker->email,
            'role'     => 'admin',
            'password' => $this->faker->password,
        ];

        Config::set('artisan_users.use_model', User::class);
        Config::set('artisan_users.with_roles', true);

        $this->artisan('user:add --role')
            ->expectsQuestion('First and last name', $presets['name'])
            ->expectsQuestion('Email address', $presets['email'])
            ->expectsQuestion('Password', $presets['password'])
            ->expectsChoice('Role', $presets['role'], ['user', "Standard User", 'admin', "Administrator"])
            ->assertExitCode(0);

        $this->assertDatabaseHas('users', ['email' => $presets['email']]);
    }

    /** @test */
    public function returns_error_message_when_wrong_data()
    {
        $this->migrateFreshUsing();

        $values = collect([
            'name'     => $this->faker->name,
            'email'    => $this->faker->email,
            'role'     => 'admin',
            'password' => $this->faker->password,
        ]);

        Config::set('artisan_users.use_model', User::class);

        (new ArtisanUsers)->createUser($values);

        $this->assertDatabaseHas('users', ['email' => $values->get('email')]);

        $this->artisan('user:add')
            ->expectsQuestion('First and last name', $values->get('name'))
            ->expectsQuestion('Email address', $values->get('email'))
            ->expectsQuestion('Password', $values->get('password'))
            ->expectsOutput("User creation has failed");
    }

    /** @test */
    public function adds_default_role_when_roles_active_but_not_asked()
    {
        $this->migrateFreshUsing();

        $presets = [
            'name'     => $this->faker->name,
            'email'    => $this->faker->email,
            'role'     => 'user',
            'password' => $this->faker->password,
        ];

        Config::set('artisan_users.use_model', User::class);
        Config::set('artisan_users.with_roles', true);

        $this->artisan('user:add')
            ->expectsQuestion('First and last name', $presets['name'])
            ->expectsQuestion('Email address', $presets['email'])
            ->expectsQuestion('Password', $presets['password'])
            ->assertExitCode(0);

        $this->assertDatabaseHas('users', ['email' => $presets['email'], 'role' => $presets['role']]);
    }
}
