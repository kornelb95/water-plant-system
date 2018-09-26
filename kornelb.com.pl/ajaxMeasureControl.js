var globTryb = 'auto';
var values;
$(function(){
    $( ".radiobut" ).change(function() {
         var radioData =  $('input[name=options]:checked').val();
        console.log(radioData);
        if(radioData == "manual"){
            $('.submit').prop('disabled', false);
            globTryb = 'manual';
        } else{
            $('.submit').prop('disabled', true);
            $('#proccess').text('');
            globTryb = 'auto';
        }
    });
    $("#controlForm").on('click', '.submit', function (event) {

                /* zatrzymanie domyslnych procesów php */
                event.preventDefault();
                if(globTryb == "manual"){
                     values = this.name + "=" + this.name;
                }
                /* stworzenie zmiennej dla tablicy POST z atrybutów name przycisków: */
                

    });
   
    //wyzwalanie funkcji co dany czas
   setInterval(function () {
        $.ajax({
            //pobranie danych  metodą post
            type: "POST"
            , 
            /*ten plik będzie wykorzystany przy transferze danych - pobiera dane z bazy*/
            url: "pobierz.php"
            , 
            /*Dane w formacie JSON*/
            contentType: "application/json; charset=utf-8"
            , 
            dataType: 'json'
            , 
            /*Działania wykonywane w przypadku sukcesu*/
            success: function (json) { /*w razie sukcesu funkcja jako parametr przyjmie pobrane dane*/
                /*zapis w osobnych danych*/
                /*console.log(json);*/
                for (var klucz in json) {
                    //kolejne wiersze w pętli
                    var wiersz = json[klucz]; 
                    //przypisanie zmiennym pobranych danych z bazy przez plik pobierz.php
                    var moist = wiersz[0];
                    var temp = wiersz[1];
                    var status = wiersz[2];
                    var czas = wiersz[3];
                  
                    /*Wyświetlenie pobranych danych w pliku main_page.php*/
                    $('#moist').text(moist);
                    $('#temp').text(temp);
                    $('#status').text(status);
                    $('#czas').text(czas);
                    if(!($('#status').hasClass(status))){
                        $('#status').removeClass().addClass(status);
                        
                    }
                    if(!($('#czas').hasClass(czas))){
                        $('#czas').removeClass().addClass(czas);
                        
                    }
                    
                }
                
            var statusClass = $('#status').attr('class');
            var czasClass = $('#czas').attr('class');
            var dataPost = 'status='+statusClass+'&time='+czasClass+'&tryb='+globTryb+'&'+values;
            console.log(dataPost);
            $.ajax({
              url: "main_page.php",
              method: "POST",
              data:  dataPost,
              success: function(data){
                console.log(values);
                  if(globTryb == "manual"){
                      if(values == "on=on"){
                    $("#proccess").html("<span>Włączono podlewanie");
                    } else if(values == "off=off"){
                         $("#proccess").html("<span>Wyłączono podlewanie");
                    } 
                  } else{
                       $("#proccess").html(' ');
                  }
                
                },
                error: function(jqXHR, textStatus){
                    console.log( "Request failed: " + textStatus );
                }
            });
                
                
                
            }
            , /*Działania wykonywane w przypadku błędu*/
            error: function (blad) {
                //alert("Wystąpił błąd");
                console.log(blad);
                /*wysiwetlenie błedu w konsoli*/
            }
        });
    }, 1000);  //koniec setInterval
 
});