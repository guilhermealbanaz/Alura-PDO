<?php 

$pathDb = __DIR__.'/banco.sqlite';
$pdo = new PDO('sqlite:'. $pathDb);

echo 'Conectei';

$pdo->exec('CREATE TABLE students (id INTEGER PRIMARY KEY, name TEXT, birth_date TEXT );');