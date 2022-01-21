!DOCTYPE html
html lang=ru
head
    meta charset=UTF-8
    titleФорма для обновления информации о дантистеtitle
    link rel=stylesheet href=..stylesstyle.css
head
body
php
require_once 'DentistRepository.php';
$repository = DentistRepositorygetInstance();
$dentist = count($_POST) == 0  $repository-readDentistById($_GET['dentist_id'])  $_POST;
$dentist['birthday'] = date_format(new DateTime($dentist['birthday']), 'Y-m-d');


form method=post action= enctype=applicationx-www-form-urlencoded
    H1Форма для обновления информации о дантистеH1
    fieldset class=fs
        legendЛичная информация о дантистеlegend
        input type=hidden name=id value== $dentist['id'] 
        plabelФамилияinput name=last_name class=user-label value== $dentist['last_name'] labelp
        plabelИмяinput name=first_name class=user-label value== $dentist['first_name'] labelp
        plabelОтчествоinput name=middle_name class=user-label value== $dentist['middle_name'] label
        p
        plabelДата рожденияinput type=date name=birthday class=user-label value== $dentist['birthday'] label
        p
    fieldset
    fieldset class=fs
        legendСпециализация дантистаlegend
        php foreach ($repository-readSpecializations() as $item) 
            php if ($item['id'] == $dentist['specialization_id']) 
                plabel input type=radio name=specialization_id
                                  value== $item['id']  checked = $item['specialization']  labelp
            php else 
                plabel input type=radio name=specialization_id
                                  value== $item['id']  = $item['specialization']  labelp
            php endif; 
        php endforeach; 
    fieldset
    fieldset class=fs
        legendСтатус дантистаlegend
        php foreach ($repository-readStatuses() as $item) 
            php if ($item['id'] == $dentist['employee_status_id']) 
                plabel input type=radio name=employee_status_id value== $item['id'] 
                                  checked = $item['status'] 
                    label
                p
            php else 
                plabel input type=radio name=employee_status_id
                                  value== $item['id']  = $item['status']  label
                p
            php endif; 
        php endforeach; 
    fieldset
    plabelПроцент выручки input type=number min=1 max=100 name=earning_in_percent
                                      value== $dentist['earning_in_percent'] labelp
    p
        button class=def_button type=submitСохранить в базе данныхbutton
    p
form
php if (isset($_POST['last_name']) && isset($_POST['first_name']) && isset($_POST['middle_name']) && isset($_POST['birthday']) && isset($_POST['specialization_id']) && isset($_POST['employee_status_id']) && isset($_POST['earning_in_percent']) &&
    $_POST['last_name'] != '' && $_POST['first_name'] != '' && $_POST['middle_name'] != '' && $_POST['birthday'] != '' && $_POST['specialization_id'] != '' && $_POST['employee_status_id'] != '' && $_POST['earning_in_percent'] != '')  
    php
    $_POST['birthday'] = date_format(new DateTime($_POST['birthday']), 'd.m.Y');
    $repository-updateDentist($_POST);
    
    pОбновление информации в базе данных прошло успешноp
php elseif (count($_POST) != 0) 
    pОбновление информации в базе данных не удалосьp
php endif; 
button onclick=window.location.href = '..index.php'; class=def_buttonНазадbutton
body
html