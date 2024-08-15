<?php 
session_start();
?>
<!DOCTYPE html>
 <html>
 <head>
 	<?php include('templates/header.php'); ?>
 </head>
 <body>
  <nav class="navbar navbar-light fixed-top">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">
        <span class="material-icons"> restaurant</span>RecipeExplorer
      </a>

      <a class="navbar-item nav-link" href="#">
        Egyption
      </a>
      <a class="navbar-item nav-link" href="#">
        Japanese
      </a>
      <a class="navbar-item nav-link" href="#">
        Italian 
      </a>
      <a class="navbar-item nav-link " href="#">
        Mexican
      </a>
      
      <!-- To add new recipes you have to be logged in-->
      <button class="btn" type="btn" onclick="window.location.href='<?php echo isset($_SESSION['username']) ? 'addrecipe.php' : 'login.php'; ?>'">Add Recipe </button>

      <!--Drop menue for login & register buttons--> 
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">

          <!--Show login/logout buttons based on the status of the user-->
          <?php if(isset($_SESSION['username'])): ?>
            <li class="nav-item"><a class="nav-link" href="logout.php">Logout </a></li>
          <?php else: ?>
            <li class="nav-item"><a class="nav-link" href="login.php">Login </a></li>
          <?php endif; ?>

          <!--Register-->
          <li class="nav-item"><a class="nav-link" href="Register.php">Register </a></li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Bottom navbar -->
  <div class="navbar mybottomnav">
    <a href="home">About us </a>
    <a href="home">Contact </a>
    <a href="home">Privacy policy</a>
  </div>

 </body>
 </html>