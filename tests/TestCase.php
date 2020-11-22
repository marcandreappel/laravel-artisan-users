<?php

declare(strict_types=1);

namespace MarcAndreAppel\ArtisanUsers\Tests;

use Faker\Factory as Faker;
use Faker\Generator;
use Illuminate\Foundation\Application;
use MarcAndreAppel\ArtisanUsers\ArtisanUsersServiceProvider;

/**
 * Class TestCase
 *
 * @package MarcAndreAppel\ArtisanUsers\Tests
 */
class TestCase extends \Orchestra\Testbench\TestCase
{

    /**
     * @var Generator $faker
     */
    public Generator $faker;

    /**
     * Setting up the environnement.
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->faker = Faker::create();
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
    }

    /**
     * @param  Application  $app
     *
     * @return array|string[]
     */
    protected function getPackageProviders($app)
    {
        return [ArtisanUsersServiceProvider::class];
    }

}
