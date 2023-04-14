<?php

use Alura\Pdo\Domain\Model\Student;
use Alura\Pdo\Infraestructure\Persistence\ConnectionCreator;

require_once 'vendor/autoload.php';

$pdo = ConnectionCreator::createConnection();

$student = new Student(null,'Guilherme Bragato Albanaz', new DateTimeImmutable('2005-03-19'));

$query = "INSERT INTO students (name, birth_date) VALUES(?,?);";
$statement = $pdo->prepare($query); //prepara um statement para ser executada e retorna um objeto statement
//bindParam() -> referencia o caminho da váriavel setada
//bindValue() -> estático o valor passado não é alterado
//exemplo: se uma variável receber um valor, antes de chamada bindParam(), e logo depois de chamada alterar essa mesma variavel passada a bindParam o bindParam lerá o ultimo valor atualizado na variável. O que não acontece no bindValue.
$statement->bindValue(1, $student->name());
$statement->bindValue(2, $student->birthDate()->format('Y-m-d'));

if($statement->execute()){
    echo "Aluno incluído com sucesso!";
}else{
    echo "Aluno não incluído!";
}



 //  var_dump($pdo->exec($query)); exec -> retorna linhas afetadas