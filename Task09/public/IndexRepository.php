<?php
require_once 'base/Repository.php';

class IndexRepository extends Repository
{
    private static ?IndexRepository $instance = null;

    public function readAllDentists()
    {
        $query = 'SELECT id, last_name, first_name, middle_name, (SELECT specialization FROM specializations s WHERE s.id = dentists.specialization_id) specialization FROM dentists WHERE employee_status_id != 2 ORDER BY last_name';
        return $this->doQuery($query);
    }

    public static function getInstance(): IndexRepository
    {
        if (static::$instance == null) static::$instance = new static('sqlite:../data/dentistry.db');
        return static::$instance;
    }
}