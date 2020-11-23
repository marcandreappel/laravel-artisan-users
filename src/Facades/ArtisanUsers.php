<?php
declare(strict_types=1);

namespace MarcAndreAppel\ArtisanUsers\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static userExists(mixed $email)
 * @method static createUser(\Illuminate\Support\Collection $values)
 * @method static updateUser(mixed $email)
 */
class ArtisanUsers extends Facade
{

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'artisan_users';
    }

}
