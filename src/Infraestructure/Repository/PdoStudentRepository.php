<?php

namespace Alura\Pdo\Infraestructure\Repository;

use Alura\Pdo\Domain\Model\Student;
use Alura\Pdo\Domain\Model\Phone;
use Alura\Pdo\Domain\Repository\StudentRepository;
use PDO;
use RuntimeException;

class PdoStudentRepository implements StudentRepository
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection; //injeção de dependencia
    }

    public function allStudents(): array
    {
        $query = "SELECT * FROM students;";
        $stmt = $this->connection->query($query);
        return $this->hydrateStudentList($stmt);
    }
    public function studentsBirthAt(\DateTimeInterface $birthDate): array
    {
        $stmt = $this->connection->query("SELECT * FROM students;");
        $stmt->bindValue(1, $birthDate->format('Y-m-d'));
        $stmt->execute();

        return $this->hydrateStudentList($stmt);
    }
    public function hydrateStudentList(\PDOStatement $stmt): array //padrão hidratar transfere dados de uma camada para outra 
    {
        $studentDataList = $stmt->fetchAll();
        $studentList = [];

        foreach ($studentDataList as $studentData) {
            $student = new Student(
                $studentData['id'],
                $studentData['name'],
                new \DateTimeImmutable($studentData['birth_date'])
            );

            $this->fillPhoneOf($student);

            $studentList[] = $student;
        }
        return $studentList;
    }

    private function fillPhoneOf(Student $student): void
    {
        $sql = "SELECT * FROM phones WHERE id = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(1, $student->id(), PDO::PARAM_INT);
        $stmt->execute();

        $phoneDataList = $stmt->fetchAll(); //PDO::FETCH_ASSOC is default 
        foreach ($phoneDataList as $phoneData) {
            $phone = new Phone(
                $phoneData['id'],
                $phoneData['area_code'],
                $phoneData['number']
            );
            $student->addPhone($phone);
        }
    }

    public function insert(Student $student): bool
    {
        $query = "INSERT INTO students(name, birth_date) VALUES(?,?);";
        $stmt = $this->connection->prepare($query);

        if ($stmt == false) {
            throw new RuntimeException(message: "Erro na query do banco");
        }
        $success =  $stmt->execute([
            $student->name(),
            $student->birthDate()->format('Y-m-d')
        ]);
        if ($success) {
            $student->defineId($this->connection->lastInsertId());
        }

        return $success;
    }

    public function save(Student $student): bool
    {
        if ($student->id() == null) {
            return $this->insert($student);
        }
        return $this->update($student);
    }

    public function remove(Student $student): bool
    {
        $stmt = $this->connection->prepare("DELETE FROM students WHERE id = ?;");
        $stmt->bindValue(1, $student->id(), PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function update(Student $student): bool
    {
        $stmt = $this->connection->prepare("UPDATE students SET name = :name, birth_date = :b WHERE id = :id;");
        $stmt->bindValue(':name', $student->name());
        $stmt->bindValue(':b', $student->birthDate()->format('Y-m-d'));
        $stmt->bindValue(':id', $student->id(), PDO::PARAM_INT);

        return $stmt->execute();
    }
}
