$(function () {
    //wywołanie ajax, gdy formularz rejestracji został potwierdzony
    $("#deviceform").submit(function(event){ 
        //zatrzymanie domyślnych akcji po zatwierdzeniu formularzu
        event.preventDefault();
        var datatopost = $(this).serializeArray();
        //wysłanie danych do signup.php ajaxem
        $.ajax({
            url: "device1.php",
            type: "POST",
            data: datatopost,
           success: function(data){
                 if(data=="success"){
                    
                    window.location = "main_page.php";
                }else{
                    $('#devicemessage').html(data);   
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown){
                $("#devicemessage").html("<div class='alert alert-danger'>Błąd AJAX</div>");
                alert("Status: " + textStatus); alert("Error: " + errorThrown); 

            }
        });

    });

}); 

