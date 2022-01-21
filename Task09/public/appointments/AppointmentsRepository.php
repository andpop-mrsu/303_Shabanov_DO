<?php
require_once '../base/Repository.php';

class AppointmentsRepository extends Repository
{
    private static ?AppointmentsRepository $instance = null;



    public function readDentistById(string $dentist_id)
    {
        $query = 'SELECT id, last_name, first_name, middle_name, birthday, specialization_id, employee_status_id, earning_in_percent  FROM dentists WHERE id = :dentist_id';
        $statement = $this->pdo->prepare($query);
        $statement->execute(['dentist_id' => $dentist_id]);
        $dentists = $statement->fetchAll();
        $statement->closeCursor();
        return $dentists[0];
    }

    public function readServiceById(string $service_id)
    {
        $query = 'SELECT id, title, cathegory_id, specialization_id, duration_in_minutes, price FROM services WHERE id = :service_id';
        $statement = $this->pdo->prepare($query);
        $statement->execute(['service_id' => $service_id]);
        $dentists = $statement->fetchAll();
        $statement->closeCursor();
        return $dentists[0];
    }


    public function readHistoryAppointmentsByDentistId(string $dentist_id)
    {
        $query = 'SELECT id, client_id, dentist_id, appointment_time, start_time, end_time, appointment_status_id, service_id FROM appointments WHERE dentist_id = :dentist_id AND appointment_status_id == 2 AND DATE(appointment_time) < DATE("now") ORDER BY appointment_time';
        $statement = $this->pdo->prepare($query);
        $statement->execute(['dentist_id' => $dentist_id]);
        $history = $statement->fetchAll();
        $statement->closeCursor();
        return $history;
    }

    public static function getInstance(): AppointmentsRepository
    {
        if (static::$instance == null) static::$instance = new static('sqlite:../../data/dentistry.db');
        return static::$instance;
    }
}