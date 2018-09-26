/*wykonanie funkcji tylko gdy strona cała jest wczytana */
$(function () {
    //wywołanie ajax, gdy formularz rejestracji został potwierdzony
    $("#signupform").submit(function(event){ 
        //zatrzymanie domyślnych akcji po zatwierdzeniu formularzu
        event.preventDefault();
        //tworzy tablice nazwa-wartosc z danych wprowadzonych przez usera
        var datatopost = $(this).serializeArray();
        //wysłanie danych do signup.php ajaxem
        $.ajax({
            url: "signup.php",
            type: "POST",
            data: datatopost,
            success: function(data){
                if(data){
                    $("#signupmessage").html(data);
                }
            },
            error: function(){
                $("#signupmessage").html("<div class='alert alert-danger'>Błąd</div>");

            }

        });

    });

//wywołanie ajax dla formularza logowania
//Gdy formularz zostanie zatwierdzony
    $("#loginform").submit(function(event){ 
        //zatrzymanie domyślnych procesów php
        event.preventDefault();
        //zebranie w tablice danych usera
        var datatopost = $(this).serializeArray();
        //wysłanie ich do login.php przez ajaxa
        $.ajax({
            url: "login.php",
            type: "POST",
            data: datatopost,
            success: function(data){
                if(data == "success"){
                    
                    window.location = "main_device.php";
                }else{
                    $('#loginmessage').html(data);   
                }
            },
            error: function(){
                $("#loginmessage").html("<div class='alert alert-danger'>Błąd AJAX</div>");

            }

        });

    });

    

}); 