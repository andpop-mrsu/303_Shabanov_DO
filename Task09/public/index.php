<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Стоматология</title>
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
<?php
require_once 'IndexRepository.php';
$repository = IndexRepository::getInstance();
?>
<h1 class="title">Список всех врачей клиники</h1>
<table class="doctor_table">
    <tr>
        <th class="doctor_th">Фамилия</th>
        <th class="doctor_th">Имя</th>
        <th class="doctor_th">Отчество</th>
        <th class="doctor_th">Специальность</th>
        <th class="doctor_th">Редактирование</th>
        <th class="doctor_th">Удаление</th>
        <th class="doctor_th">График</th>
        <th class="doctor_th">Оказанные услуги</th>
    </tr>
    <?php foreach ($repository->readAllDentists() as $dentist): ?>
        <tr>
            <td class="doctor_td"><?= $dentist['last_name'] ?></td>
            <td class="doctor_td"><?= $dentist['first_name'] ?></td>
            <td class="doctor_td"><?= $dentist['middle_name'] ?></td>
            <td class="doctor_td"><?= $dentist['specialization'] ?></td>
            <td class="doctor_td"><a href="dentists/update_dentist.php?dentist_id=<?= $dentist['id'] ?>">Редактировать</a></td>
            <td class="doctor_td"><a href="dentists/delete_dentist.php?dentist_id=<?= $dentist['id'] ?>">Удалить</a></td>
            <td class="doctor_td"><a href="schedule/read_schedule.php?dentist_id=<?= $dentist['id'] ?>">График</a></td>
            <td class="doctor_td"><a href="appointments/appointments_history.php?dentist_id=<?= $dentist['id'] ?>">Оказанные услуги</a></td>
        </tr>
    <?php endforeach; ?>
</table>
<form method="post" action="dentists/create_dentist.php" enctype="application/x-www-form-urlencoded">
    <p><button class="def_button">Добавить нового врача</button></p>
</form>
</body>
</html>