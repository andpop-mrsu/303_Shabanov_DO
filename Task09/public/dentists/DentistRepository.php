<?php
require_once '../base/Repository.php';

class DentistRepository extends Repository
{
    private static ?DentistRepository $instance = null;

    public function readSpecializations()
    {
        $query = 'SELECT id, specialization FROM specializations';
        return $this->doQuery($query);
    }

    public function readStatuses()
    {
        $query = 'SELECT id, status FROM employee_statuses';
        return $this->doQuery($query);
    }


    public function createDentist($res)
    {
        $query = 'INSERT INTO dentists(last_name, first_name, middle_name, birthday, specialization_id, employee_status_id, earning_in_percent) 
          VALUES (:last_name, :first_name, :middle_name, :birthday, :specialization_id, :employee_status_id, :earning_in_percent)';
        $statement = $this->pdo->prepare($query);
        $statement->execute(['last_name' => $res['last_name'], 'first_name' => $res['first_name'], 'middle_name' => $res['middle_name'], 'birthday' => $res['birthday'], 'specialization_id' => $res['specialization'], 'employee_status_id' => $res['status'], 'earning_in_percent' => $res['earning_in_percent']]);
        $statement->closeCursor();
    }

    public function readDentistById(string $dentist_id)
    {
        $query = 'SELECT id, last_name, first_name, middle_name, birthday, specialization_id, employee_status_id, earning_in_percent  FROM dentists WHERE id = :dentist_id';
        $statement = $this->pdo->prepare($query);
        $statement->execute(['dentist_id' => $dentist_id]);
        $dentists = $statement->fetchAll();
        $statement->closeCursor();
        return $dentists[0];
    }

    public function updateDentist($res)
    {
        $query = 'UPDATE dentists SET last_name = :last_name, first_name = :first_name, middle_name = :middle_name, birthday = :birthday, specialization_id = :specialization_id, employee_status_id = :employee_status_id, earning_in_percent = :earning_in_percent WHERE id = :id';
        $statement = $this->pdo->prepare($query);
        $statement->execute(['id' => $res['id'], 'last_name' => $res['last_name'], 'first_name' => $res['first_name'], 'middle_name' => $res['middle_name'], 'birthday' => $res['birthday'], 'specialization_id' => $res['specialization_id'], 'employee_status_id' => $res['employee_status_id'], 'earning_in_percent' => $res['earning_in_percent']]);
        $statement->closeCursor();
    }

    public function deleteDentist(string $dentist_id)
    {
        $query = 'UPDATE dentists SET employee_status_id = :employee_status_id WHERE id = :id';
        $statement = $this->pdo->prepare($query);
        $statement->execute(['employee_status_id' => 2, 'id' => $dentist_id]);
        $statement->closeCursor();
    }

    public static function getInstance(): DentistRepository
    {
        if (static::$instance == null) static::$instance = new static('sqlite:../../data/dentistry.db');
        return static::$instance;
    }
}