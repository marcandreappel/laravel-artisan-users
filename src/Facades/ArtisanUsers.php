<?php
declare(strict_types=1);

namespace MarcAndreAppel\ArtisanUsers\Facades;

use Illuminate\Support\Facades\Facade;

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
