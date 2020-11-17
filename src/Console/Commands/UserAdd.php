<?php
declare(strict_types=1);

namespace MarcAndreAppel\ArtisanUsers\Console\Commands;

use Illuminate\Console\Command;
use MarcAndreAppel\ArtisanUsers\ArtisanUsers;

class UserAdd extends Command
{

    /**
     * @var string 
     */
    protected $signature = 'user:add
                            {--r|role : Whether a role needs to defined}';

    /**
     * @var string 
     */
    protected $description = "Create a new user";

    /**
     * @var string $userModel
     */
    private string $userModel;

    /**
     * @var bool $withRoles
     */
    private bool $withRoles;

    /**
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->withRoles = config('artisan_users.with_roles');
    }

    /**
     * @return mixed
     */
    public function handle()
    {
        $values = collect(
            [
            'name'     => $this->ask("First and last name"),
            'email'    => $this->ask("Email address"),
            'password' => $this->secret("Password"),
            ]
        );

        if ($this->option('role') && $this->withRoles) {
            $values->put(
                'role', $this->choice(
                    "Role",
                    [
                    'user'  => "Standard User",
                    'admin' => "Administrator",
                    ],
                    'user'
                )
            );
        }

        if ((new ArtisanUsers)->createUser($values)) {
            $this->info("User was successfully created");
        } else {
            $this->error("User creation has failed");
        }

    }

}
