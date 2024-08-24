<?php

include('db_config/db_connect.php');
session_start();

if(isset($_GET['id']))
{
	# Get the id of each recipe
	$id = mysqli_real_escape_string($conn, $_GET['id']);
	#echo $id;

	$query = "SELECT * FROM Food WHERE food_id='$id'";
	$res = mysqli_query($conn, $query);

	# fetches a row as an associative array
	$recipe = mysqli_fetch_assoc($res);

	mysqli_free_result($res);
	mysqli_close($conn);
}

?>

<!DOCTYPE html>
<html>
<head>
	<?php include('templates/header.php'); ?>
	<title>recipedetails page</title>
</head>
<body>
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
		<?php if($recipe): ?>
			<h1><?php echo htmlspecialchars($recipe['title']); ?> </h1>
			<p>By Chef: <?php echo htmlspecialchars($recipe['user_id']); ?> </p>
			<h5> <strong>Description:</strong> </h5>
			<p><?php echo htmlspecialchars($recipe['description']); ?> </p>
			<h5><strong> Ingredients:</strong> </h5>
			<p>
			<?php 
			$set = htmlspecialchars($recipe['Ingredients']);
			$ing = explode(',', $set);
			foreach($ing as $i){
				echo $i."<br/>";
			}
			?>	
			</p>
			<h5><strong>Comments:</strong></h5>
			<textarea class="form-control" id="comments" rows="4" name="comments" placeholder="Add comment"></textarea>

		<?php endif; ?>
	</div>
    <div class="col-md-12 text-center mt-4">
        <a class="brand-text nav-link btn" href="#">Submit Comment</a>
    </div>

</body>
</html>