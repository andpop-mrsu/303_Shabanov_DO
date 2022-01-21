<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Оказанные услуги</title>
    <link rel="stylesheet" href="../styles/style.css">
</head>
<body>
<?php
require_once 'AppointmentsRepository.php';
$repository = AppointmentsRepository::getInstance();
$dentist = $repository->readDentistById($_GET['dentist_id']);
?>
<h1 class="title">Оказанные услуги</h1>
<p>Выбранный дантист: <?= "$dentist[last_name] $dentist[first_name] $dentist[middle_name]" ?></p>
<table class="doctor_table">
    <tr>
        <th class="doctor_th">Название услуги</th>
        <th class="doctor_th">Время начала лечения</th>
        <th class="doctor_th">Время окончания лечения</th>
        <th class="doctor_th">Стоимость услуги</th>
    </tr>
    <?php foreach ($repository->readHistoryAppointmentsByDentistId($_GET['dentist_id']) as $appointment): ?>
        <?php
        $appointment_start_time = new DateTime($appointment['appointment_time']);
        $appointment_end_time = new DateTime($appointment['appointment_time']);
        $service = $repository->readServiceById($appointment['service_id']);
        $duration = (int)$service['duration_in_minutes'];
        $appointment_end_time->add(new DateInterval("PT" . $duration . "M"));
        $end_time_string = $appointment_end_time->format('Y-m-d H:i:s');
        ?>
        <tr>
            <td class="doctor_td"><?= $service['title'] ?></td>
            <td class="doctor_td"><?= $appointment['appointment_time'] ?></td>
            <td class="doctor_td"><?= $end_time_string ?></td>
            <td class="doctor_td"><?= $service['price'] ?></td>
        </tr>
    <?php endforeach; ?>
</table>
<p>
    <button onclick="window.location.href = '../index.php';" class="def_button">Назад</button>
</p>
</body>
</html>