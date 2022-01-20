<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Форма для ввода информации о графике работы</title>
    <link rel="stylesheet" href="../styles/style.css">
</head>
<body>
<?php
require_once 'WorkHoursRepository.php';
$repository = WorkHoursRepository::getInstance();
$count = isset($_POST['count']) ? (int)$_POST['count'] : 1;
?>
<p>
<H1>Форма для ввода информации о графике работы</H1>
<form method="post" action="" enctype="application/x-www-form-urlencoded">
    <label>
        Введите количество рабочих дней для добавления:
        <input name="count" class="user-label" type="number" min="1" max="10" value="<?= $count ?>">
    </label>
    <button class="def_button" type="submit">Подтвердить</button>
</form>
</p>
<form method="post" action="" enctype="application/x-www-form-urlencoded">
    <p><label>
            Выберите доктора:
            <select name="select_id" class="label">
                <?php foreach ($repository->getDentists() as $dentist): ?>
                    <option value=<?= $dentist['id'] ?>> <?= "$dentist[id]. $dentist[last_name] $dentist[first_name] $dentist[middle_name]" ?> </option>
                <?php endforeach; ?>
            </select>
        </label></p>
    <fieldset class="fs">
        <fieldset class="fs">
            <legend>1-й рабочий день</legend>
            <p><label>Начало работы: <input type="datetime-local" name="start_time_1"
                                            class="time"></label></p>
            <p><label>Окончание работы: <input type="datetime-local" name="end_time_1"
                                               class="time"></label></p>
        </fieldset>
        <?php if (isset($_POST['count'])): ?>
            <?php for ($i = 2; $i <= $_POST['count']; $i++): ?>
                <fieldset class="fs">
                    <legend><?= $i ?>-й рабочий день</legend>
                    <p><label>Начало работы: <input type="datetime-local" name="start_time_<?= $i ?>"
                                                    class="time"></label></p>
                    <p><label>Окончание работы: <input type="datetime-local" name="end_time_<?= $i ?>"
                                                       class="time"></label></p>
                </fieldset>
            <?php endfor; ?>
            <label>
                <input value="<?= $_POST['count'] ?>" name="count" hidden>
            </label>
        <?php endif; ?>
    </fieldset>
    <p>
        <button class="def_button" type="submit">Сохранить в базе данных</button>
    </p>
</form>
<?php
$countSet = 0;
for ($i = 1; $i <= $count; $i++) {
    if (isset($_POST['start_time_' . $i]) && $_POST['start_time_' . $i] != '' && isset($_POST['end_time_' . $i]) && $_POST['end_time_' . $i] != '') {
        $countSet++;
    }
}
?>
<?php if ($countSet == $count): ?>
    <?php for ($i = 1; $i <= $count; $i++) {
        $repository->addWorkHours($_POST['select_id'], date_format(new DateTime($_POST['start_time_' . $i]), 'Y-m-d H:i:s'), date_format(new DateTime($_POST['end_time_' . $i]), 'Y-m-d H:i:s'));
    }
    ?>
    <p>Запись информации в базу данных прошла успешна</p>
<?php elseif (count($_POST) != 1 && count($_POST) != 0): ?>
    <p>Запись информации в базу данных не удалась</p>
<?php endif; ?>
<button onclick="window.location.href = '../index.php';" class="def_button">Назад</button>
</body>