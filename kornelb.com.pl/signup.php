<?php
//start sesji
session_start();

include('sql.php');

//przechowanie info zwiazanych z formularzem o błędach w zmeinncych
$missingLogin = '<p><strong>Proszę wprowadzić login!</strong></p>';
 $missingEmail = '<p><strong>Proszę wprowadzić adres email</strong></p>';
$invalidEmail = '<p><strong>Proszę wprowadzić prawidłowy adres email</strong></p>';
$missingPassword = '<p><strong>Prosze wpisać hasło</strong></p>';
$invalidPassword = '<p><strong>Hasło powinno mieć nie mniej niż 6 znaków, zawierać conajmniej jedną literę i jedną cyfrę</strong></p>';
$differentPassword = '<p><strong>Hasła nie pasują do siebie</strong></p>';
$missingPassword2 = '<p><strong>Proszę potwierdzić hasło</strong></p>';

//sprawdzenie czy istnieją zmienne POST z formularza, zapis błędów do zmiennej jezeli nie lub kiedy istnieje to sanityzacja
if(empty($_POST["username"])){
    $errors .= $missingUsername;
}else{
    $username = filter_var($_POST["username"], FILTER_SANITIZE_STRING);   
}
//EMAIL
if(empty($_POST["email"])){
    $errors .= $missingEmail;   
}else{
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $errors .= $invalidEmail;   
    }
}
//HASŁO
//jezeli nie wypelnione pole
if(empty($_POST["password"])){
    $errors .= $missingPassword;
    //jezelidlugosc mniejsza niz 6 i nie zawiera min. jedną wielką litere oraz cyfre
}elseif(!(strlen($_POST["password"])>6
         and preg_match('/[A-Z]/',$_POST["password"])
         and preg_match('/[0-9]/',$_POST["password"])
        )
       ){
    $errors .= $invalidPassword; 
    //poprawna postać hasła
}else{
    $password = filter_var($_POST["password"], FILTER_SANITIZE_STRING); 
    //jezeli potweirdzenie hasła puste
    if(empty($_POST["password2"])){
        $errors .= $missingPassword2;
    }else{
        
        $password2 = filter_var($_POST["password2"], FILTER_SANITIZE_STRING);
        //jezeli potwierdzenie nie równa się password
        if($password !== $password2){
            $errors .= $differentPassword;
        }
    }
}
//Jezeli istnieja jakies stringi z błedami
if($errors){
    $resultMessage = '<div class="alert alert-danger">' . $errors .'</div>';
    echo $resultMessage;
    exit;
}

//Brka błędów

//przygotowanie zmiennych do zapytania
$username = mysqli_real_escape_string($link, $username);
$email = mysqli_real_escape_string($link, $email);
$password = mysqli_real_escape_string($link, $password);
//hashowanie hasła
$password = hash('sha256', $password);
//sprawdzenie czy taki user istnieje
$sql = "SELECT * FROM users WHERE username = '$username'";
$result = mysqli_query($link, $sql);

$results = mysqli_num_rows($result);
//jezeli znajdzie sie wiersz z takim userem to błąd
if($results){
    echo '<div class="alert alert-danger">Użytkownik już istnieje!</div>';  exit;
}
//jezeli dany email juz istnieje
$sql = "SELECT * FROM users WHERE email = '$email'";
$result = mysqli_query($link, $sql);

$results = mysqli_num_rows($result);
if($results){
    echo '<div class="alert alert-danger">Podany Email już istnieje!</div>';  exit;
}
//stworzenie klucza aktywacyjnego
$activationKey = bin2hex(openssl_random_pseudo_bytes(16));
    
//Włożenie danych do bazy 

$sql = "INSERT INTO users (`username`, `email`, `password`, `activation`) VALUES ('$username', '$email', '$password', '$activationKey')";
$result = mysqli_query($link, $sql);
if(!$result){
    echo '<div class="alert alert-danger">Błąd wstawiania danych do bazy!</div>'; 
    exit;
}

//wysłanie linku aktywacyjnego
$message = "Proszę wejść w link, aby aktywowac konto:\n\n";
//przesłanie metoda GET zmiennych email i key
$message .= "http://kornelb.com.pl/activate.php?email=" . urlencode($email) . "&key=$activationKey";
if(mail($email, 'Potweirdzenie rejestracji', $message, 'From:'.'kornelb@korni007.webd.pl')){
       echo "<div class='alert alert-success'>Rejestracja pomyślna! Wiadomość z potwierdzenie została wysłana na $email. </div>";
}
        
        ?>