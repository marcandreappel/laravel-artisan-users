<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use MarcAndreAppel\ArtisanUsers\Facades\ArtisanUsers;
use MarcAndreAppel\ArtisanUsers\Tests\App\User;
use MarcAndreAppel\ArtisanUsers\Tests\TestCase;

/**
 * Class ArtisanUsersTest
 *
 * @package MarcAndreAppel\ArtisanUsers\Tests
 */
class ArtisanUsersTest extends TestCase
{

    use RefreshDatabase;

    /**
     * @test
     */
    public function create_user()
    {
        $this->migrateUsing();

        $presets = collect([
            'name'     => $this->faker->name,
            'email'    => $this->faker->email,
            'password' => $this->faker->password,
        ]);

        Config::set('artisan_users.use_model', User::class);

        $user = ArtisanUsers::createUser($presets);

        $this->assertTrue($user);
        $this->assertDatabaseHas('users', ['email' => $presets['email']]);
    }

    /**
     * @test
     */
    public function throws_exception_without_arguments()
    {
        $this->expectException('ArgumentCountError');

        ArtisanUsers::createUser();
    }

    /**
     * @test
     */
    public function throws_exception_with_wrong_arguments()
    {
        $this->expectException('TypeError');

        ArtisanUsers::createUser(['email' => $this->faker->email]);
    }

    /**
     * @test
     */
    public function returns_false_when_user_exists()
    {
        $presets = collect([
            'name'     => $this->faker->name,
            'email'    => $this->faker->email,
            'password' => $this->faker->password,
        ]);

        Config::set('artisan_users.use_model', User::class);

        ArtisanUsers::createUser($presets);

        $result = ArtisanUsers::createUser($presets);
        $this->assertFalse($result);
    }

    /**
     * @test
     */
    public function it_can_verify_if_email_exists()
    {
        $email = $this->faker->email;

        $now = Carbon::now();
        DB::table('users')->insert([
            'name'       => $this->faker->name,
            'email'      => $email,
            'password'   => Hash::make($this->faker->password),
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        $user = DB::table('users')->where('id', '=', 1)->first();

        Config::set('artisan_users.use_model', User::class);

        $this->assertTrue(ArtisanUsers::userExists($user->email));
        $this->assertFalse(ArtisanUsers::userExists($this->faker->email));
    }
}
