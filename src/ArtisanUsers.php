<?php

declare(strict_types=1);

namespace MarcAndreAppel\ArtisanUsers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use PDOException;

/**
 * Class ArtisanUsers.
 */
class ArtisanUsers
{

    /**
     * @var bool $roles
     */
    private bool $roles;

    /**
     * @var string $user
     */
    private string $user;

    /**
     * ArtisanUsers constructor.
     */
    public function __construct()
    {
        $this->roles = config('artisan_users.with_roles');
        $this->user  = config('artisan_users.use_model');
    }

    /**
     * @param Collection<string> $values
     *
     * @return bool
     */
    public function createUser(Collection $values): bool
    {
        /**
         * @var Model $user
        */
        $user = new $this->user();

        $user->name     = $values->get('name');
        $user->email    = $values->get('email');
        $user->password = Hash::make($values->get('password'));

        if ($this->roles) {
            $user->role = $values->get('role', 'user');
        }

        try {
            return $user->save();
        } catch (PDOException $exception) {
            return false;
        }
    }
}
