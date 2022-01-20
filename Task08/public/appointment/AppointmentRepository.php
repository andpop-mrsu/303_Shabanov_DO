<?php
require_once '../base/Repository.php';

class AppointmentRepository extends Repository
{
    private static ?AppointmentRepository $instance = null;

    public function getServices()
    {
        $query = 'SELECT id, title, cathegory_id, specialization_id, duration_in_minutes, price FROM services';
        return $this->doQuery($query);
    }

    public function getDentistsBySpecialization(string $specialization_id)
    {
        $query = 'SELECT id, last_name, first_name, middle_name, specialization_id, employee_status_id FROM dentists WHERE specialization_id == :specialization_id AND employee_status_id == 1';
        $statement = $this->pdo->prepare($query);
        $statement->execute(['specialization_id' => $specialization_id]);
        $dentists = $statement->fetchAll();
        $statement->closeCursor();
        return $dentists;
    }

    public function getServiceById(string $service_id)
    {
        $query = 'SELECT id, title, cathegory_id, specialization_id, duration_in_minutes, price FROM services WHERE id = :service_id';
        $statement = $this->pdo->prepare($query);
        $statement->execute(['service_id' => $service_id]);
        $dentists = $statement->fetchAll();
        $statement->closeCursor();
        return $dentists;
    }

    public function getDentistById(string $dentist_id)
    {
        $query = 'SELECT last_name, first_name, middle_name FROM dentists WHERE id = :dentist_id';
        $statement = $this->pdo->prepare($query);
        $statement->execute(['dentist_id' => $dentist_id]);
        $dentists = $statement->fetchAll();
        $statement->closeCursor();
        return $dentists;
    }

    public function getFutureWorkHoursByDentistId(string $dentist_id)
    {
        $query = 'SELECT start_time, end_time FROM dentists_work_hours WHERE dentist_id = :dentist_id AND DATE(start_time) > DATE("now")';
        $statement = $this->pdo->prepare($query);
        $statement->execute(['dentist_id' => $dentist_id]);
        $work_hours = $statement->fetchAll();
        $statement->closeCursor();
        return $work_hours;
    }

    public function getFutureAppointmentsByDentistId(string $dentist_id)
    {
        $query = 'SELECT id, client_id, dentist_id, appointment_time, start_time, end_time, appointment_status_id, service_id FROM appointments WHERE dentist_id = :dentist_id AND appointment_status_id == 2 AND DATE(appointment_time) > DATE("now")';
        $statement = $this->pdo->prepare($query);
        $statement->execute(['dentist_id' => $dentist_id]);
        $work_hours = $statement->fetchAll();
        $statement->closeCursor();
        return $work_hours;
    }

    public function addClient($res)
    {
        $query = 'INSERT INTO clients(last_name, first_name, middle_name, birthday, phone_number, passport)
          VALUES (:last_name, :first_name, :middle_name, :birthday, :phone_number, :passport)';
        $statement = $this->pdo->prepare($query);
        $statement->execute(['last_name' => $res['last_name'], 'first_name' => $res['first_name'], 'middle_name' => $res['middle_name'], 'birthday' => $res['birthday'], 'phone_number' => $res['phone_number'], 'passport' => $res['passport']]);
        $statement->closeCursor();
        return $this->getLastClientId();
    }

    public function addAppointment($res)
    {
        $query = 'INSERT INTO appointments(client_id, dentist_id, appointment_time, appointment_status_id, service_id)
          VALUES (:client_id, :dentist_id, :appointment_time, 2, :service_id)';
        $statement = $this->pdo->prepare($query);
        $statement->execute(['client_id' => $res['client_id'], 'dentist_id' => $res['dentist_id'], 'appointment_time' => $res['appointment_time'], 'service_id' => $res['service_id']]);
        $statement->closeCursor();
    }

    public function getLastClientId()
    {
        $query = 'SELECT id FROM clients';
        $res = $this->doQuery($query);
        return $res[count($res) - 1]['id'];
    }


    public static function getInstance(): AppointmentRepository
    {
        if (static::$instance == null) static::$instance = new static('sqlite:../../data/dentistry.db');
        return static::$instance;
    }
}