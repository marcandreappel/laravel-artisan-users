<?php

declare(strict_types=1);

namespace MarcAndreAppel\ArtisanUsers\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use MarcAndreAppel\ArtisanUsers\Facades\ArtisanUsers;
use MarcAndreAppel\ArtisanUsers\Tests\App\User;
use MarcAndreAppel\ArtisanUsers\Tests\TestCase;

/**
 * Class UserAddTest
 *
 * @package MarcAndreAppel\ArtisanUsers\Tests\Feature
 */
class UserAddTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function creates_standard_user_account()
    {
        $this->migrateUsing();

        $presets = [
            'name'     => $this->faker->name,
            'email'    => $this->faker->email,
            'password' => $this->faker->password,
        ];

        Config::set('artisan_users.use_model', User::class);

        $this->artisan('user:add')
            ->expectsQuestion('Email address', $presets['email'])
            ->expectsQuestion('First and last name', $presets['name'])
            ->expectsQuestion('Password', $presets['password'])
            ->expectsOutput("User was successfully created.")
            ->assertExitCode(0);

        $this->assertDatabaseHas('users', ['email' => $presets['email']],);
    }

    /** @test */
    public function asks_for_email_first_and_verifies_existence_of_account()
    {
        $values = collect([
            'name'     => $this->faker->name,
            'email'    => $this->faker->email,
            'password' => $this->faker->password,
        ]);

        Config::set('artisan_users.use_model', User::class);

        ArtisanUsers::createUser($values);

        $this->artisan('user:add')
            ->expectsQuestion('Email address', $values->get('email'))
            ->expectsOutput("User exists. Use 'artisan user:edit' instead.");
    }
}
