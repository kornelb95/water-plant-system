<?php

/*Definiowanie zmiennych z danymi niezbędnymi do połączenia z bazą danych*/
$serwer = 'localhost';
$uzytkownik = 'korni007_esp';
$haslo = 'f0d3f252';
$nazwa_bazy = 'korni007_ESP';
  
/*Połączenie z bazą*/
$link = mysqli_connect($serwer, $uzytkownik, $haslo, $nazwa_bazy);
$link -> query ('SET NAMES utf8');
$link -> query ('SET CHARACTER_SET utf8_unicode_ci');
if(mysqli_connect_error()){
    die('ERROR: Unable to connect:' . mysqli_connect_error()); 
}

?>