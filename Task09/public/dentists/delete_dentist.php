<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Форма с запросом на удаление информации о выбранном дантисте</title>
    <link rel="stylesheet" href="../styles/style.css">
</head>
<body>
<?php
require_once 'DentistRepository.php';
$repository = DentistRepository::getInstance();
$dentist = $repository->readDentistById($_GET['dentist_id'])
?>
<form method="post" enctype="application/x-www-form-urlencoded" action="">
    <input type="hidden" name="id" value=<?= $_GET['dentist_id'] ?>>
    <input type="hidden" name="isDeleted" value=<?= true ?>>
    <h1>Форма с запросом на удаление информации о выбранном дантисте</h1>
    <p>Выбранный дантист: <?="$dentist[last_name] $dentist[first_name] $dentist[middle_name]"?></p>
    <fieldset class="fs">
        <p>Вы действительно хотите удалить информацию о выбранном дантисте?</p>
        <?php if (isset($_POST['isDeleted']) and $_POST['isDeleted'] == true) : ?>
            <p>
                <button class="def_button" disabled>Подтвердить</button>
            </p>
            <p>Информация о дантисте успешно удалена</p>
        <?php else: ?>
            <p>
                <button class="def_button">Подтвердить</button>
            </p>
        <?php endif; ?>
    </fieldset>
</form>
<?php if (isset($_POST['isDeleted']) and $_POST['isDeleted'] == true) {
    $repository->deleteDentist($_POST['id']);
} ?>
<p>
    <button onclick="window.location.href = '../index.php';" class="def_button">Назад</button>
</p>
</body>
</html>