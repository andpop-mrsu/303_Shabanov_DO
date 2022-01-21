<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Форма с запросом на удаление информации о выбранном дне в графике работы</title>
    <link rel="stylesheet" href="../styles/style.css">
</head>
<body>
<?php
require_once 'ScheduleRepository.php';
$repository = ScheduleRepository::getInstance();
$dentist = $repository->readDentistById($_GET['dentist_id']);
$schedule = $repository->readScheduleById($_GET['schedule_id']);
?>
<form method="post" enctype="application/x-www-form-urlencoded" action="">
    <input type="hidden" name="id" value=<?= $_GET['schedule_id'] ?>>
    <input type="hidden" name="isDeleted" value=<?= true ?>>
    <h1>Форма с запросом на удаление информации о выбранном дне в графике работы</h1>
    <p>Выбранный дантист: <?= "$dentist[last_name] $dentist[first_name] $dentist[middle_name]" ?></p>
    <p>Выбранное время: <?= "$schedule[start_time] - $schedule[end_time]" ?></p>
    <fieldset class="fs">
        <p>Вы действительно хотите удалить информацию о выбранном дне в графике работы?</p>
        <?php if (isset($_POST['isDeleted']) and $_POST['isDeleted'] == true) : ?>
            <p>
                <button class="def_button" disabled>Подтвердить</button>
            </p>
            <p>Информация о выбранном дне в графике работы успешно удалена</p>
        <?php else: ?>
            <p>
                <button class="def_button">Подтвердить</button>
            </p>
        <?php endif; ?>
    </fieldset>
</form>
<?php if (isset($_POST['isDeleted']) and $_POST['isDeleted'] == true) {
    $repository->deleteSchedule($_POST['id']);
} ?>
<form method="post" enctype="application/x-www-form-urlencoded"
      action="read_schedule.php?dentist_id=<?= $_GET['dentist_id'] ?>">
    <p>
        <button class="def_button">Назад</button>
    </p>
</form>
<p>
    <button onclick="window.location.href = '../index.php';" class="def_button">Вернуться на начальную страницу</button>
</p>
</body>
</html>