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
<body class="putmargin">
	<nav class="navbar fixed-top ">
		<div class="container-fluid">
			<h3>
				<a class="navbar-brand" href="#">
					<span class="material-icons"> restaurant</span>RecipeExplorer
				</a>
			</h3>
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="true" aria-label="Toggle navigation">
	        	<span class="navbar-toggler-icon"></span>
	        </button>
		    <div class="collapse navbar-collapse" id="navbarNav">
		        <ul class="navbar-nav ms-auto">
		            <li class="nav-item">
		                <a class="nav-link" href="index.php">Home</a>
		            </li>
		            <li class="nav-item">
		            	<button class="btn nav-link" type="btn" onclick="window.location.href='<?php echo isset($_SESSION['username']) ? 'profile.php' : 'login.php'; ?>'">My Profile</button>
		            </li>
		            <li class="nav-item">
		                <a class="nav-link" href="login.php">Login/Register</a>   

		            </li>
		        </ul>
		    </div>
		</div>
    </nav>
	<div class="container">
		<div class="row">
			<div class="col">
				<h2><?php echo $user['username']; ?></h2>
		    	<p>Contact: <?php echo $user['email']; ?></p>
		    	<p><?php echo $user['description']; ?></p>
		    	<a class="brand-text nav-link btn" href="editprofile.php?id=<?php echo $user['id']; ?>">Edit profile</a>
			</div>
			<div class="col">
				<!--for showing recipes done by me-->
				<main>
					<h3>Recipes by <?php echo $user['username']; ?>:</h3>
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
					        <p class="title"><?php echo htmlspecialchars($recipe['title']); ?></p>
					        <p class="description"><?php echo htmlspecialchars($recipe['description']); ?></p>
					      </div>
					      <div class="card-content right-align">
					        <!--<a class="brand-text nav-link btn" href="details.php?id=<?php echo $recipe['food_id']; ?>">more info</a>-->
					        <a class="brand-text nav-link btn" href="delete.php?id=<?php echo $recipe['food_id']; ?>">Delete </a>
					       	<a class="brand-text nav-link btn" href="updaterecipe.php?id=<?php echo $recipe['food_id']?>">Update</a>
					      </div>
					    </div>
					<?php endforeach; ?>
				</main>
				
			</div>
		</div>
	</div>
    <div class="container">
    	
    </div>
</body>
</html>