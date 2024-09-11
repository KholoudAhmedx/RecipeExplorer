<?php 

include('sessionconfig/session_config.php');
include('db_config/db_connect.php');

$sql = "SELECT * FROM Food";
$result = mysqli_query($conn, $sql);

$recipes = mysqli_fetch_all($result, MYSQLI_ASSOC);

mysqli_free_result($result);
mysqli_close($conn);
?>
<!DOCTYPE html>
 <html>
 <head>
 	<?php include('templates/header.php'); ?>

 </head>
 <body class="d-flex flex-column min-vh-100">
  <nav class="navbar fixed-top navbar-expand-md navbar-light">
    <div class="container-xxl">
      <a class="navbar-brand" href="index.php">
        <span class="material-icons"> restaurant</span>RecipeExplorer
      </a>

      <!-- Toggle button for mobile div -->
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#main-nav" aria-controls="main-nav" aria-expanded="false" aria-label="Toggle navigation">
        <!-- Button toggler -->
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- navbar links -->
      <div class="collapse navbar-collapse justify-content-end align-center" id="main-nav">
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link" href="<?php echo isset($_SESSION['username']) ? 'addrecipe.php' : 'login.php'; ?>"> Add Recipe</a>
            </li>
            <?php if(isset($_SESSION['username'])): ?>
              <li class="nav-item">
                <a class="nav-link" href="profile.php">Profile</a> 
              </li> 
            <?php endif; ?>
            <li class="nav-item">
              <a class="nav-link" href="Register.php">Register </a>
            </li>
            
            <!--Show login/logout buttons based on the status of the user-->
            <?php if(isset($_SESSION['username'])): ?>
            <li class="nav-item">
              <a class="nav-link" href="logout.php">Logout </a>
            </li>
            <?php else: ?>
            <li class="nav-item">
              <a class="nav-link" href="login.php">Login </a>
            </li>
            <?php endif; ?>

            
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
      <div class="caption">
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
  <?php include('templates/footer2.php'); ?>
  </body>
</html>
