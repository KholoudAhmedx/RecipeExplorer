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
    	<form class="d-flex flex-column" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">

    		<div class="mb-3">
    			<lable for="title" class="form-label"><strong>Recipe Title</strong></lable> 
    			<input type="text" id = "title" name="title" class="form-control" placeholder="Enter recipe title">
    		</div>

    		<div class="mb-3">
    			<label for="desc" class="form-lable"><strong>Description</strong></label>
    			<textarea class="form-control" id="desc" rows="4" placeholder="Descripe the recipe"></textarea>
    		</div>

    		<div class="mb-3">
    			<lable for="ingredients" class="form-lable"><strong>Ingredients</strong></lable>
    			<textarea class="form-control" id="ingredients" rows="4" placeholder="List the ingredients"></textarea>
    		</div>
    		
    		<div class="mb-3">
    			<label for="category" class="form-lable"><strong>Category</strong></label>
    			<input type="text" id="category" name="category" class="form-control" placeholder="Enter recipe category">
    		</div>

    		<div class="mb-3">
    			<label for="img" class="form-lable"><strong>Recipe Image</strong></label>
    			
    		</div>
    		<div class="mb-3">
    			<input type="file" id="img" name="imgfile" class="form-control-file">
    		</div>

    		<div class="d-grid gap-2">
    			<button class="btn submit" type="btn submit" name="submit">Submit</button>
    		</div>
    	</form>
    </div>
</body>
</html>