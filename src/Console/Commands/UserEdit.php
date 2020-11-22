<?php
declare(strict_types=1);

namespace MarcAndreAppel\ArtisanUsers\Console\Commands;

use Illuminate\Console\Command;

class UserEdit extends Command
{

    /** @var string */
    protected $signature = 'user:edit';

    /** @var string */
    protected $description = "Modify an user";

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
    }
}
