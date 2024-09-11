<?php 

session_start();

// For reporting purposes
/*error_reporting(E_ALL);
ini_set('display_errors', 1);*/
include('db_config/db_connect.php');

$username=$email=$password=$cpassword=$description=' ';

// Ensure the session persists >> can only be accessible for logged in users 
if (!isset($_SESSION['username'])) {
    // Redirect to login page if session is lost
    header("Location: login.php");
    exit;
}	

# Get the id of the logged in user;
$user_name=$_SESSION['username'];
$sql = "SELECT id FROM Users WHERE username='$user_name'";
$ress= mysqli_query($conn, $sql);
$id_of_logged_user = mysqli_fetch_assoc($ress);
#print_r($id_of_logged_user);

# Save the id of the user in a global variable 
$_SESSION['logged_user_id'] = $id_of_logged_user['id'];
$usr_id = $_SESSION['logged_user_id'];
$user_id = mysqli_real_escape_string($conn, $usr_id);
$query = "SELECT * FROM Users WHERE id='$user_id'";
$res = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($res);
mysqli_free_result($res);


// When submit button is clicked
if (isset($_POST['submit'])) {
    // Get the user ID from the session
    $user_id = $_SESSION['logged_user_id'];

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

mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
	<?php include('templates/header.php'); ?>
	<title>Edit Profile</title>
</head>
<body class="d-flex flex-column min-vh-100" style="margin-top: 100px;">
	<!-- Navbar -->
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
							<input type="password" name="pass" class="form-control" id="pass" placeholder="Enter new password">
						</div>
						<div class="mb-3">
							<label for="desc">Description</label>
							<textarea  name="desc" class="form-control" rows="4" id="desc" placeholder="Enter new description"><?php echo htmlspecialchars($user['description']); ?></textarea>
						</div>
						<div class="d-grid gap-2 ">
					  	  	<button class="btn submit" type="button submit" name="submit">Submit</button>
					  	</div>
					</form>
				</div>
			</div>
		</div>
	</div>
    <?php include('templates/footer2.php'); ?>
</body>
</html>