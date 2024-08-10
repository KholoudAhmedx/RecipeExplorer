<?php 

# Include the db_config file
include('db_config/db_connect.php');

$username = $email= $password= $cpassword = '';
# List of errors
$errors = array('username'=>'', 'email'=>'', 'password'=>'', 'cpassword'=>'');

if(isset($_POST['submit']))
{
	# Checks
	if(!empty($_POST['username']))
	{
		# Avoid special characters
		$username = htmlspecialchars($_POST['username']);
	}

	if(!empty($_POST['email']))
	{
		$email = htmlspecialchars($_POST['email']);
		if(!filter_var($email, FILTER_VALIDATE_EMAIL))
		{
			$errors['email'] = 'Invalid email address, please provide a correct one.';
		}
	}


	if(!empty($_POST['password'] && !empty($_POST['cpassword'])))
	{
		$password = htmlspecialchars($_POST['password']);
		$cpassword = htmlspecialchars($_POST['cpassword']);

		if($password != $cpassword)
		{
			$errors['cpassword'] = 'Passwords do not match';
		}
		if(!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password))
		{
			$errors['password'] = 'Password must have at least 8 character length with mimimum 1 uppercase, 1 lowercase, 1 number and 1 special characters.\n';
		}

	}

	## Database
	if(!array_filter($errors))
	{
		# Insert data from the user to the database
		$username = mysqli_real_escape_string($conn, $_POST['username']);
		$email = mysqli_real_escape_string($conn, $_POST['email']);
		$password = mysqli_real_escape_string($conn, $_POST['password']);
		$cpassword = mysqli_real_escape_string($conn, $_POST['cpassword']);


		# SQL query
		$sql = "INSERT INTO Users(username, email, password, cpassword) VALUES('$username', '$email', PASSWORD('$password'), PASSWORD('$cpassword'))";

		# Save to db
		if(mysqli_query($conn, $sql))
		{
			# Success 
			header('Location: login.php');

		}
		else
		{
			echo 'Query error'.mysqli_error($conn);
		}
	}
}

?>

<!DOCTYPE html>
<html>
<head>
	<?php include('templates/header.php'); ?>
</head>

<body>
	<!--Navigation bar-->
	<div class="container-fluid-nav text-center">
		<h3>
			<span class="material-icons"> restaurant</span>RecipeExplorer
		</h3>
	</div>

	<div class="container d-flex justify-content-center align-items-center" >
		<form class="d-flex flex-column" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
		  <div class="mb-3">
		  	<label class="form-label " id="form-title"> Register</label>
		  </div>

		  <div class="mb-3">
		    <label for="username" class="form-label">Username</label>
		    <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username" required>
		    <?php if(isset($errors['username'])): ?>
			    <div class="text-danger">
			    	<?php echo $errors['username']; ?>
			    </div>
		    <?php endif; ?>
		  </div>

		  <div class="mb-3">
		    <label for="username" class="form-label">Email</label>
		    <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
		    <?php if(isset($errors['email'])): ?>
			    <div class="text-danger">
			    	<?php echo $errors['email']; ?>
			    </div>
		    <?php endif; ?>
		  </div>

		  <div class="mb-3 ">
		    <label for="password" class="form-label">Password</label>
		    <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
		    <?php if(isset($errors['password'])): ?>
		    	<div class="text-danger">
		    		<?php echo $errors['password']; ?>
		    	</div>
		    <?php endif; ?>
		  </div>
		  

		  <div class="mb-3">
		    <label for="cpassword" class="form-label">Confirm password</label>
		    <input type="password" class="form-control" id="cpassword" name="cpassword" placeholder="Confirm your password" required>
		    <?php if(isset($errors['cpassword'])): ?>
		    	<div class="text-danger">
		    		<?php echo $errors['cpassword']; ?>
		    	</div>
		    <?php endif; ?>
		  </div>

		  <div class="mb-3">
		    <label class="form-label">Already have an account? <a href="login.php">login here</a></label>
		  </div">

	  	  <div class="d-grid gap-2 ">
	  	  	<button class="btn submit" type="button submit" name="submit">Register</button>
	  	  </div>
		</form>
	</div>
	<?php include('templates/footer.php'); ?>
</body>
</html>