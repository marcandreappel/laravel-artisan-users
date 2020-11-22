<?php

declare(strict_types=1);

namespace MarcAndreAppel\ArtisanUsers\Console\Commands;

use Illuminate\Console\Command;
use MarcAndreAppel\ArtisanUsers\Facades\ArtisanUsers;

/**
 * Class UserAdd
 * 
 * @package MarcAndreAppel\ArtisanUsers\Console\Commands
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
        $email = $this->ask("Email address");

        if (ArtisanUsers::userExists($email)) {
            $this->warn("User exists. Use <info>'artisan user:edit'</info> instead.");
        } else {
            $values = collect(
                [
                    'email'    => $email,
                    'name'     => $this->ask("First and last name"),
                    'password' => $this->secret("Password"),
                ]
            );
            ArtisanUsers::createUser($values);

            $this->info("User was successfully created.");
        }
    }
}
