<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>График</title>
    <link rel="stylesheet" href="../styles/style.css">
</head>
<body>
<?php
require_once 'ScheduleRepository.php';
$repository = ScheduleRepository::getInstance();
$dentist = $repository->readDentistById($_GET['dentist_id'])
?>
<h1 class="title">График</h1>
<p>Выбранный дантист: <?= "$dentist[last_name] $dentist[first_name] $dentist[middle_name]" ?></p>
<table class="doctor_table">
    <tr>
        <th class="doctor_th">Время начала работы</th>
        <th class="doctor_th">Время окончания работы</th>
        <th class="doctor_th">Редактирование</th>
        <th class="doctor_th">Удаление</th>
    </tr>
    <?php foreach ($repository->readAllSchedule($_GET['dentist_id']) as $schedule): ?>
        <tr>
            <td class="doctor_td"><?= $schedule['start_time'] ?></td>
            <td class="doctor_td"><?= $schedule['end_time'] ?></td>
            <td class="doctor_td"><a href="update_schedule.php?dentist_id=<?= $_GET['dentist_id'] ?>&schedule_id=<?= $schedule['id'] ?>">Редактировать</a>
            </td>
            <td class="doctor_td"><a href="delete_schedule.php?dentist_id=<?= $_GET['dentist_id'] ?>&schedule_id=<?= $schedule['id'] ?>">Удалить</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<form method="post" enctype="application/x-www-form-urlencoded"
      action="create_schedule.php?dentist_id=<?= $_GET['dentist_id'] ?>">
    <p>
        <button class="def_button">Добавить информацию о графике работы</button>
    </p>
</form>
<p>
    <button onclick="window.location.href = '../index.php';" class="def_button">Назад</button>
</p>
</body>
</html>