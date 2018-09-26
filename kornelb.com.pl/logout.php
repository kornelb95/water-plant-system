<?php
//jezeli istnieje zmienna sesji id usera i zarówno ustawiona jest flaga wylogowania
if(isset($_SESSION['user_id']) && $_GET['logout'] == 1){
    //usunięcie zmiennych sesji
    session_destroy();

    
}

?>