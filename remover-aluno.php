<?php

use Alura\Pdo\Domain\Model\Student;
use Alura\Pdo\Infraestructure\Persistence\ConnectionCreator;

require_once 'vendor/autoload.php';

$pdo = ConnectionCreator::createConnection();

$statement = $pdo->prepare("DELETE FROM students WHERE id = ?;");
$statement->bindValue(1,1, PDO::PARAM_INT);
var_dump($statement->execute());