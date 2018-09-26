<?php
//jezeli nie ustawiona zmienna sesji to przekieruj na strone logowania
session_start();
if(!isset($_SESSION['user_id'])){
    header("location: index.php");
}
if(!isset($_SESSION['device_id'])){
    header("location: main_device.php");
}
?>
<?php  
include('sql.php');
//jezeli ustawiona zmienna POST wyslana ajaxem i jest równa "on"
/*if(isset($_POST['status']) && isset($_POST['time']) && isset($_POST['tryb']) && $_POST['tryb'] == 'auto' ){
    $action = 'none';
    $measureTime = $_POST['time'];
    
    $measureTime = mktime(substr($measureTime,11,12),substr($measureTime,14,15),substr($measureTime,17,18),substr($measureTime,5,6),substr($measureTime,8,9),substr($measureTime,0,4));
    $currentTime = time();
    $roznica = $currentTime - $measureTime;
    $roznica = $roznica / 60;
       
    if($_POST['status'] == 'Sucho' && $roznica < 10){
         $file = fopen("on_off.json", "w") or die("can't open file");
        fwrite($file, '{"on_off": "on"}');
        fclose($file);
        $action = 'podlewanie';
        
    } else{
        $file = fopen("on_off.json", "w") or die("can't open file");
        fwrite($file, '{"on_off": "off"}');
        fclose($file);
        $action = 'none';
    }
  
} else{*/
if(isset($_POST['tryb']) && $_POST['tryb'] == 'manual'){
     $sql = "REPLACE INTO action (`tryb`) VALUES ('manual')";
     $result = mysqli_query($link, $sql);
    
     if(isset($_POST['on']) && $_POST['on'] == "on") {  
    //otwarcie pliku .json i zapis informacji dla modułu sterującego elektorzaworem
  $file = fopen("on_off.json", "w") or die("can't open file");
  fwrite($file, '{"on_off": "on"}');
  fclose($file);
} 
//jezeli ustawiona zmienna POST wyslana ajaxem i jest równa "off"
    else if(isset($_POST['off']) && $_POST['off'] == 'off') {  
      $file = fopen("on_off.json", "w") or die("can't open file");
      fwrite($file, '{"on_off": "off"}');
      fclose($file);
    }
} else if(isset($_POST['tryb']) && $_POST['tryb'] == 'auto'){
    $sql = "REPLACE INTO action (`tryb`) VALUES ('auto')";
    $result = mysqli_query($link, $sql);
}
   
/*}*/
?>






<!DOCTYPE html>
<html lang="pl">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>System automatycznego podlewania</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
      <link href="style.css" rel="stylesheet">
      <link href='https://fonts.googleapis.com/css?family=Arvo' rel='stylesheet' type='text/css'>
     
     
      <style>
        #container{
            margin-top:120px;   
        }
          #thumb{
              margin-top: 200px;
              font-size: 15px;
          }
          .thumbnail {
	        border-radius: 20px;
        }
          #proccess{
              margin-bottom: 100px;
          }
          #chart-container {
				width: auto;
				height: auto;
			}
          .measureSpan{
              font-size: 1.1em;
              font-weight: bold;
          }
           
              
          
          
          
      </style>
  </head>
  <body>
    <!--Navigation Bar-->  
      <nav role="navigation" class="navbar navbar-custom navbar-fixed-top">
      
          <div class="container-fluid">
            
              <div class="navbar-header">
                      
                  <a class="navbar-brand ">System nawadniania</a>
                  <button type="button" class="navbar-toggle" data-target="#navbarCollapse" data-toggle="collapse">
                      <span class="icon-bar"></span>
                      <span class="icon-bar"></span>
                      <span class="icon-bar"></span>
                  
                  </button>
              </div>
              <div class="navbar-collapse collapse" id="navbarCollapse">
                  <ul class="nav navbar-nav navbar-right">
                      <li><a href="#">Zalogowany <b><?php echo $_SESSION['username']?></b></a></li>
                        <!--  ustawienie zmiennej logout dla pliku logout.php                    -->
                    <li><a href="index.php?logout=1">Wyloguj się</a></li>
                  </ul>
              
              </div>
          </div>
      
      </nav>
    
