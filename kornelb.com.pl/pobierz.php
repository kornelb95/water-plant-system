<?php
session_start();
 if(!isset($_SESSION['user_id'])){
    header("location: index.php");
}
/*Nagłówek pliku z info o formacie JSON
*/
header('Content-type: application/json');
 
/*Załączenie pliku odpowiadającego za połączenie z bazą danych.*/
require_once('sql.php');

 
/*Zapytanie SELECT pobierjące dane pomiarowe*/
$zapytanie_pobierz = "SELECT moist, temp, status, czas FROM esp ORDER BY id DESC LIMIT 1";
/*Wykonanie zapytania SELECT*/
$wynik_pobierz = mysqli_query($link, $zapytanie_pobierz);
/*Przygotowanie tablicy, przechowujaca dane z bazy*/
$pobrane_dane = array();
 
/*zapis danych w tabeli */
while ($wiersz = mysqli_fetch_row($wynik_pobierz)) 
{
  $pobrane_dane[] = $wiersz;
}
 
/*Wywołanie danych w formacie JSON. 
utworzenie danych JSON, 
 do zinterpretowania w pliku skrypt.js*/
echo json_encode($pobrane_dane);
 
?>