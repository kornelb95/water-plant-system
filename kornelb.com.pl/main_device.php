<?php
//jezeli nie ustawiona zmienna sesji to przekieruj na strone logowania
session_start();
if(!isset($_SESSION['user_id'])){
    header("location: index.php");
}
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
                     <li><a href="#deviceModal" data-toggle="modal">Dodaj urządzenia</a></li>
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
                        <p id="czas"> </p>
                        <p>
                            <div id="moist"> </div>
                             <div id="temp"> </div>
                             <div id="status"> </div>
                        </p>
                    </div>
                </div>
            </div>
             <div class="col-sm-6 col-md-4">
                <div class="thumbnail">
                 
                    <div class="caption">
                        <h3>Dane archiwalne</h3>
                        
                         
                    </div>
                </div>
            </div>
             <div class="col-sm-6 col-md-4">
                <div class="thumbnail">
                  
                    <div class="caption">
                        <h3>Sterowanie ręczne</h3>
                        
                        <p>
                           <form method="post" id="controlForm">
                           
                            <input class="btn btn-primary btn-block submit" name="on" id="on" type="submit" value="Włącz" disabled>
                            <br />
                            <input class="btn btn-primary btn-block submit" name="off" id="off" type="submit" value="Wyłącz" disabled>
                            <br />
                            <div class="light-status well" id="proccess" style="margin-top: 5px; text-align:center">
                    
                            </div>
                           </form>
                          
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <form method="post" id="deviceform">
        <div class="modal" id="deviceModal">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button class="close" data-dismiss="modal">
                    &times;
                  </button>
                  <h4 id="myModalLabel">
                    Dodaj urządzenia
                  </h4>
              </div>
              <div class="modal-body">
                  <div id="devicemessage"></div>
                  <div class="form-group">
                      <label for="deviceemail" class="sr-only">Adres Email:</label>
                      <input class="form-control" type="email" name="deviceemail" id="deviceemail" placeholder="Adres Email" maxlength="50">
                  </div>
                  <div class="form-group">
                      <label for="deviceid" class="sr-only">Id urządzenia:</label>
                      <input class="form-control" type="text" name="deviceid" id="deviceid" placeholder="Id" maxlength="4">
                  </div>
              </div>
              <div class="modal-footer">
                  <input class="btn green btn-lg" name="adddevice" type="submit" value="Dodaj urządzenie">
              </div>
          </div>
      </div>
      </div>
      </form>
      

    <!-- Footer-->
      <div class="footer">
          <div class="container">
              <p>Korneliusz Błażek - Plant System  <?php $today = date("Y"); echo $today?>.</p>
          </div>
      </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="device.js"></script>
  </body>
</html>