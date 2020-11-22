<?php
declare(strict_types=1);

namespace MarcAndreAppel\ArtisanUsers\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use MarcAndreAppel\ArtisanUsers\Tests\App\User;
use MarcAndreAppel\ArtisanUsers\Tests\TestCase;

class UserEditTest extends TestCase
{

    use RefreshDatabase;

    /**
     * @var \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder|object|null
     */
    public $user;

    public function setUp(): void
    {
        parent::setUp();

        $now = Carbon::now();

        DB::table('users')->insert([
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => Hash::make($this->faker->password),
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $this->user = DB::table('users')->where('id', '=', 1)->first();

        Config::set('artisan_users.use_model', User::class);

    }

    /** @test  */
    public function returns_error_if_no_user_found()
    {
        $this->artisan('user:edit')
            ->expectsQuestion("Users email address", $this->faker->email)
            ->expectsOutput("User doesn't exist. use 'artisan user:add' instead.");
    }

    /** @test */
    public function proposes_four_choices_and_cancels()
    {
        $this->artisan('user:edit')
            ->expectsQuestion('Users email address', $this->user->email)
            ->expectsChoice("Choose from the following to edit", 'cancel',
                ['Email', 'Name', 'Password', 'Cancel'])
            ->expectsOutput("As you wish.");
    }

    /** @test  */
    public function changes_email_address()
    {
        $email = $this->faker->email;

        $this->artisan('user:edit')
            ->expectsQuestion('Users email address', $this->user->email)
            ->expectsChoice("Choose from the following to edit", 'email',
                ['Email', 'Name', 'Password', 'Cancel'])
            ->expectsQuestion("New email address", $email)
            ->expectsOutput("Email address was modified.");
    }

    /** @test */
    public function returns_error_when_trying_to_change_to_existing_email_address()
    {
        $now = Carbon::now();
        DB::table('users')->insert([
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => Hash::make($this->faker->password),
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        $secondUser = DB::table('users')->where('id', '=', 2)->first();

        $this->artisan('user:edit')
            ->expectsQuestion('Users email address', $this->user->email)
            ->expectsChoice("Choose from the following to edit", 'email',
                ['Email', 'Name', 'Password', 'Cancel'])
            ->expectsQuestion("New email address", $secondUser->email)
            ->expectsOutput("Email address already on record!");
    }

    /** @test  */
    public function changes_name()
    {
        $this->artisan('user:edit')
            ->expectsQuestion('Users email address', $this->user->email)
            ->expectsChoice("Choose from the following to edit", 'name',
                ['Email', 'Name', 'Password', 'Cancel'])
            ->expectsQuestion("New first and last name", $this->faker->name)
            ->expectsOutput("First and last name were modified.");
    }

    /** @test */
    public function changes_password()
    {
        $this->artisan('user:edit')
        ->expectsQuestion('Users email address', $this->user->email)
        ->expectsChoice("Choose from the following to edit", 'password',
            ['Email', 'Name', 'Password', 'Cancel'])
        ->expectsQuestion("New password", $this->faker->name)
        ->expectsOutput("Password was modified.");
    }
}
