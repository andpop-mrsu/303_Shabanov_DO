<?php

require_once '../base/Repository.php';

class ScheduleRepository extends Repository
{
    private static ?ScheduleRepository $instance = null;

    public function getDentists()
    {
        $query = 'SELECT id, last_name, first_name, middle_name FROM dentists';
        return $this->doQuery($query);
    }

    public function createSchedule(string $dentist_id, string $start_time, string $end_time)
    {
        $query = 'INSERT INTO dentists_work_hours(dentist_id, start_time, end_time, work_hours_status_id)
          VALUES (:dentist_id, :start_time, :end_time, 1)';
        $statement = $this->pdo->prepare($query);
        $statement->execute(['dentist_id' => $dentist_id, 'start_time' => $start_time, 'end_time' => $end_time]);
        $statement->closeCursor();
    }

    public function readAllSchedule(string $dentist_id)
    {
        $query = 'SELECT * FROM dentists_work_hours WHERE dentist_id = :dentist_id and work_hours_status_id = 1 ORDER BY start_time, end_time';
        $statement = $this->pdo->prepare($query);
        $statement->execute(['dentist_id' => $dentist_id]);
        $schedule = $statement->fetchAll();
        $statement->closeCursor();
        return $schedule;
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

    public function readScheduleById(string $schedule_id)
    {
        $query = 'SELECT *  FROM dentists_work_hours WHERE id = :schedule_id';
        $statement = $this->pdo->prepare($query);
        $statement->execute(['schedule_id' => $schedule_id]);
        $schedule = $statement->fetchAll();
        $statement->closeCursor();
        return $schedule[0];
    }

    public function updateSchedule($res)
    {
        $query = 'UPDATE dentists_work_hours SET start_time = :start_time, end_time = :end_time WHERE id = :id';
        $statement = $this->pdo->prepare($query);
        $statement->execute(['id' => $res['id'], 'start_time' => $res['start_time'], 'end_time' => $res['end_time']]);
        $statement->closeCursor();
    }

    public function deleteSchedule(string $schedule_id)
    {
        $query = 'UPDATE dentists_work_hours SET work_hours_status_id = 0 WHERE id = :id';
        $statement = $this->pdo->prepare($query);
        $statement->execute(['id' => $schedule_id]);
        $statement->closeCursor();
    }


    public static function getInstance(): ScheduleRepository
    {
        if (static::$instance == null) static::$instance = new static('sqlite:../../data/dentistry.db');
        return static::$instance;
    }
}