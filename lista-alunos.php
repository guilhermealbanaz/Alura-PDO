<?php

use Alura\Pdo\Domain\Model\Student;
use Alura\Pdo\Infraestructure\Persistence\ConnectionCreator;

require_once 'vendor/autoload.php';

$pdo = ConnectionCreator::createConnection();

$statement = $pdo->query("SELECT * FROM students");

// var_dump($statement->fetchColumn(1)); busca uma coluna de um objeto, mesma lógica do fetch ocupa menos espaço por a cada renderização a render passada não ocupar mais espaço

// var_dump($statement->fetchObject(Student::class)); traz um objeto da classe passada como parâmetro

$studentDataList = $statement->fetchAll(PDO::FETCH_ASSOC); // busca todas as linha (fetchAll()) armazena em um objeto cada linha
$studentList = [];
foreach($studentDataList as $studentData){
    $studentList[] = new Student(
        $studentData['id'],
        $studentData['name'],
        new \DateTimeImmutable($studentData['birth_date'])
    );
}



// $studentDataList = $statement->fetch(PDO::FETCH_ASSOC);
// while ($studentData = $statement->fetch(PDO::FETCH_ASSOC)){ 
//     $student = new Student(
//         $studentData['id'],
//         $studentData['name'],
//         new \DateTimeImmutable($studentData['birth_date'])
//     );

//     echo $student->age();
// }
//enquanto studentData estiver recebendo dados de uma aluno em um array eu quero criar instancias pra esses determinados dados, quando não houverem mais dados $studentData é FALSE, ou seja o loop acabará. desse modo, se tivermos muitas linhas sendo trazidas e tentarmos executar o fetchAll, iremos colocar todas as linhas em memória de uma vez só. Isso pode trazer problemas. Utilizando o fetch dentro de um while, pode nos permitir buscar todos os resultados, mas colocando um de cada vez na memória.




echo '<pre>';
var_dump($studentList);
echo '</pre>';