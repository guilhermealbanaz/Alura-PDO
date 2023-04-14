<?php 

namespace Alura\Pdo\Infraestructure\Persistence;

use PDO;

class ConnectionCreator
{

    public static function createConnection(): PDO
    {
        $pathDb = __DIR__.'/../../../banco.sqlite';
        return new PDO('sqlite:'. $pathDb);

    }

}