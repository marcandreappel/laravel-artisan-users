<?php

declare(strict_types=1);

namespace MarcAndreAppel\ArtisanUsers\Console\Commands;

use Illuminate\Console\Command;
use MarcAndreAppel\ArtisanUsers\Facades\ArtisanUsers;

/**
 * Class UserAdd.
 */
class UserAdd extends Command
{

    /** @var string */
    protected $signature = 'user:add';

    /** @var string */
    protected $description = "Create a new user";

    /** @return mixed */
    public function handle()
    {
        $values = collect(
            [
            'name'     => $this->ask("First and last name"),
            'email'    => $this->ask("Email address"),
            'password' => $this->secret("Password"),
            ]
        );

        if ((new ArtisanUsers())->createUser($values)) {
            $this->info("User was successfully created");
        } else {
            $this->error("User creation has failed");
        }
    }
}
