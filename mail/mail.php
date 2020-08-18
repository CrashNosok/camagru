<?php

function confirmation_mail($login, $mail, $token)
{
  $link = 'http://localhost/camagru/mail/confirmation_mail.php?token='.$token.'&log='.$login;
  $subject = "Inscription Camagru";
  $content = "
  <html>
  <head>
    <title> Camagru </title>
  </head>
  <body>
    <p>
      Hello " . $login . " To finalize your registration click on this link
    </p>
    <a href='".$link."'>
      account confirmation!
    </a>
    <p>
      or go directly here '".$link."'
    </p>
  </body>";
  $headers  = 'MIME-Version: 1.0' . "\r\n";
  $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
  $headers .= 'From: Camagru <riocrash@mail.ru' . "\r\n";
  $send_mail = mail($mail, $subject, $content, $headers);
  return $send_mail;
}

function change_mail($login, $mail, $token)
{
  $link = 'http://localhost/camagru/mail/confirmation_mail.php?token='.$token.'&log='.$login;
  $subject = "Inscription Camagru";
  $content = "
  <html>
  <head>
    <title> Camagru </title>
  </head>
  <body>
    <p>
      Hello " . $login . " To change your email click on this link
    </p>
    <a href='".$link."'>
      email change confirmation!
    </a>
    <p>
      or go directly here '".$link."'
    </p>
  </body>";
  $headers  = 'MIME-Version: 1.0' . "\r\n";
  $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
  $headers .= 'From: Camagru <riocrash@mail.ru' . "\r\n";
  $send_mail = mail($mail, $subject, $content, $headers);
  return $send_mail;
}

function reset_password($login, $mail, $token)
{
  $subject = "Password Reset Camagru";
  $content = "
  <html>
  <head>
    <title> Camagru </title>
  </head>
  <body>
    <p>Hello " . $login . " code to reset your account password:</p>
    <p>$token</p>
  </body>";
  $headers  = 'MIME-Version: 1.0' . "\r\n";
  $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
  $headers .= 'From: Camagru <riocrash@mail.ru' . "\r\n";
  $send_mail = mail($mail, $subject, $content, $headers);
  return $send_mail;
}

function comment_mail($login, $mail, $text, $from)
{
  $subject = "New comment";
  $content = "
  <html>
  <head>
    <title> Camagru </title>
  </head>
  <body>
    <p>Hello " . $login . ". $from commented on you!</p>
    <p>'$text'</p>
  </body>";
  $headers  = 'MIME-Version: 1.0' . "\r\n";
  $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
  $headers .= 'From: Camagru <riocrash@mail.ru' . "\r\n";
  $send_mail = mail($mail, $subject, $content, $headers);
  return $send_mail;
}

function mentioned_mail($login, $mail, $text, $from)
{
  $subject = "Mentioned in the comment";
  $content = "
  <html>
  <head>
    <title> Camagru </title>
  </head>
  <body>
    <p>Hello " . $login . ". $from Mentioned you in the comment!</p>
    <p>'$text'</p>
  </body>";
  $headers  = 'MIME-Version: 1.0' . "\r\n";
  $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
  $headers .= 'From: Camagru <riocrash@mail.ru' . "\r\n";
  $send_mail = mail($mail, $subject, $content, $headers);
  return $send_mail;
}
?>