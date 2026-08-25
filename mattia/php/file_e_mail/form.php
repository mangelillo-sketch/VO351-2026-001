<!DOCTYPE html>
<html>
    <head>
    </head>

    <body>
        <form action="email.php" method="post">
        <!--<form action="leggi_form.php" method="post">-->
            <label>Email: </label> <input type="email" name="destinatario" /> <br />
            <label>Oggetto: </label> <input type="text" name="obj" /> <br />
            <label>Messaggio: </label> 
            <textarea row="5" name="msg"></textarea> <br />

            <input type="submit" value="Invia l'email" />
        </form>
    </body>
</html>