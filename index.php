<?php 
session_start();
include('db_config/db_connect.php');

$sql = "SELECT * FROM Food";
$result = mysqli_query($conn, $sql);

$recipes = mysqli_fetch_all($result, MYSQLI_ASSOC);
#print_r($recipes);

mysqli_free_result($result);

mysqli_close($conn);
/*$count = mysqli_num_rows($result);
if($count > 0)
{
  while($row=mysqli_fetch_assoc($result)){
    $title = $row['title'];
    $filename = $row['imagefile'];
    $imageURL ="uploads/".$filename;
    echo "<img src='$imageURL'>";
    echo "<h3>$title</h3>";
  }
}*/

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
        Lebanon
      </a>
      <a class="navbar-item nav-link" href="#">
        Moroccon 
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
          <li class="nav-item">
            <button class="btn nav-link" type="btn" onclick="window.location.href='<?php echo isset($_SESSION['username']) ? 'profile.php' : 'login.php'; ?>'">My Profile</button>
          </li>
        </ul>
      </div>
    </div>
  </nav> 

  <!-- Display images from the database: -->
  <main>
    <?php foreach($recipes as $recipe): ?>
    <div class="card mt-5">
      <div class="image">
        <img src="<?php $filename=$recipe['imagefile']; $imageURL="uploads/".$filename; echo $imageURL;?>">
      </div>
      <div class="caption ">
        <!--author -->
        <p class="title"><strong><?php echo htmlspecialchars($recipe['title']); ?></strong></p>
        <p class="description"><?php echo htmlspecialchars($recipe['description']); ?></p>
      </div>
      <div class="card-content right-align mt-4">
        <a class="brand-text nav-link btn" href="details.php?id=<?php echo $recipe['food_id']; ?>">more info</a>
      </div>
    </div>
  <?php endforeach; ?>
  </main>
  
  <!-- Bottom navbar -->
  <div class="navbar mybottomnav">
    <a href="home">About us </a>
    <a href="home">Contact </a>
    <a href="home">Privacy policy</a>
  </div>

 </body>
 </html>
