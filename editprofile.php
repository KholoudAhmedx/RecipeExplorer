<?php 
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('db_config/db_connect.php');
$username=$email=$password=$cpassword=$description=' ';


if(isset($_GET['id']))
{
    $user_id = mysqli_real_escape_string($conn, $_GET['id']);
    $query = "SELECT * FROM Users WHERE id='$user_id'";
    $res = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($res);
    $_SESSION['user_id'] = $user_id; // Store user_id in session
    mysqli_free_result($res);
    // No need to close the connection here
}

// Ensure the session persists
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if session is lost
    header("Location: login.php");
    exit;
}

if (isset($_POST['submit'])) {
    // Get the user ID from the session
    $user_id = $_SESSION['user_id'];

    // Initialize an array to hold the fields to be updated
    $updates = [];

    // Check each field and add it to the updates array if it's not empty
    if (!empty($_POST['username'])) {
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $updates[] = "username='$username'";
    }

    if (!empty($_POST['email'])) {
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $updates[] = "email='$email'";
    }

    if (!empty($_POST['pass'])) {
        $password = PASSWORD(mysqli_real_escape_string($conn, $_POST['pass']));
        $cpassword = $password;
        $updates[] = "password='$password'";
        $updates[] = "cpassword='$cpassword'";
    }

    if (!empty($_POST['desc'])) {
        $description = mysqli_real_escape_string($conn, $_POST['desc']);
        $updates[] = "description='$description'";
    }

    // Only update if there are fields to update
    if (!empty($updates)) {
        // Convert the updates array into a comma-separated string
        $update_query = "UPDATE Users SET " . implode(', ', $updates) . " WHERE id='$user_id'";
        
        #echo $update_query;

        if (mysqli_query($conn, $update_query)) {
            #echo "Profile updated successfully!";
            header('Location: profile.php?');
        } else {
            echo "Error updating profile: " . mysqli_error($conn);
        }
    } else {
        echo "No fields were updated.";
    }
    # Update session username with the new updated values;
    $_SESSION['username'] = $username;
}

// Close the database connection
mysqli_close($conn);
?>
<!DOCTYPE html>
<html>
<head>
	<?php include('templates/header.php'); ?>
	<title>Edit Profile</title>
</head>
<body style="margin-top: 100px;">
	<!-- Navbar -->
	<nav class="navbar fixed-top ">
		<div class="container-fluid">
			<h3>
				<a class="navbar-brand" href="#">
					<span class="material-icons"> restaurant</span>RecipeExplorer
				</a>
			</h3>
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="true" aria-label="Toggle navigation">
	        	<span class="navbar-toggler-icon"></span>
	        </button>
		    <div class="collapse navbar-collapse" id="navbarNav">
		        <ul class="navbar-nav ms-auto">
		            <li class="nav-item">
		                <a class="nav-link" href="index.php">Home</a>
		            </li>
		            <li class="nav-item">
		            	<button class="btn nav-link" type="btn" onclick="window.location.href='<?php echo isset($_SESSION['username']) ? 'profile.php' : 'login.php'; ?>'">My Profile</button>
		            </li>
		            <li class="nav-item">
		                <a class="nav-link" href="login.php">Login/Register</a>   

		            </li>
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
							<label><strong>Update Your Profile</strong></label>
						</div>
						<div class="mb-3">
							<label for ="username">Username</label>
							<input type="text" name="username" class="form-control" id="username" placeholder="Enter new username" value="<?php echo $user['username']; ?>">
						</div>
						<div class="mb-3">
							<label for="email">Email</label>
							<input type="email" name="email" class="form-control" id="email" placeholder="Enter new email" value="<?php echo $user['email']; ?>">
						</div>
						<div class="mb-3">
							<label for="pass">Password</label>
							<input type="password" name="pass" class="form-control" id="pass" placeholder="Enter new password" value="<?php echo $user['password']; ?>">
						</div>
						<div class="mb-3">
							<label for="desc">Description</label>
							<textarea  name="desc" class="form-control" rows="4" id="desc" placeholder="Enter new description"><?php echo htmlspecialchars($user['description']); ?></textarea>
						</div>
						<div class="d-grid gap-2 ">
							<!-- Read the user id from the POST request to be seen inside the if condition that checks if the form is submitted-->
							<input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
					  	  	<button class="btn submit" type="button submit" name="submit">Submit</button>
					  	</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	
</body>
</html>