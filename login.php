<?php

include('db_config/db_connect.php');

session_start();
$username = $password = '';
$errors = array('username'=> '', 'password' =>'', 'login' => '');

if(isset($_POST['submit']))
{
	## Validation Checks
	if(empty($_POST['username']))
	{
		$errors['username'] = 'You must provide a username';
	}

	$username = htmlspecialchars($_POST['username']);

	if(empty($_POST['password']))
	{
		$errors['password'] = 'You must provide a password';
	}

	$password = htmlspecialchars($_POST['password']);

	if(!array_filter($errors))
	{
		# SQL query 
		$sql = "SELECT * FROM Users WHERE username='$username 'AND password=PASSWORD('$password')";

		# Executes the query >> returns either false if no resource existed or the records found
		$result = mysqli_query($conn, $sql);
		$count = mysqli_num_rows($result);

		if($count == 1)
		{
			$_SESSION['username'] = $username;
			header('Location: index.php');
			exit();

		}
		else
		{
			$errors['login'] = 'Login failed, please try again';
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

	<div class="container d-flex justify-content-center align-items-center">
		<form class="d-flex flex-column" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">

		  <div class="mb-3">
		  	<label class="form-label" id="form-title"> Login to Your Account</label>
		  </div>

		  <div class="mb-3">
		    <label for="username" class="form-label">Username</label>
		    <input type="text" class="form-control" id="username" placeholder="Enter your username" name="username" required>
		    <?php if(isset($errors['username'])): ?>
		    	<div class="text-danger">
		    		<?php echo $errors['username']; ?>
		    	</div>
		    <?php endif; ?>
		  </div>

		  <div class="mb-3 ">
		    <label for="password" class="form-label">Password</label>
		    <input type="password" class="form-control" id="password" placeholder="Enter your password" name="password" required >
		    <?php if(isset($errors['password'])): ?>
		    	<div class="text-danger">
		    		<?php echo $errors['password']; ?>
		    	</div>
		    <?php endif; ?>
		  </div>

		  <?php if(isset($errors['login'])): ?>
		    	<div class="text-danger">
		    		<?php echo $errors['login']; ?>
		    	</div>
		    <?php endif; ?>

		  <div class="mb-3">
		    <label class="form-label">Don't have an account? <a href="Register.php">Register here</a></label>
		  </div">

	  	  <div class="d-grid gap-2 ">
	  	  	<button class="btn submit" type="button submit" name="submit">Login</button>
	  	  </div>

		</form>
	</div>

	<?php include('templates/footer.php'); ?>
</body>
<script type="text/javascript">

	document.addEventListener("DOMContentLoaded", function() {
    // Select all required input fields
    const requiredFields = document.querySelectorAll('input[required], textarea[required]');

    requiredFields.forEach(function(field) {
        // Get the corresponding label using the 'for' attribute
        const label = document.querySelector(`label[for="${field.id}"]`);
        if (label) {
            // Append an asterisk (*) to the label
            label.innerHTML += '<span style="color: #FF0000;"> *</span>';
        }
    });
});
</script>
</html>