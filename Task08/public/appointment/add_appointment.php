<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Форма для ввода информации о предварительной записи к врачу</title>
    <link rel="stylesheet" href="../styles/style.css">
</head>
<body>
<?php
require_once 'AppointmentRepository.php';
$repository = AppointmentRepository::getInstance();

if (isset($_POST['last_name']) && isset($_POST['first_name']) && isset($_POST['middle_name']) && isset($_POST['birthday']) && $_POST['birthday'] != '' && isset($_POST['phone_number']) && isset($_POST['passport'])) {
    $_POST['birthday'] = date_format(new DateTime($_POST['birthday']), 'd.m.Y');
    $_POST['appointment_time'] = date_format(new DateTime($_POST['appointment_time']), 'Y-m-d H:i:s');
    $_POST['client_id'] = $repository->addClient($_POST);
    $repository->addAppointment($_POST);
    header("Location: add_appointment_success.php");
    exit();
}

?>
<h2>Форма для ввода информации о предварительной записи к врачу</h2>


<form method="post" action="" enctype="application/x-www-form-urlencoded">
    <fieldset class="fs">
        <legend>Выберите услугу</legend>
        <p><label>
                <select class="label" name="service_id">
                    <option value="" selected disabled hidden>Услуга не выбрана</option>
                    <?php foreach ($repository->getServices() as $id => $service): ?>
                        <?php if (isset($_POST['service_id']) && $id + 1 == $_POST['service_id']): ?>
                            <option value="<?= $service['id'] ?>"
                                    selected><?= "$service[title] | " . (int)$service['duration_in_minutes'] . " мин. | $service[price] р." ?></option>
                        <?php else: ?>
                            <option value="<?= $service['id'] ?>"><?= "$service[title] | " . (int)$service['duration_in_minutes'] . " мин. | $service[price] р." ?></option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>

            </label></p>
        <button type="submit" class="def_button">Выбрать</button>
    </fieldset>
</form>


<?php if (isset($_POST['service_id'])): ?>
    <?php $specialization_id = $repository->getServiceById($_POST['service_id'])[0]['specialization_id']; ?>
    <form method="post" action="" enctype="application/x-www-form-urlencoded">
        <fieldset class="fs">
            <legend>Выберите дантиста</legend>
            <p><label>
                    <select class="label" name="dentist_id">
                        <option value="" selected disabled hidden>Датист не выбран</option>
                        <?php foreach ($repository->getDentistsBySpecialization($specialization_id) as $id => $dentist): ?>
                            <?php if (isset($_POST['dentist_id']) && (int)$dentist['id'] == $_POST['dentist_id']): ?>
                                <option value="<?= $dentist['id'] ?>"
                                        selected> <?= ($id + 1) . ". $dentist[last_name] $dentist[first_name] $dentist[middle_name]" ?> </option>
                            <?php else: ?>
                                <option value="<?= $dentist['id'] ?>"> <?= ($id + 1) . ". $dentist[last_name] $dentist[first_name] $dentist[middle_name]" ?> </option>
                            <?php endif; ?>

                        <?php endforeach; ?>
                    </select>
                </label></p>
            <button type="submit" class="def_button">Выбрать</button>
        </fieldset>
        <label>
            <input value="<?= $_POST['service_id'] ?>" name="service_id" hidden>
        </label>
    </form>
<?php endif; ?>

<?php
$isSetCorrectTime = false;
if (isset($_POST['appointment_time']) && $_POST['appointment_time'] != '') {
    $dentist_id = $_POST['dentist_id'];
    $appointment_start_time = new DateTime($_POST['appointment_time']);
    $appointment_end_time = new DateTime($_POST['appointment_time']);
    $duration = (int)$repository->getServiceById($_POST['service_id'])[0]['duration_in_minutes'];
    $appointment_end_time->add(new DateInterval("PT" . $duration . "M"));
    foreach ($repository->getFutureWorkHoursByDentistId($dentist_id) as $work_hours) {
        $start_time = new DateTime($work_hours['start_time']);
        $end_time = new DateTime($work_hours['end_time']);
        if ($start_time <= $appointment_start_time && $appointment_end_time <= $end_time) {
            $isSetCorrectTime = true;
            break;
        }
    }

    if ($isSetCorrectTime) {
        foreach ($repository->getFutureAppointmentsByDentistId($dentist_id) as $appointment) {
            $start_time = new DateTime($appointment['appointment_time']);
            $end_time = new DateTime($appointment["appointment_time"]);
            $duration = (int)$repository->getServiceById($appointment['service_id'])[0]['duration_in_minutes'];
            $end_time->add(new DateInterval("PT" . $duration . "M"));
            if ($start_time <= $appointment_start_time && $appointment_end_time <= $end_time) {
                $isSetCorrectTime = false;
                break;
            }
        }
    }
}
?>

