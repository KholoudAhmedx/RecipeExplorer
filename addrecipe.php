<?php

include('sessionconfig/session_config.php');
include('db_config/db_connect.php');

# Secure Redirection
if(!isset($_SESSION['username']))
{
	header('Location:login.php');
	exit;
}

$title=$description=$ingredients=$instructions=$category=$img_file=$author_id='';
$errors = array('title'=>'', 'description'=>'', 'ingredients'=>'','instrc' =>'', 'category'=>'', 'img_file'=> '');

$flag = false;

if(isset($_POST['submit']))
{
	# Some Checks
	if(empty($_POST['title']))
	{
		$errors['title'] = 'You must provide a recipe title';
	}
	else{
		$title = htmlspecialchars($_POST['title']);
	}

	if(empty($_POST['desc']))
	{
		$errors['description'] = 'You must provide a description';
	}
	else
	{
		$description = htmlspecialchars($_POST['description']);
	}
	
	if(empty($_POST['ingredients']))
	{
		$errors['ingredients'] = 'You must provide the ingredients';
	}
	else
	{
		$ingredients=htmlspecialchars($_POST['ingredients']);

		if (!preg_match('/^([^,]+(?:, ?[^,]+)*)$/', $ingredients)) {
	        $errors['ingredients'] = 'Please enter ingredients in the correct format, separated by commas.';
	    }
	}

	if(empty($_POST['instrc']))
	{
		$errors['instrc'] = 'You must provide the instructions of making the recipe';
	}
	else
	{
		$instructions = htmlspecialchars($_POST['instrc']);
	}

	if(empty($_POST['category']))
	{
		$errors['category']= 'Please choose a category';
	}
	else{
		$category= htmlspecialchars($_POST['category']);
	}

	if(empty($_FILES['img']['name']))
	{
		$errors['img_file'] = 'Please provide an image for the recipe';
	}
	else
	{
		# Read filename
		$img_file = htmlspecialchars($_FILES['img']['name']);

		# Allow specifc extensions 
		$ext = pathinfo($img_file, PATHINFO_EXTENSION);
		$allowedTypes = array("jpg","jpeg","png");

		## Target path of the image
		$target_dir = "/opt/lampp/htdocs/RecipeExplorer/RecipeExplorer/uploads";
		$target_path = $target_dir. "/". $img_file;

		$temp_name = htmlspecialchars($_FILES['img']['tmp_name']);

		if(in_array($ext, $allowedTypes))
		{
			# Upload image file
			if(move_uploaded_file($_FILES['img']['tmp_name'], $target_path))
			{
				# If the file is uploaded correctly, save it to the database
				$flag= true;
			}
			 else {
	            echo "Error uploading file: " . error_get_last()['message'];
	        }
	    }
		else
		{
			$errors['img_file'] = $ext." "."extension is not allowed";
			#echo $errors['allowed_ext'];
		}
	}

	
	if(!array_filter($errors))
	{

		# Insert data into the database
		$title=mysqli_real_escape_string($conn,$_POST['title']);
		$description=mysqli_real_escape_string($conn, $_POST['desc']);
		$ingredients=mysqli_real_escape_string($conn, $_POST['ingredients']);
		$instructions=mysqli_real_escape_string($conn, $_POST['instrc']);

		$category=mysqli_real_escape_string($conn, $_POST['category']);
		$author = $_SESSION['username'];

		# To select author_id
		$sql = "SELECT id, username FROM Users WHERE username='$author'";
		$result = mysqli_query($conn, $sql);
		$count = mysqli_num_rows($result);

		if($count > 0)
		{
			while($row = mysqli_fetch_assoc($result)){
				$author_id= $row['id'];
			}	
		}

		if($flag==true)
		{
			#echo 'my image is updated corrrectly';
			$img_file=mysqli_real_escape_string($conn, $_FILES['img']['name']);
			
		}

		# SQL
		$sql = "INSERT INTO Food(title,description, Ingredients,instructions, category, imagefile,user_id) VALUES('$title', '$description', '$ingredients','$instructions', '$category', '$img_file', '$author_id')";

		# Save to db
		if(mysqli_query($conn,$sql))
		{
			# Success
			header('Location: index.php');
		}
		else
		{
			die('Query error:'.mysqli_error($conn));
		}
		
	}
}


?>

<!DOCTYPE html>
<html>
<head>
	<?php include('templates/header.php'); ?>
</head>
<body class="d-flex flex-column min-vh-100">
	<nav class="navbar fixed-top navbar-expand-md navbar-light">
	    <div class="container-xxl">
	      <a class="navbar-brand" href="index.php">
	        <span class="material-icons"> restaurant</span>RecipeExplorer-Add a New Recipe
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
  
    <!-- forum-->
    <div class="container d-flex justify-content-center align-items-center">
    	<form class="d-flex flex-column" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" enctype="multipart/form-data">

    		<div class="mb-3">
    			<lable for="title" class="form-label"><strong>Recipe Title</strong></lable> 
    			<input type="text" id = "title" name="title" class="form-control" placeholder="Enter recipe title">
    			<?php if(isset($errors['title'])): ?>
    				<div class="text-danger">
    					<?php echo $errors['title']; ?>
    				</div>
				<?php endif; ?>
    		</div>

    		<div class="mb-3">
    			<label for="desc" class="form-lable"><strong>Description</strong></label>
    			<textarea class="form-control" id="desc" rows="4" placeholder="Descripe the recipe" name="desc"></textarea>
    			<?php if(isset($errors['description'])): ?>
    				<div class="text-danger">
    					<?php echo $errors['description']; ?>
    				</div>
				<?php endif; ?>
    		</div>

    		<div class="mb-3">
    			<lable for="ingredients" class="form-lable"><strong>Ingredients</strong></lable>
    			<textarea class="form-control" id="ingredients" rows="4" placeholder="List the ingredients" name="ingredients"></textarea>
    			<?php if(isset($errors['ingredients'])): ?>
    				<div class="text-danger">
    					<?php echo $errors['ingredients']; ?>
    				</div>
				<?php endif; ?>
    		</div>
    		<div class="mb-3">
    			<lable for="instrc" class="form-lable"><strong>Instructions for making the recipe</strong></lable>
    			<textarea class="form-control" id="instr" rows="4" placeholder="Give your instructions" name="instrc"></textarea>
    			<?php if(isset($errors['instrc'])): ?>
    				<div class="text-danger">
    					<?php echo $errors['instrc']; ?>
    				</div>
				<?php endif; ?>
    		</div>
    		
    		<div class="mb-3">
    			<select class="form-select" aria-label="Default select example" name="category" required>
    				<option value=""> --Choose the Orign--</option>
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
    			<?php if(isset($errors['img_file'])): ?>
    				<div class="text-danger">
    					<?php echo $errors['img_file']; ?>
    				</div>
				<?php endif; ?>
    		</div>

    		<div class="d-grid gap-2">
    			<button class="btn submit" type="btn submit" name="submit">Submit</button>
    		</div>
    	</form>
    </div>
    <?php include('templates/footer2.php'); ?>
</body>
</html>