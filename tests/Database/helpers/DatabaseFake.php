<?php

namespace Tests\Database\helpers;

use \PDO;

class DatabaseFake
{
    protected static function setPdo(): PDO
    {
        $pdo = new PDO('sqlite::memory:', null, null, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
        return $pdo;
    }

    public static function getPdo()
    {      
        return self::setPdo();
    }
}