<?php if (isset($_POST['dentist_id'])): ?>
    <?php $dentist_id = $_POST['dentist_id']; ?>
    <form method="post" action="" enctype="application/x-www-form-urlencoded">
        <fieldset class="fs">
            <legend>Выберите время для записи</legend>
            <p>Рабочее время:</p>
            <?php foreach ($repository->getFutureWorkHoursByDentistId($dentist_id) as $work_hours): ?>
                <p class="mg_left"><?= "$work_hours[start_time] - $work_hours[end_time]" ?></p>
            <?php endforeach; ?>
            <p>Занятое время:</p>
            <?php foreach ($repository->getFutureAppointmentsByDentistId($dentist_id) as $appointment): ?>
                <?php
                $end_time = new DateTime($appointment["appointment_time"]);
                $duration = (int)$repository->getServiceById($appointment['service_id'])[0]['duration_in_minutes'];
                $end_time->add(new DateInterval("PT" . $duration . "M"));
                $end_time_string = $end_time->format('Y-m-d H:i:s');
                ?>
                <p class="mg_left"><?= "$appointment[appointment_time] - $end_time_string" ?></p>
            <?php endforeach; ?>
            <p><label>Выбор времени для записи:<input type="datetime-local" name="appointment_time"
                                                      class="time"
                                                      value="<?= $_POST['appointment_time'] ?? "" ?>"></label>
            </p>
            <?php if (isset($_POST['appointment_time']) && $_POST['appointment_time'] != '' && !$isSetCorrectTime) : ?>
                <p>Выбранное время недоступно для записи</p>
            <?php endif ?>
            <button type="submit" class="def_button">Выбрать</button>
        </fieldset>
        <label>
            <input value="<?= $_POST['service_id'] ?>" name="service_id" hidden>
            <input value="<?= $_POST['dentist_id'] ?>" name="dentist_id" hidden>
        </label>
    </form>
<?php endif; ?>

<h3>Итог:</h3>
<p>Услуга:
    <?php if (isset($_POST['service_id'])): ?>
        <?php $service = $repository->getServiceById($_POST['service_id'])[0]; ?>
        <?= "$service[title], длительность - " . (int)$service['duration_in_minutes'] . " мин., цена - $service[price] р." ?>
    <?php else: ?>
        не выбрана
    <?php endif; ?>
</p>
<p>Дантист:
    <?php if (isset($_POST['dentist_id'])): ?>
        <?php $dentist = $repository->getDentistById($_POST['dentist_id'])[0]; ?>
        <?= "$dentist[last_name] $dentist[first_name] $dentist[middle_name]" ?>
    <?php else: ?>
        не выбран
    <?php endif; ?>
</p>
<p>Время записи:
    <?php if ($isSetCorrectTime): ?>
        <?= date_format(new DateTime($_POST['appointment_time']), 'Y-m-d H:i:s') ?>
    <?php else: ?>
        не выбрано
    <?php endif; ?>
</p>

<?php if ($isSetCorrectTime): ?>
    <form method="post" action="" enctype="application/x-www-form-urlencoded">
        <fieldset class="fs">
            <legend>Введите информацию о себе</legend>
            <p><label>Фамилия:<input name="last_name" class="user-label"></label></p>
            <p><label>Имя:<input name="first_name" class="user-label"></label></p>
            <p><label>Отчество:<input name="middle_name" class="user-label"></label></p>
            <p><label>Дата рождения:<input type="date" name="birthday" class="user-label"></label></p>
            <p><label>Номер телефона в формате +7...:<input type="tel" name="phone_number" class="user-label"
                                                            minlength="12"></label></p>
            <p><label>Номер и серия паспорта:<input name="passport" class="user-label" minlength="10"></label></p>
        </fieldset>
        <p>
            <button type="submit" class="def_button">Подтвердить</button>
        </p>
        <label>
            <input value="<?= $_POST['service_id'] ?>" name="service_id" hidden>
            <input value="<?= $_POST['dentist_id'] ?>" name="dentist_id" hidden>
            <input value="<?= $_POST['appointment_time'] ?>" name="appointment_time" hidden>
        </label>
    </form>
<?php endif; ?>

<p>
    <button onclick="window.location.href = '../index.php';" class="def_button">Назад</button>
</p>
</body>
</html>