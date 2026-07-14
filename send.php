<?php
/**
 * AVA Solution — обработчик формы заявки (PHP, PS.kz / Plesk).
 * Минимальные заголовки + конверт-отправитель (-f). Антиспам — honeypot.
 */

$TO      = 'info@ava-solution.kz';   // получатель заявок
$FROM    = 'info@ava-solution.kz';   // отправитель — реальный ящик домена
$SUBJECT = 'Заявка с сайта AVA Solution';
$SUCCESS = '/spasibo/';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: /'); exit; }

$back = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/';
function fail($back, $code){ $s=(strpos($back,'?')===false)?'?':'&'; header('Location: '.$back.$s.'error='.$code.'#lead'); exit; }

// honeypot — скрытое поле должно остаться пустым
if (!empty($_POST['website'])) { header('Location: '.$SUCCESS); exit; }

function clean($k){ $v = isset($_POST[$k]) ? trim($_POST[$k]) : ''; return str_replace(array("\r","\n"), ' ', $v); }
$contact   = clean('contact');
$direction = clean('direction');
$cargo     = clean('cargo');
$from      = clean('from');
$to        = clean('to');
$page      = clean('page');

if ($contact === '' || mb_strlen($contact) > 200) { fail($back, 'contact'); }

$body =
  "Новая заявка с сайта AVA Solution\n".
  "--------------------------------\n".
  "Направление:        ".($direction!==''?$direction:'—')."\n".
  "Тип груза:          ".($cargo!==''?$cargo:'—')."\n".
  "Город отправления:  ".($from!==''?$from:'—')."\n".
  "Город назначения:   ".($to!==''?$to:'—')."\n".
  "Контакт:            ".$contact."\n".
  "--------------------------------\n".
  "Страница:           ".($page!==''?$page:'—')."\n".
  "Дата/время:         ".date('Y-m-d H:i:s')."\n";

$headers  = 'From: AVA Solution <'.$FROM.">\r\n";
$headers .= 'Reply-To: '.$FROM."\r\n";
$headers .= 'Content-Type: text/plain; charset=UTF-8';

$subjectEncoded = '=?UTF-8?B?'.base64_encode($SUBJECT).'?=';

$ok = @mail($TO, $subjectEncoded, $body, $headers, '-f'.$FROM);

if ($ok) { header('Location: '.$SUCCESS); exit; }
fail($back, 'send');
