<?php
//rozpoczęcie sesji
session_start();
//połaczenie z bazą
include('sql.php');

$missingEmail = '<p><stong>Proszę wprowadzić adres email!</strong></p>';
$missingPassword = '<p><stong>Proszę wprowadzić hasło!</strong></p>'; 
    //w razie nieustawionych zmiennych POSt email i hasło 
//zapisywanie błędów w zmiennej
if(empty($_POST["loginemail"])){
    $errors .= $missingEmail;   
}else{
    //jeżeli istnieje zmienna POST - sanityzacja zmiennej z emailem i przypisanie
    $email = filter_var($_POST["loginemail"], FILTER_SANITIZE_EMAIL);
}

if(empty($_POST["loginpassword"])){
    $errors .= $missingPassword;   
}else{
    //jeżeli istnieje zmienna POST - sanityzacja zmiennej z hasłem i przypisanie
    $password = filter_var($_POST["loginpassword"], FILTER_SANITIZE_STRING);
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
        $password = mysqli_real_escape_string($link, $password);
        //hashowanie hasła
        $password = hash('sha256', $password);
        //zapytanie wyszukukące danego użytkownika
        $sql = "SELECT * FROM users WHERE email='$email' AND password='$password' AND activation='activated'";
        $result = mysqli_query($link, $sql);
        if(!$result){
            echo '<div class="alert alert-danger">Błąd zapytania</div>';
            exit;
        }
            //jeżeli brak błędów zapytania
        $count = mysqli_num_rows($result);
        //jezeli nie znalazło jednego wiersza z takimi danymi
        if($count !== 1){
            echo '<div class="alert alert-danger">Błędny email lub hasło</div>';
        }
        //gdy znalazło dokładnie jeden wiersz z danymi uzytkownika
        else {  
            //zalogowanie usera: ustawienie zmiennych sesji
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC); // zapis do tablicy asocjacyjnej pobranych danych
            $_SESSION['user_id']=$row['user_id'];
            $_SESSION['username']=$row['username'];
            $_SESSION['email']=$row['email'];
            echo "success";    
          
            }
    }

        
                    ?>