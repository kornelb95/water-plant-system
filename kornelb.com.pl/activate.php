<?php
//Przekierowanie tutaj po potwierdzeniu linku aktywacyjnego
//dwa parametry: email i klucz aktywacyjny
session_start();

include('sql.php'); //połączenie z bazą
?>

<!DOCTYPE html>
<html lang="pl">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Aktywowanie konta</title>
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <style>
            h1{
                color:purple;   
            }
            .contactForm{
                border:1px solid #7c73f6;
                margin-top: 50px;
                border-radius: 15px;
            }
        </style> 

    </head>
        <body>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-offset-1 col-sm-10 contactForm">
            <h1>Aktywowanie konta</h1>
<?php


    //zmienne zawierające email i klucz aktywacji
$email = $_GET['email'];
$key = $_GET['key'];
    //zwraca zmienne ze znakami specjalnymi 
$email = mysqli_real_escape_string($link, $email);
$key = mysqli_real_escape_string($link, $key);
    //zapytanie ustawia flagę aktywacji dla danego maila i klucza aktywacyjnego
$sql = "UPDATE users SET activation='activated' WHERE (email='$email' AND activation='$key') LIMIT 1";
$result = mysqli_query($link, $sql);
    //zwraca ilość poprawnie wyszukanych wierszy w ostatnim zapytaniu
    //zapytanie wykonane poprawnie - wyśiwetlenie info oraz button Zaloguj
if(mysqli_affected_rows($link) == 1){
    echo '<div class="alert alert-success">Konto zostało pomyślnie utworzone.</div>';
    echo '<a href="index.php" type="button" class="btn-lg btn-sucess">Zaloguj się<a/>';
    
}else{ //niepowodzenie zapytania
    //info o niepowodzeniu aktywacji konta
    echo '<div class="alert alert-danger">Konto nie zostało aktywowane...</div>';
    echo '<div class="alert alert-danger">' . mysqli_error($link) . '</div>';
    
}
?>
            
        </div>
    </div>
</div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        </body>
</html>