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

        $this->migrateFreshUsing();

        $now = Carbon::now();

        DB::table('users')->insert([
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => Hash::make($this->faker->password),
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $this->user = DB::table('users')->where('id', '=', 1)->first();
    }

    /** @test */
    public function command_asks_for__email_address()
    {
        $this->artisan('user:edit')
            ->expectsQuestion('Users email address', $this->user->email);
    }

    /** @test */
    public function proposes_three_choices()
    {
        $this->artisan('user:edit')
            ->expectsQuestion('Users email address', $this->user->email)
            ->expectsChoice("Modify", 'email', ['name', "First and last name", 'email', "Email address", 'password', "Password"]);
    }
}
