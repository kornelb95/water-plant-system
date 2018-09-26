<?php
session_start();
include('sql.php'); //połączenie z bazą

//wylogowanie
include('logout.php');

?>
<!DOCTYPE html>
<html lang="pl">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1 user-scalable=yes">
    <title>System automatycznego podlewania</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
 <!--    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/start/jquery-ui.css">-->
      <link href="style.css" rel="stylesheet">
      <link href='https://fonts.googleapis.com/css?family=Arvo' rel='stylesheet' type='text/css'>
  </head>
  <body>
    <!--Navigation Bar-->  
      <nav role="navigation" class="navbar navbar-custom navbar-fixed-top">
      
          <div class="container-fluid">
            
              <div class="navbar-header">
              
                  <a class="navbar-brand ">System nawadniania</a>
                  <button type="button" class="navbar-toggle" data-target="#navbarCollapse" data-toggle="collapse">
                      <span class="sr-only">Toggle navigation</span>
                      <span class="icon-bar"></span>
                      <span class="icon-bar"></span>
                      <span class="icon-bar"></span>
                  
                  </button>
              </div>
              <div class="navbar-collapse collapse" id="navbarCollapse">
                  <ul class="nav navbar-nav">
                    <li class="active "><a href="#">Strona główna</a></li>
                  </ul>
                  <ul class="nav navbar-nav navbar-right">
                    <li><a href="#loginModal" data-toggle="modal">Zaloguj się</a></li>
                    <li><a href="#signupModal" data-toggle="modal">Załóż konto</a></li>
                    
                  </ul>
              
              </div>
          </div>
      
      </nav>
    
    <!--Jumbotron i Sign up Button-->
      <div class="jumbotron" id="myContainer">
           <h1>Bezprzewodowe automatyczne podlewanie ogrodu</h1>
          <p>Zarejestruj się, dodaj urządzenia i zautomatyzuj podlewanie ogrodu.</p>
         
      </div>

    <!--Login form-->    
      <form method="post" id="loginform">
        <div class="modal" id="loginModal">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button class="close" data-dismiss="modal">
                    &times;
                  </button>
                  <h4 id="myModalLabel">
                    Logowanie: 
                  </h4>
              </div>
              <div class="modal-body">
                  
                  <!--Login message from PHP file-->
                  <div id="loginmessage"></div>
                  

                  <div class="form-group">
                      <label for="loginemail">Adres Email:</label>
                      <input class="form-control" type="email" name="loginemail" id="loginemail" placeholder="Email" maxlength="50">
                  </div>
                  <div class="form-group">
                      <label for="loginpassword">Hasło</label>
                      <input class="form-control" type="password" name="loginpassword" id="loginpassword" placeholder="Hasło" maxlength="30">
                  </div>
                  
              </div>
              <div class="modal-footer">
                  <input class="btn green btn-lg" name="login" type="submit" value="Zaloguj">
                <button type="button" class="btn btn-default pull-left btn-lg" data-dismiss="modal" data-target="signupModal" data-toggle="modal">
                  Załóż konto
                </button>  
              </div>
          </div>
      </div>
      </div>
      </form>

    <!--Sign up form--> 
      <form method="post" id="signupform">
        <div class="modal" id="signupModal">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button class="close" data-dismiss="modal">
                    &times;
                  </button>
                  <h4 id="myModalLabel">
                    Załóż konto
                  </h4>
              </div>
              <div class="modal-body">
                  
                  <!--info z php-->
                  <div id="signupmessage"></div>
                  
                  <div class="form-group">
                      <label for="username" class="sr-only">Nazwa użytkownika:</label>
                      <input class="form-control" type="text" name="username" id="username" placeholder="Nazwa użytkownika" maxlength="30">
                  </div>
                  <div class="form-group">
                      <label for="email" class="sr-only">Adres Email:</label>
                      <input class="form-control" type="email" name="email" id="email" placeholder="Adres Email" maxlength="50">
                  </div>
                  <div class="form-group">
                      <label for="password" class="sr-only">Hasło:</label>
                      <input class="form-control" type="password" name="password" id="password" placeholder="Hasło" maxlength="30">
                  </div>
                  <div class="form-group">
                      <label for="password2" class="sr-only">Potwierdź hasło</label>
                      <input class="form-control" type="password" name="password2" id="password2" placeholder="Potwierdź hasło" maxlength="30">
                  </div>
              </div>
              <div class="modal-footer">
                  <input class="btn green btn-lg" name="signup" type="submit" value="Załóż konto">
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
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="ajaxLogin.js"></script>

  </body>
</html>