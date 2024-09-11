<?php

include('db_config/db_connect.php');
include('sessionconfig/session_config.php');

# For debugging purposes 
#error_reporting(E_ALL); 
#ini_set('display_errors', 1);

$recipe = '';
if(isset($_GET['id']))
{
	# Get the id of each recipe
	$id = mysqli_real_escape_string($conn, $_GET['id']);
	$_SESSION['recipe_id'] = $id;
	#echo $id;

	$query = "SELECT * FROM Food WHERE food_id='$id'";
	$res = mysqli_query($conn, $query);

	# fetches a row as an associative array
	$recipe = mysqli_fetch_assoc($res);

	mysqli_free_result($res);
}

# Get the id of the logged in user;
$user_name=$_SESSION['username'];
$sql = "SELECT id FROM Users WHERE username='$user_name'";
$ress= mysqli_query($conn, $sql);
$id_of_logged_user = mysqli_fetch_assoc($ress);
#print_r($id_of_logged_user);

$_SESSION['logged_user_id'] = $id_of_logged_user['id'];


$errors=array('login_required' => '');
#echo "my recipe id is: ". $_SESSION['recipe_id'];

# Insert comments
if(isset($_POST['submit']) && isset($_SESSION['username']))
{
	if(!empty($_POST['comment']))
	{	

		# This is only to be able to add it inside another query without needing to escape characters like the ''
		$globalvar_user_id = $_SESSION['logged_user_id'];
		#echo $globalvar_user_id;
		$rec_id = $_SESSION['recipe_id'];
		#echo "my recipe_id: ". $rec_id;
		# Input validation
		$comment = htmlspecialchars($_POST['comment']);
		$query = "INSERT INTO Comments(user_id, food_id, comment) VALUES($globalvar_user_id, $rec_id,'$comment')";
		$result = mysqli_query($conn, $query);
		#echo $query;
		if(!$result)
		{
			echo "Error adding comment: " . mysqli_error($conn);
		}


	}
}
else if(isset($_POST['submit']) && !isset($_SESSION['username'])){
	$errors['login_required'] = 'You must login in first';
}

# Display comments of each recipe
if(isset($_GET['id']))
{
	$rec_id = mysqli_real_escape_string($conn, $_GET['id']);
	$query = "SELECT * FROM Comments WHERE food_id='$rec_id'";
	$resullt = mysqli_query($conn, $query);

	if(!$resullt)
	{
		echo "Error displaying comments " . mysqli_error($conn);
	}

	$comments = mysqli_fetch_All($resullt, MYSQLI_ASSOC);

	#print_r($comments);
	
}

?>

<!DOCTYPE html>
<html>
<head>
	<?php include('templates/header.php'); ?>
	<title>recipedetails page</title>
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
              <a class="nav-link" href="<?php echo isset($_SESSION['username']) ? 'profile.php' : 'login.php'; ?>">Profile</a> 
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
            <li class="nav-item">
              <a class="nav-link" href="Register.php">Register </a>
            </li>
            
            

          </ul>
        
      </div>
    </div>
  </nav>
  

	<div class="container">
		<!-- Display info for each recipe -->
		<?php if($recipe): ?>
			<h1><?php echo htmlspecialchars($recipe['title']); ?> </h1>
			<p>By Chef 
				<?php
				$iduser = $recipe['user_id'];
				$query = "SELECT username FROM Users WHERE id='$iduser'";
				$res = mysqli_query($conn, $query);
				$user=mysqli_fetch_assoc($res);
				echo htmlspecialchars($user['username']);
				?> 
			</p>
			<h5><strong>Origin </strong></h5>
			<p><?php echo htmlspecialchars($recipe['category']); ?> </p>
			<h5> <strong>Description</strong> </h5>
			<p><?php echo htmlspecialchars($recipe['description']); ?> </p>
			<h5><strong> Ingredients</strong> </h5>
			<p>
			<?php 
			$set = htmlspecialchars($recipe['Ingredients']);
			$ing = explode(',', $set);
			foreach($ing as $i){
				echo $i."<br/>";
			}	
			?>	
			</p>
			<h5><strong> Instructions</strong> </h5>
			<p><?php echo htmlspecialchars($recipe['instructions']); ?></p>
			<h5><strong>Comments</strong></h5>
			<?php foreach($comments as $comment): ?>
				<div class="container comment-box">
					<!-- Only if you are logged in you can submit comment--> 
					<h5> <?php 

					$usr_id = $comment['user_id'];
					$query = "SELECT username FROM Users WHERE id='$usr_id'";
					$res = mysqli_query($conn, $query);
					$username = mysqli_fetch_assoc($res);
					echo htmlspecialchars($username['username'])	; 
					?> </h5>
					<p> <?php echo htmlspecialchars($comment['comment']); ?></p>
				</div>
			<?php endforeach; ?>
			
			<form class="d-flex flex-column" method="POST" style="width:100%; margin:0px;" action="details.php?id=<?php echo $_SESSION['recipe_id']; ?>">
				<textarea class="form-control" id="comments" rows="4" name="comment" placeholder="Add comment"></textarea>
				<!-- If user is not logged in and want to add comment -->
				<?php if(isset($errors['login_required'])): ?>
			    	<div class="text-danger">
			    		<?php echo $errors['login_required']; ?>
			    	</div>
			    <?php endif; ?>
				<div class="d-flex justify-content-center mt-4 mb-4">
			        <button id="commentsubmit" class="btn submit brand-text nav-link" type="submit" name="submit">Submit Comment</button>
			    </div>
			</form>


		<?php endif; ?>
	</div>
  <?php include('templates/footer2.php'); ?> 

</body>
</html>