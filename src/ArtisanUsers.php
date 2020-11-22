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
     * @var string $model
     */
    private string $model;

    /** @var Model $user */
    public Model $user;

    /**
     * ArtisanUsers constructor.
     */
    public function __construct()
    {
        $this->model = config('artisan_users.use_model');
    }

    /**
     * @param  Collection<string>  $values
     *
     * @return bool
     */
    public function createUser(Collection $values): bool
    {
        /**
         * @var Model $user
         */
        $user = new $this->model();

        $user->name     = $values->get('name');
        $user->email    = $values->get('email');
        $user->password = Hash::make($values->get('password'));

        try {
            return $user->save();
        } catch (PDOException $exception) {
            return false;
        }
    }

    /**
     * @param  string  $email
     *
     * @return ArtisanUsers
     */
    public function updateUser(string $email): self
    {
        $this->user = $this->model::where('email', $email)->first();

        return $this;
    }

    /**
     * Updates the email address
     *
     * @param  string  $email
     *
     * @return bool
     */
    public function setEmail(string $email): bool
    {
        $this->user->email = $email;
        return $this->user->save();
    }

    /**
     * Updates the name associated to the user
     *
     * @param  string  $name
     *
     * @return bool
     */
    public function setName(string $name)
    {
        $this->user->name = $name;
        return $this->user->save();
    }

    /**
     * Updates the password
     *
     * @param  string  $password
     *
     * @return bool
     */
    public function setPassword(string $password)
    {
        $this->user->password = Hash::make($password);
        return $this->user->save();
    }

    /**
     * Verify the existence of a record with the given email address
     *
     * @param  string  $email
     *
     * @return bool
     */
    public function userExists(string $email): bool
    {
        try {
            $this->model::where('email', $email)->firstOrFail();

            return true;
        } catch (\Exception $exception) {
            return false;
        }
    }

}
