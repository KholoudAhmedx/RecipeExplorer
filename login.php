
<!DOCTYPE html>
<html>
<head>

	<title>RecipeExplorer</title>

	<!--To make the code responsive-->
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">  


    <!--Icons-->
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Bootstrap CDN jsdelivr-->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
	
	<!--Css file-->
	<link rel="stylesheet" type="text/css" href="style.css">

</head>
<body>
	<!--Navigation bar-->
	<div class="container-fluid-nav text-center">
		<h3>
			<span class="material-icons"> restaurant</span>RecipeExplorer
		</h3>
	</div>

	<div class="container d-flex justify-content-center align-items-center"  style=" display: flex;
  flex-direction: column; /* This is the key change */
  justify-content-center; /* Adjust as needed for horizontal alignment */
  align-items-center;">
		<form class="d-flex flex-column" style="width: 35%">
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
		    <label class="form-label">Don't have an account? <a href="#">Register here</a></label>
		  </div">
	  	  <div class="d-grid gap-2 ">
	  	  	<button class="btn" type="button">Login</button>
	  	  </div>
		</form>
	</div>

	
	<footer>
		copyright
	</footer>
</body>
</html>