<!--Container-->
     
        <div class="container-fluid" id="thumb">
      
        <div class="row">
            <div class="col-sm-6 col-md-4">
                <div class="thumbnail">
                    
                    <div class="caption">
                        
                        <h2>Pomiary</h2>
                        
                        <p>
                            <div><span class="measureSpan">Ostatni pomiar: </span><span id="czas"></span></div>
                            <div><span class="measureSpan">Wilgotność: </span><span id="moist"></span><span> [%]</span> </div>
                            <div><span class="measureSpan">Temperatura: </span><span id="temp"></span><span> [&deg;C]</span> </div>
                             <div><span class="measureSpan">Stan:</span><span id="status"></span></div>
                             
                        </p>
                    </div>
                </div>
            </div>
             <div class="col-sm-6 col-md-4">
                <div class="thumbnail">
                    <div class="caption">
                        <h3>Dane archiwalne</h3>
                            
                                 <div class="btn-group">
                                   <div class="btn-group">
                                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                    Wilgotność <span class="caret"></span></button>
                                   
                                    <ul class="dropdown-menu" role="menu">
                                      <li><a href="#chartModal" data-toggle="modal">Ostatni tydzień</a></li>
                                      <li><a href="#chartModal" data-toggle="modal">Ostatni miesiąc</a></li>
                                        
                                    </ul>
                                    
                                  </div>
                                  <div class="btn-group">
                                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                    Temperatura <span class="caret"></span></button>
                                    
                                    <ul class="dropdown-menu formChart" role="menu">
                                      <li><a href="#chartModal" data-toggle="modal" id="temp7" class="submitChart">Ostatni tydzień</a></li>
                                      <li><a href="#chartModal" data-toggle="modal" id="temp30" class="submitChart">Ostatni miesiąc</a></li>
                                    </ul>
                                    
                                  </div>
                                </div> 
                    </div>
                </div>
            </div>
             <div class="col-sm-6 col-md-4">
                <div class="thumbnail">
                   
                    <div class="caption">
                       <h2>Tryb pracy</h2>
                        <div class="btn-group" data-toggle="buttons">
                          <label class="btn btn-primary active">
                            <input type="radio" name="options" id="auto" value="auto" autocomplete="off" class="radiobut"  checked> Automatyczny
                          </label>
                          <label class="btn btn-primary">
                            <input type="radio" name="options" value="manual" id="manual" autocomplete="off" class="radiobut"> Ręczny
                          </label>
                    </div>
                    </div>
                    <div class="caption">
                       <form method="post" id="controlForm">
                        <h3>Sterowanie ręczne</h3>
                        
                        <p>
                           
                           
                            <input class="btn btn-primary btn-block submit" name="on" id="on" type="submit" value="Włącz" disabled>
                            <br />
                            <input class="btn btn-primary btn-block submit" name="off" id="off" type="submit" value="Wyłącz" disabled>
                            <br />
                            <div class="light-status well" id="proccess" style="margin-top: 5px; text-align:center">
                    
                            </div>
                           
                          
                        </p>
                        </form>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    <div class="modal" id="chartModal">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button class="close" data-dismiss="modal">
                    &times;
                  </button>
                  <h4 id="myModalLabel">
                    Dane archiwalne
                  </h4>
              </div>
              <div class="modal-body">
                  <div id="chart-container">
                            <canvas id="mycanvas"></canvas>
                    </div>
                  
              </div>
              <div class="modal-footer">
              </div>
          </div>
      </div>
</div>
     
      

    <!-- Footer-->
      <div class="footer">
          <div class="container">
              <p>Korneliusz Błażek - Plant System  <?php $today = date("Y"); echo $today?>.</p>
          </div>
      </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="ajaxMeasureControl.js"></script>
     <script src="js/Chart.js"></script>
    <script src="charts.js"></script>
    
       
         <script>
      $().button('toggle').toggleClass('checked');
      </script>
   
  </body>
</html>