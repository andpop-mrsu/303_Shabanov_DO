<?php

require_once '../base/Repository.php';

class WorkHoursRepository extends Repository
{
    private static ?WorkHoursRepository $instance = null;

    public function getDentists()
    {
        $query = 'SELECT id, last_name, first_name, middle_name FROM dentists';
        return $this->doQuery($query);
    }

    public function addWorkHours(string $dentist_id, string $start_time, string $end_time)
    {
        $query = 'INSERT INTO dentists_work_hours(dentist_id, start_time, end_time)
          VALUES (:dentist_id, :start_time, :end_time)';
        $statement = $this->pdo->prepare($query);
        $statement->execute(['dentist_id' => $dentist_id, 'start_time' => $start_time, 'end_time' => $end_time]);
        $statement->closeCursor();
    }


    public static function getInstance(): WorkHoursRepository
    {
        if (static::$instance == null) static::$instance = new static('sqlite:../../data/dentistry.db');
        return static::$instance;
    }
}