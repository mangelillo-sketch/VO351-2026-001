<?php
$email = filter_var($_POST['destinatario'], FILTER_SANITIZE_EMAIL);
$oggetto = filter_var($_POST['obj'], FILTER_SANITIZE_STRING);
$msg = filter_var($_POST['msg'], FILTER_SANITIZE_STRING);

$headers[] = "MIME-Version=1.0";
$headers[] = "Content-type: text/html; charset: utf-8";

$headers = implode("\r\n", $headers);

$messaggio = <<<EMAIL
<!DOCTYPE html>
<html>
<head>
    <style>
        div{
            border: 1px solid;
            text-align: center;
        }
        h1{
            text-align: center;
            color: darkred;
        }
    </style>
</head>

<body>
    <h1>TEST EMAIL</h1>

    <div>
        $msg
    </div>
</body>
</html>
EMAIL;


$risultato_email = mail(
    $email,
    $oggetto,
    $messaggio,
    $headers
);



if($risultato_email){
    echo "Email inviata con successo";
}else{
    echo "Errore nell'invio dell'email";
}