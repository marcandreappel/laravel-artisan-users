<?php
declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use MarcAndreAppel\ArtisanUsers\ArtisanUsers;
use MarcAndreAppel\ArtisanUsers\Tests\App\User;
use MarcAndreAppel\ArtisanUsers\Tests\TestCase;

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

        $artisanUser = new ArtisanUsers;

        $user = $artisanUser->createUser($presets);

        $this->assertTrue($user);
        $this->assertDatabaseHas('users', ['email' => $presets['email']]);
    }

    /**
     * @test
     */
    public function throws_exception_without_arguments()
    {
        $this->expectException('ArgumentCountError');

        (new ArtisanUsers)->createUser();
    }

    /**
     * @test
     */
    public function throws_exception_with_wrong_arguments()
    {
        $this->expectException('TypeError');

        (new ArtisanUsers)->createUser(['name' => $this->faker->name]);
    }

    /**
     * @test
     */
    public function returns_false_when_user_exists()
    {
        $this->migrateFreshUsing();

        $presets = collect([
            'name'     => $this->faker->name,
            'email'    => $this->faker->email,
            'password' => $this->faker->password,
        ]);

        Config::set('artisan_users.use_model', User::class);

        (new ArtisanUsers)->createUser($presets);


        $result = (new ArtisanUsers)->createUser($presets);
        $this->assertFalse($result);
    }

    /**
     * @test
     */
    public function adds_user_with_role()
    {
        $this->migrateFreshUsing();

        Config::set('artisan_users.use_model', User::class);
        Config::set('artisan_users.with_roles', true);

        $values = collect([
            'name'     => $this->faker->name,
            'email'    => $this->faker->email,
            'role'     => 'admin',
            'password' => $this->faker->password,
        ]);


        $user = (new ArtisanUsers)->createUser($values);

        $this->assertTrue($user);
        $this->assertDatabaseHas('users', $values->except('password')->toArray());
    }
}
