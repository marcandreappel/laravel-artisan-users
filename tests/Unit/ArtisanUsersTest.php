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

    public function setUp(): void
    {
        parent::setUp();

        Config::set('artisan-users.use-model', User::class);
    }

    /**
     * @test
     */
    public function create_user()
    {
        $presets = collect([
            'name'     => $this->faker->name,
            'email'    => $this->faker->email,
            'password' => $this->faker->password,
        ]);

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

        $this->assertTrue(ArtisanUsers::userExists($user->email));
        $this->assertFalse(ArtisanUsers::userExists($this->faker->email));
    }

    /** @test */
    public function updates_users_email()
    {
        $email = $this->faker->email;
        $newEmail = $this->faker->email;

        $now = Carbon::now();
        DB::table('users')->insert([
            'name'       => $this->faker->name,
            'email'      => $email,
            'password'   => Hash::make($this->faker->password),
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $user = ArtisanUsers::updateUser($email);
        $this->assertInstanceOf(\MarcAndreAppel\ArtisanUsers\ArtisanUsers::class, $user);

        $user->setEmail($newEmail);
        $data = DB::table('users')->where('id', '=', 1)->first();

        $this->assertEquals($data->email, $newEmail);
    }

    /** @test */
    public function updates_users_name()
    {
        $email = $this->faker->email;
        $newName = $this->faker->name;

        $now = Carbon::now();
        DB::table('users')->insert([
            'name'       => $this->faker->name,
            'email'      => $email,
            'password'   => Hash::make($this->faker->password),
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $user = ArtisanUsers::updateUser($email);

        $user->setName($newName);
        $data = DB::table('users')->where('id', '=', 1)->first();

        $this->assertEquals($data->name, $newName);
    }

    /** @test  */
    public function updates_users_password()
    {
        $email = $this->faker->email;
        $oldPassword = Hash::make($this->faker->password);
        $newPassword = $this->faker->password;

        $now = Carbon::now();
        DB::table('users')->insert([
            'name'       => $this->faker->name,
            'email'      => $email,
            'password'   => $oldPassword,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $data = DB::table('users')->where('id', '=', 1)->first();
        $this->assertEquals($oldPassword, $data->password);

        $user = ArtisanUsers::updateUser($email);

        $user->setPassword($newPassword);
        $data = DB::table('users')->where('id', '=', 1)->first();

        $this->assertNotEquals($data->password, $oldPassword);
    }
}
