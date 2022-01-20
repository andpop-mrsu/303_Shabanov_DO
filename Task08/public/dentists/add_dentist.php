<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Форма для ввода информации о дантисте</title>
    <link rel="stylesheet" href="../styles/style.css">
</head>
<body>
<?php
require_once 'DentistRepository.php';
$repository = DentistRepository::getInstance();
?>
<form method="post" action="" enctype="application/x-www-form-urlencoded">
    <H1>Форма для ввода информации о дантисте</H1>
    <fieldset class="fs">
        <legend>Личная информация о дантисте</legend>
        <p><label>Фамилия:<input name="last_name" class="user-label"></label></p>
        <p><label>Имя:<input name="first_name" class="user-label"></label></p>
        <p><label>Отчество:<input name="middle_name" class="user-label"></label></p>
        <p><label>Дата рождения:<input type="date" name="birthday" class="user-label"></label></p>
    </fieldset>
    <fieldset class="fs">
        <legend>Специализация дантиста</legend>
        <?php foreach ($repository->getSpecializations() as $item): ?>
            <p><label> <input type="radio" name="specialization"
                              value="<?= $item['id'] ?>"> <?= $item['specialization'] ?> </label></p>
        <?php endforeach; ?>
    </fieldset>
    <fieldset class="fs">
        <legend>Статус дантиста</legend>
        <?php foreach ($repository->getStatuses() as $item): ?>
            <p><label> <input type="radio" name="status" value="<?= $item['id'] ?>"> <?= $item['status'] ?> </label></p>
        <?php endforeach; ?>
    </fieldset>
    <p><label>Процент выручки: <input type="number" min="1" max="100" name="earning_in_percent" value="100"></label></p>
    <p>
        <button class="def_button" type="submit">Сохранить в базе данных</button>
    </p>
</form>
<?php if (isset($_POST['last_name']) && isset($_POST['first_name']) && isset($_POST['middle_name']) && isset($_POST['birthday']) && $_POST['birthday'] != '' && isset($_POST['specialization']) && isset($_POST['status']) && isset($_POST['earning_in_percent'])) : ?>
    <?php
    $_POST['birthday'] = date_format(new DateTime($_POST['birthday']), 'd.m.Y');
    $repository->addDentist($_POST);
    ?>
    <p>Запись информации в базу данных прошла успешна</p>
<?php elseif (count($_POST) != 0): ?>
    <p>Запись информации в базу данных не удалась</p>
<?php endif; ?>
<button onclick="window.location.href = '../index.php';" class="def_button">Назад</button>
</body>
</html>