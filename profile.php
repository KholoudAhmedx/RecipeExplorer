<?php

session_start();
include('db_config/db_connect.php');

if(!isset($_SESSION['username']))
{
	header('Location: login.php');
}

# Get details of the signed in user
$usersession = $_SESSION['username'];
#echo $usersession;

$query = "SELECT * FROM Users WHERE username='$usersession'";
$res = mysqli_query($conn, $query);
$user=mysqli_fetch_assoc($res);

#print_r($user);

?>
<!DOCTYPE html>
<html>
<head>
	<?php include('templates/header.php'); ?>
	<title>Profile </title>
</head>
<body class="putmargin d-flex flex-column min-vh-100">
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
	<div class="container">
		<div class="row">
			<div class="col"  style="margin-top: 20px;">
				<h2><strong><?php echo "Chef"." ". $user['username']; ?></strong></h2>
		    	<p>Contact: <?php echo $user['email']; ?></p>
		    	<p><?php echo $user['description']; ?></p>
		    	<a class="brand-text nav-link btn" href="editprofile.php">Edit profile</a>
			</div>
			<div class="col">
				<!--for showing recipes done by me-->
				<main>
					<h3><strong>Recipes by <?php echo $user['username']; ?></strong></h3>
					<?php

					# Get the details of each recipe made by each user
					$User_id = $user['id'];
					$q = "SELECT * FROM Food WHERE user_id='$User_id'";
					$r = mysqli_query($conn, $q);
					$recipes= mysqli_fetch_all($r, MYSQLI_ASSOC);
					#print_r($recipes);
					?>

					<?php foreach($recipes as $recipe): ?>
						<div class="card mt-5">
					      <div class="image">
					      	<!-- Wrapping the image with a link to the page that shows info of each recipe -->
					      	<a href="details.php?id=<?php echo $recipe['food_id']; ?>">
					        	<img src="<?php $filename=$recipe['imagefile']; $imageURL="uploads/".$filename; echo $imageURL;?>">
					        </a>
					      </div>
					      <div class="caption">
					        <!--author -->
					        <p class="title"><strong><?php echo htmlspecialchars($recipe['title']); ?></strong></p>
					        <p class="description"><?php echo htmlspecialchars($recipe['description']); ?></p>
					      </div>
					      <div class="card-content right-align">
					        <!--<a class="brand-text nav-link btn" href="details.php?id=<?php echo $recipe['food_id']; ?>">more info</a>-->
					        <a class="brand-text nav-link btn mt-4" href="delete.php?id=<?php echo $recipe['food_id']; ?>">Delete </a>
					       	<a class="brand-text nav-link btn mt-4" href="updaterecipe.php?id=<?php echo $recipe['food_id']?>">Update</a>
					      </div>
					    </div>
					<?php endforeach; ?>
				</main>
				
			</div>
		</div>
	</div>
   <?php include('templates/footer2.php'); ?>
</body>
</html>