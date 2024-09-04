<?php

session_start();

include('db_config/db_connect.php');

# Secure Redirection
if(!isset($_SESSION['username']))
{
	header('Location:login.php');
	exit;
}


$title=$description=$ingredients=$category=$img_file=$author_id='';
$errors = array('title'=>'', 'description'=>'', 'ingredients'=>'', 'category'=>'', 'img_file'=> '');

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
		if(!preg_match('/^(\s*(\d+(?:\/\d+)?\s*\w+)?\s*[\w\s\-]+(?:\((optional)\))?\s*,\s*)*\s*(\d+(?:\/\d+)?\s*\w+)?\s*[\w\s\-]+(?:\((optional)\))?\s*$/i', $ingredients))
		{
			$errors['ingredients'] = 'Please enter ingredients in the correct format. Each ingredient should be comma-separated and may include:
			    - A quantity (e.g., "1/2", "2")
			    - A measurement (e.g., "cup", "tbsp", "g")
			    - An ingredient name (e.g., "sugar", "flour")
			    - An optional description or note in parentheses (e.g., "optional", "finely chopped")';
		}
	}

	

	if(empty($_POST['category']))
	{
		$errors['category']= 'Please provide a category';
		#echo $errors['category'];
	}
	else{

		$category= htmlspecialchars($_POST['category']);

	}

	if(empty($_FILES['img']['name']))
	{
		$errors['img_file'] = 'Please provide an image for the recipe';
		#echo $errors['img_file'];
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
			echo 'my image is updated corrrectly';
			$img_file=mysqli_real_escape_string($conn, $_FILES['img']['name']);
		}

		# SQL
		$sql = "INSERT INTO Food(title,description, Ingredients, category, imagefile,user_id) VALUES('$title', '$description', '$ingredients', '$category', '$img_file', '$author_id')";

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
<body>
	<nav class="navbar fixed-top">
		<div class="cotainer-fluid">
			<h3>
				<a class="navbar-brand" href="#">
					<span class="material-icons"> restaurant</span>RecipeExplorer-Add a New Recipe
				</a>
			</h3>
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
    			<label for="category" class="form-lable"><strong>Category</strong></label>
    			<input type="text" id="category" name="category" class="form-control" placeholder="Enter recipe category">
    			<?php if(isset($errors['category'])): ?>
    				<div class="text-danger">
    					<?php echo $errors['category']; ?>
    				</div>
				<?php endif; ?>
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
</body>
</html>