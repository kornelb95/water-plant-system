$(function(){
   $.ajax({
       url: "chart.php",
       type: "POST",
       contentType: "application/json; charset=utf-8",
       dataType: 'json', 
       success: function(json){
           console.log(json);
           var temp = new Array();
           var czas = new Array();
           var wiersz = new Array();
           
           
           
            $(".formChart").on('click', '.submitChart', function(){
                var choice = this.id;
                if(choice == "temp30"){
                    for(var i = 0 <=30 in json) {
                        temp.push(json[i][0]);
                        czas.push(json[i][1]);
			         }
                        console.log(temp);
                        console.log(czas);
                } else if(choice == "temp7"){
                    for(var i = 0 <=7 in json) {
                        temp.push(json[i][0]);
                        czas.push(json[i][1]);
			         }
                        console.log(temp);
                        console.log(czas);
                }
            });
           
           /*$("#info").html("<span>"+temp +" "+czas+"</span>");*/
           var ctx = document.getElementById("mycanvas").getContext('2d');
           var chart = new Chart(ctx,{
               type: 'line',
               data: {
                   labels: czas,
                   datasets: [{
                       label: "temperatura",
                       /*backgroundColor: 'rgb(255,99,132)',
                       borderColor: 'rgb(255,99,132)',*/
                       data: temp,
                   }]
               },
               options: {}
           });
       },
       error: function(data){
           console.log(data);
       }
   }); 
});