<?php 

session_start();
# Include the db_config file
include('db_config/db_connect.php');

$username = $email= $password= $cpassword = '';
# List of errors
$errors = array('username'=>'', 'email'=>'', 'password'=>'', 'cpassword'=>'', 'existeduser' =>'', 'existedemail' => '', 'desc_length' => '');


# Get stored usernames in the database;
$sql = "SELECT username, email from Users";
$res = mysqli_query($conn, $sql);
$stored_info = mysqli_fetch_all($res, MYSQLI_ASSOC);
#print_r($stored_info);

# Store in global variable to be seen in all scopes 
$_SESSION['stored_info'] = $stored_info;
#print_r($_SESSION['stored_info']);

if(isset($_POST['submit']))
{
	# Checks
	if(!empty($_POST['username']))
	{
		# Avoid special characters
		$username = htmlspecialchars($_POST['username']);
		#echo $no_stored_usernames;
		# submitted username; 
		foreach($_SESSION['stored_info'] as $singleusername)
		{
			if($singleusername['username'] === $username)
			{
				$errors['existeduser'] = 'User already exists';
				break;
			}
		}


	}

	if(!empty($_POST['email']))
	{
		$email = htmlspecialchars($_POST['email']);
		if(!filter_var($email, FILTER_VALIDATE_EMAIL))
		{
			$errors['email'] = 'Invalid email address, please provide a correct one.';
		}

		# If email already exists 
		foreach($_SESSION['stored_info'] as $singleusername)
		{
			if($singleusername['email'] === $email)
			{
				$errors['existedemail'] = 'Email already exists';
				break;
			}
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

	if(!empty($_POST['desc']))
	{
		$description = htmlspecialchars($_POST['desc']);

		# Check lengtth
		if(strlen($description) > 255)
		{
			$errors['desc_length'] = 'Your description is too long';
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
		$description=mysqli_real_escape_string($conn, $_POST['desc']);

		# SQL query
		$sql = "INSERT INTO Users(username, email, password, cpassword, description) VALUES('$username', '$email', PASSWORD('$password'), PASSWORD('$cpassword'), '$description')";

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
		    <?php if(isset($errors['existeduser'])): ?>
		    	<div class="text-danger">
		    		<?php echo $errors['existeduser']; ?>
		    	</div>
	    	<?php endif; ?>
		  </div>

		  <div class="mb-3">
		    <label for="email" class="form-label">Email</label>
		    <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
		    <?php if(isset($errors['email'])): ?>
			    <div class="text-danger">
			    	<?php echo $errors['email']; ?>
			    </div>
		    <?php endif; ?>
		    <?php if(isset($errors['existedemail'])): ?>
			    <div class="text-danger">
			    	<?php echo $errors['existedemail']; ?>
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

		  <div class="mb-3 ">
		    <label for="desc" class="form-label">Description</label>
		    <textarea class="form-control" id="desc" rows="4" placeholder="Give a description about yourself" name="desc" required></textarea>
		    <?php if(isset($errors['desc_length'])): ?>
		    	<div class="text-danger">
		    		<?php echo $errors['desc_length']; ?>
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