<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Форма для обновления информации о графике работы</title>
    <link rel="stylesheet" href="../styles/style.css">
</head>
<body>
<?php
require_once 'ScheduleRepository.php';
$repository = ScheduleRepository::getInstance();
$dentist = $repository->readDentistById($_GET['dentist_id']);
$schedule = count($_POST) == 0 ? $repository->readScheduleById($_GET['schedule_id']) : $_POST;
$schedule['start_time'] = date_format(new DateTime($schedule['start_time']), 'Y-m-d h:i');
$schedule['end_time'] = date_format(new DateTime($schedule['end_time']), 'Y-m-d h:i');
?>
<H1>Форма для обновления информации о графике работы</H1>
<p>Выбранный дантист: <?= "$dentist[last_name] $dentist[first_name] $dentist[middle_name]" ?></p>
<form method="post" action="" enctype="application/x-www-form-urlencoded">
    <input type="hidden" name="id" value=<?= $_GET['schedule_id'] ?>>
    <fieldset class="fs">
        <fieldset class="fs">
            <p><label>Начало работы: <input type="datetime-local" name="start_time"
                                            class="time" value="<?= $schedule['start_time'] ?>"></label></p>
            <p><label>Окончание работы: <input type="datetime-local" name="end_time"
                                               class="time" value="<?= $schedule['end_time'] ?>"></label></p>
        </fieldset>
    </fieldset>
    <p>
        <button class="def_button" type="submit">Сохранить в базе данных</button>
    </p>
</form>
<?php if (isset($_POST['start_time']) && $_POST['start_time'] != '' && isset($_POST['end_time']) && $_POST['end_time']): ?>
    <?php
    $_POST['start_time'] = date_format(new DateTime($_POST['start_time']), 'Y-m-d H:i:s');
    $_POST['end_time'] = date_format(new DateTime($_POST['end_time']), 'Y-m-d H:i:s');
    $repository->updateSchedule($_POST) ?>
    <p>Обновление информации в базе данных прошло успешно</p>
<?php elseif (count($_POST) != 0): ?>
    <p>Обновление информации в базе данных не удалось</p>
<?php endif; ?>
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