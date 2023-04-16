<?php

use Alura\Pdo\Domain\Model\Student;
use Alura\Pdo\Infraestructure\Repository\PdoStudentRepository;
use Alura\Pdo\Infraestructure\Persistence\ConnectionCreator;


require_once 'vendor/autoload.php';

$connection = ConnectionCreator::createConnection();
$studentRepository = new PdoStudentRepository($connection);

$connection->beginTransaction(); //inicia uma transação
try {
    $aStudent = new Student(null, 'Nico Steppat', new DateTimeImmutable('1985-05-01'));
    $studentRepository->save($aStudent);
    $anotherStudent = new Student(
        null,
        'Sergio Lopes',
        new DateTimeImmutable('1985-05-01')
    );
    $studentRepository->save($anotherStudent);
    $connection->commit();
} catch (\PDOException $e) {
    echo $e->getMessage();
    $connection->rollBack();
}

#$connection->commit(); // finaliza a transação e então executa o código acima de fato no banco 