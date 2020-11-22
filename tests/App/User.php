<?php
declare(strict_types=1);

namespace MarcAndreAppel\ArtisanUsers\Tests\App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory, Authenticatable;
}
