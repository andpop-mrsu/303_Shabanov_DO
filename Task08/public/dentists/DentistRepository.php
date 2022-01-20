<?php
require_once '../base/Repository.php';

class DentistRepository extends Repository
{
    private static ?DentistRepository $instance = null;

    public function getSpecializations()
    {
        $query = 'SELECT id, specialization FROM specializations';
        return $this->doQuery($query);
    }

    public function getStatuses()
    {
        $query = 'SELECT id, status FROM employee_statuses';
        return $this->doQuery($query);
    }


    public function addDentist($res)
    {
        $query = 'INSERT INTO dentists(last_name, first_name, middle_name, birthday, specialization_id, employee_status_id, earning_in_percent) 
          VALUES (:last_name, :first_name, :middle_name, :birthday, :specialization_id, :employee_status_id, :earning_in_percent)';
        $statement = $this->pdo->prepare($query);
        $statement->execute(['last_name' => $res['last_name'], 'first_name' => $res['first_name'], 'middle_name' => $res['middle_name'], 'birthday' => $res['birthday'], 'specialization_id' => $res['specialization'], 'employee_status_id' => $res['status'], 'earning_in_percent' => $res['earning_in_percent']]);
        $statement->closeCursor();
    }

    public static function getInstance(): DentistRepository
    {
        if (static::$instance == null) static::$instance = new static('sqlite:../../data/dentistry.db');
        return static::$instance;
    }
}