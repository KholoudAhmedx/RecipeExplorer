
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
		<form class="d-flex flex-column" method="POST" action="index.php">

		  <div class="mb-3">
		  	<label class="form-label" id="form-title"> Login to Your Account</label>
		  </div>

		  <div class="mb-3">
		    <label for="username" class="form-label">Username</label>
		    <input type="text" class="form-control" id="username" placeholder="Enter your username">
		  </div>

		  <div class="mb-3 ">
		    <label for="password" class="form-label">Password</label>
		    <input type="password" class="form-control" id="password" placeholder="Enter your password">
		  </div>

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
</html>