<?php
//rozpoczęcie sesji
session_start();
if(!isset($_SESSION['user_id'])){
    header("location: index.php");
}
//połaczenie z bazą
include('sql.php');

$missingEmail = '<p><stong>Proszę wprowadzić adres email!</strong></p>';
$missingid = '<p><stong>Proszę wprowadzić id!</strong></p>'; 
$wrongEmail = '<p><stong>Email niezgodny z emailem logowania!</strong></p>';
    //w razie nieustawionych zmiennych POSt email i hasło 
//zapisywanie błędów w zmiennej
if(empty($_POST["deviceemail"])){
    $errors .= $missingEmail;   
}elseif(!($_POST["deviceemail"] == $_SESSION['email'])){
    $errors .= $wrongEmail; 
}else{
    //jeżeli istnieje zmienna POST - sanityzacja zmiennej z emailem i przypisanie
    $email = filter_var($_POST["deviceemail"], FILTER_SANITIZE_EMAIL);
}

if(empty($_POST["deviceid"])){
    $errors .= $missingid;   
}else{
    //jeżeli istnieje zmienna POST - sanityzacja zmiennej z hasłem i przypisanie
    $deviceid = filter_var($_POST["deviceid"], FILTER_SANITIZE_STRING);
}
//jezeli zmienna error nie jest pusta i jakies pole jest puste lub niepoprawne
if($errors){
    //wyswietl błędy
    $resultMessage = '<div class="alert alert-danger">' . $errors .'</div>';
    echo $resultMessage;   
}else{
        //jezeli nie ma błędów i wpisane są wszystkie pola
        //Przygotowanie zmiennych do zapytania
        $email = mysqli_real_escape_string($link, $email);
        $deviceid = mysqli_real_escape_string($link, $deviceid);
        //zapytanie wyszukukące danego użytkownika
        $sql = "SELECT * FROM devices WHERE device_id='$deviceid'";
        $result = mysqli_query($link, $sql);
        if(!$result){
            echo '<div class="alert alert-danger">Błąd zapytania</div>';
            exit;
        }
            //jeżeli brak błędów zapytania
        $count = mysqli_num_rows($result);
        //jezeli nie znalazło jednego wiersza z takimi danymi
        if($count !== 1){
            echo '<div class="alert alert-danger">Błędne id urządzenia</div>';
        }
        //gdy znalazło dokładnie jeden wiersz z danymi uzytkownika
        else {  
                $sql = "SELECT * FROM userdevices WHERE device_id='$deviceid'";
                $result = mysqli_query($link, $sql);
                $count = mysqli_num_rows($result);
                if($count !== 1){
                     $sql = "INSERT INTO userdevices(device_id, email) VALUES ('$deviceid', '$email')";
                     $result = mysqli_query($link, $sql);
                }
                $sql = "SELECT * FROM userdevices WHERE device_id='$deviceid' AND email='$email'";
                $result = mysqli_query($link, $sql);
                $count = mysqli_num_rows($result);
                if($count !== 1){
                echo '<div class="alert alert-danger">Błędne id urządzenia</div>';
                }
                else{
                    //zalogowanie usera: ustawienie zmiennych sesji
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC); // zapis do tablicy asocjacyjnej pobranych danych
                    $_SESSION['device_id']=$row['device_id'];
                    $_SESSION['deviceemail']=$row['email'];
                    echo "success";  
                }
              
          
            }
    }

        
                    ?>