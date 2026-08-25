<h1>Dati inviati: </h1>
<?php

/*echo "Email: {$_POST['destinatario']}", "<br />";
echo "Oggetto: {$_POST['obj']}", "<br />";
echo "Messaggio: {$_POST['msg']}", "<br />";*/

$email = filter_var($_POST['destinatario'], FILTER_SANITIZE_EMAIL);
$oggetto = filter_var($_POST['obj'], FILTER_SANITIZE_STRING);
$msg = filter_var($_POST['msg'], FILTER_SANITIZE_STRING);

echo "Email: {$email}", "<br />";
echo "Oggetto: {$oggetto}", "<br />";
echo "Messaggio: {$msg}", "<br />";


/*echo "Email: {$_GET['destinatario']}", "<br />";
echo "Oggetto: {$_GET['obj']}", "<br />";
echo "Messaggio: {$_GET['msg']}", "<br />";*/
?>