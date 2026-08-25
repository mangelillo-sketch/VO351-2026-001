<pre>
    <?php
    print_r($_FILES);
    ?>
</pre>

<?php
if(!isset($_FILES['file_inviato']) && !is_uploaded_file($_FILES['file_inviato']['tmp_name'])){
    echo "File non inviato";
    exit;
}

define('UPLOAD_DIR', 'file_uploaded');


$tmp_file = $_FILES['file_inviato']['tmp_name'];
$arr = explode(".", $_FILES['file_inviato']['name']);
$ext = end($arr);
$new_name = bin2hex(random_bytes(16)).".".$ext;

if(move_uploaded_file($tmp_file, UPLOAD_DIR.DIRECTORY_SEPARATOR.$new_name)){
    echo "file caricato con successo";
}else{
    echo "errore nel caricamento del file";
}