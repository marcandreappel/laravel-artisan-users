<?php
declare(strict_types=1);

namespace MarcAndreAppel\ArtisanUsers\Console\Commands;

use Illuminate\Console\Command;
use MarcAndreAppel\ArtisanUsers\Facades\ArtisanUsers;

/**
 * Class UserEdit
 *
 * @package MarcAndreAppel\ArtisanUsers\Console\Commands
 */
class UserEdit extends Command
{

    /** @var string */
    protected $signature = 'user:edit';

    /** @var string */
    protected $description = "Edit a user";

    /** @var bool $withRoles */
    private bool $withRoles;

    /** @return void */
    public function __construct()
    {
        parent::__construct();

        $this->withRoles = config('artisan_users.with_roles');
    }

    /** @return mixed */
    public function handle()
    {
        $email = $this->ask("Users email address");

        if (ArtisanUsers::userExists($email) === false) {
            $this->warn("User doesn't exist. use <info>'artisan user:add'</info> instead.");
        } else {
            $choice = $this->choice("Choose from the following to edit",
                ['Email', 'Name', 'Password', 'Cancel']
            );

            switch ($choice) {
                case 'cancel':
                    $this->info("As you wish.");
                    break;
                case 'email':
                    $newEmail = $this->ask("New email address");
                    if (ArtisanUsers::userExists($newEmail) === false) {
                        ArtisanUsers::updateUser($email)->setEmail($newEmail);
                        $this->info("Email address was modified.");
                    } else {
                        $this->error("Email address already on record!");
                    }
                    break;
                case 'name':
                    $newName = $this->ask("New first and last name");
                    ArtisanUsers::updateUser($email)->setName($newName);
                    $this->info("First and last name were modified.");
                    break;
                /**
                 * @fixme Add verification to the password question
                 */
                case 'password':
                    $newPassword = $this->ask("New password");
                    ArtisanUsers::updateUser($email)->setPassword($newPassword);
                    $this->info("Password was modified.");
                    break;
            }
        }
    }

}
