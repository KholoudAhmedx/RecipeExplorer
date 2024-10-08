<?php

/*
error_reporting(E_ALL);
ini_set('display_errors', 1);*/

session_start();
include('db_config/db_connect.php');

// Ensure the session persists
if (!isset($_SESSION['username'])) {
    // Redirect to login page if session is lost
    header("Location: login.php");
    exit;
}	

if(isset($_GET['id']))
{
	$recipe_id = mysqli_real_escape_string($conn, $_GET['id']);
	$query = "SELECT * From Food WHERE food_id='$recipe_id'";
	$res = mysqli_query($conn, $query);
	$recipes = mysqli_fetch_assoc($res);
	$_SESSION['recipe_id'] = $recipe_id;
	mysqli_free_result($res);
}


if(isset($_POST['submit'])){
	echo "my id is: ". $_SESSION['recipe_id'] ;
	$food_id = $_SESSION['recipe_id'];
	// Initialize an array to hold the fields to be updated
    $updates = [];

    // Check each field and add it to the updates array if it's not empty
    if (!empty($_POST['title'])) {
        $title = mysqli_real_escape_string($conn, $_POST['title']);
        $updates[] = "title='$title'";
    }

    if (!empty($_POST['desc'])) {
        $desc = mysqli_real_escape_string($conn, $_POST['desc']);
        $updates[] = "description='$desc'";
    }

    if (!empty($_POST['ingredients'])) {
        $ingredients = mysqli_real_escape_string($conn, $_POST['ingredients']);
        $updates[] = "Ingredients='$ingredients'";
    }
    if (!empty($_POST['instrc'])) {
        $instructions = mysqli_real_escape_string($conn, $_POST['instrc']);
        $updates[] = "instructions='$instructions'";
    }


    if (!empty($_POST['category'])) {
        $category = mysqli_real_escape_string($conn, $_POST['category']);
        $updates[] = "category='$category'";
    }

    if(!empty($_POST['img']))
    {
    	$img = mysqli_real_escape_string($conn, $_POST['img']);
    	$updates[] = "imagefile='$img'";
    }

    // Only update if there are fields to update
    if (!empty($updates)) {
        // Convert the updates array into a comma-separated string
        $update_query = "UPDATE Food SET " . implode(', ', $updates) . " WHERE food_id='$food_id'";
        
        #echo $update_query;

        if (mysqli_query($conn, $update_query)) {
            echo "Profile updated successfully!";
            header("Location: details.php?id=$food_id");
            exit();

        } else {
            echo "Error updating profile: " . mysqli_error($conn);
        }
    } else {
        echo "No fields were updated.";
    }
    # Update session username with the new updated values;
    $_SESSION['username'] = $username;
}
?>


<!DOCTYPE html>
<html>
<head>
	<?php include('templates/header.php'); ?>
	<title>Edit Profile</title>
</head>
<body class="d-flex flex-column min-vh-100" style="margin-top: 100px;">
	<!-- Navbar -->
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
	<!--form-->
	<div class="container">
		<div class="row">
			<div class="col">

			</div>
			<div class="col-6">
				<div class="container d-flex justify-content-center align-items-center" >
					<form class="d-flex flex-column" method="POST" style="width:100%; margin:0px; "action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
						<div class="mb-3">
							<h3><strong>Update Your Recipe</strong></h3>
						</div>
						<div class="mb-3">
							<label for ="title"><strong>Recipe Title</strong></label>
							<input type="text" name="title" class="form-control" id="title" placeholder="Enter a new title">
						</div>
						<div class="mb-3">
							<label for="desc"><strong>Description</strong></label>
							<textarea  name="desc" class="form-control" rows="4" id="desc" placeholder="Enter new description"></textarea>
						</div>
						<div class="mb-3">
			    			<lable for="ingredients" class="form-lable"><strong>Ingredients</strong></lable>
			    			<textarea class="form-control" id="ingredients" rows="4" placeholder="List the ingredients" name="ingredients"></textarea>
			    		</div>
			    		<div class="mb-3">
			    			<lable for="instrc" class="form-lable"><strong>Instructions </strong></lable>
			    			<textarea class="form-control" id="instrc" rows="4" placeholder="Give your instructions" name="instrc"></textarea>
			    		</div>
						<div class="mb-3">
			    			<select class="form-select" aria-label="Default select example" name="category">
			    				<option Selected> --Choose the Orign--</option>
			    				<option value="Egyptian">Egyptian</option>
								<option value="Moroccan">Moroccan</option>
								<option value="Lebanese">Lebanese</option>
								<option value="Syrian">Syrian</option>
								<option value="Tunisian">Tunisian</option>
								<option value="Algerian">Algerian</option>
								<option value="Jordanian">Jordanian</option>
								<option value="Palestinian">Palestinian</option>
								<option value="Iraqi">Iraqi</option>
								<option value="Kuwaiti">Kuwaiti</option>
								<option value="Saudi Arabian">Saudi Arabian</option>
								<option value="Emirati">Emirati</option>
								<option value="Omani">Omani</option>
								<option value="Qatari">Qatari</option>
								<option value="Bahraini">Bahraini</option>
								<option value="Yemeni">Yemeni</option>
								<option value="Libyan">Libyan</option>
								<option value="Sudanese">Sudanese</option>
			    			</select>
			    		</div>

			    		<div class="mb-3">
			    			<label for="img" class="form-lable"><strong>Recipe Image</strong></label>
			    			
			    		</div>
			    		<div class="mb-3">
			    			<input type="file" id="img" name="img" class="form-control-file">
			    		</div>

			    		<div class="d-grid gap-2">
			    			<button class="btn submit" type="btn submit" name="submit">Submit</button>
			    		</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<?php include('templates/footer2.php'); ?>
</body>
</html>