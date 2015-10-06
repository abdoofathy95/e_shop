<?php require_once("functions.php") ?>
<?php require_once("db_connection.php") ?>
<?php require_once("session.php") ?>
<?php
	if (isset($_SESSION['id']))	// if logged in , can't register (add flash message)
	{
	 redirect_to("index.php");
	}
 ?>
<?php	if(isset($_POST["register"])) // checks whether register button was clicked
	{
		$errors = array(); //errors array
		validate_fields($_POST);
		if(!empty($_FILES["avatar"]["name"])){
			validate_uploaded_image($_FILES["avatar"]); // validate the extension
		}
		if(empty($errors)){
			// try to register
			$firstname = mysqli_real_escape_string($db,$_POST["firstname"]); // to avoid SQL INjection
			$lastname = mysqli_real_escape_string($db,$_POST["lastname"]);
			$password = password_hash($_POST["password"],PASSWORD_DEFAULT);
			$email = mysqli_real_escape_string($db,$_POST["email"]);
			if($_FILES["avatar"]["name"] === "") // check if the field is empty
			{
				$avatar = "uploaded-images/default.jpg"; // need default image at that dir
			}
			else
			{
				$avatar = "uploaded-images/" . $_FILES["avatar"]["name"];
				$avatar = $avatar.time(); // append time stamp (3a4an el filename maytkarar4)
				resize_image($_FILES["avatar"]["tmp_name"],$avatar,50,50); // resize and move the uploaded image
			}
			$cart_query = "INSERT INTO cart VALUES()";
			mysqli_query($db,$cart_query);
			$cart_id = mysqli_insert_id($db);
			$credit = 1000.0;
			$query = "INSERT INTO user (";
			$query .=	" first_name, last_name, email, password_hash, credit, avatar, cart_id";
			$query .= ") VALUES (";
			$query .= " '{$firstname}','{$lastname}','{$email}','{$password}',{$credit},'{$avatar}',{$cart_id}";
			$query .= ")";
			mysqli_query($db,$query);
			// log in and redirect
			$_SESSION['id'] = mysqli_insert_id($db); // get the id of the registered user
			$_SESSION['first_name'] = $firstname;
			redirect_to("index.php");
		}else{
			echo "<div>";
			echo "Please fix the follwing errors:";
			echo "<ul>";
			foreach ($errors as $error) {
				echo "<li>{$error}</li>";
			}
			echo "</ul>";
		}
	}

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<link rel="stylesheet" href="stylesheets/index.css">
		<title>	eShop | Register </title>
	</head>
	<?php include("./layouts/header.php"); ?>
	<body>
	<!-- <h1>Register</h1> -->
	<div class="login-div" style="margin: 0em auto">
		<form action="register.php" method="post" enctype="multipart/form-data">
			<table style="width: 100%">
				<tr>
				<td><br /><input type="text" name="firstname" value="" placeholder="Enter your first name" class="login-input"/> </td>
				<!-- <td> Last Name <br /><input type="text" name="lastname" value="" placeholder="example: abdoo" class="login-input"/> </td> -->
				</tr>
				<tr>
				<!-- <td> First Name <br /><input type="text" name="firstname" value="" placeholder="Enter your first name" class="login-input"/> </td> -->
				<td><br /><input type="text" name="lastname" value="" placeholder="Enter your last name" class="login-input"/> </td>
				</tr>
				<tr>
				<td><br><input type="text" name="email" value="" placeholder="Enter your email address" class="login-input"/> </td>
				<!-- <td> Avatar <br /> <input type="file" name="avatar" /> </td> -->
		 		</tr>
				<tr>
				<!-- <td><br><input type="text" name="email" value="" placeholder="example: abdoo@gmail.com" class="login-input"/> </td> -->
				<td> <br>Avatar <br /> <input type="file" name="avatar" /> </td>
		 		</tr>
		 		<tr>
				<td> <br><input type="password" id="password" name="password" value="" placeholder="Create a new password" class="login-input"/> </td>
				<!-- <td> <br /><input type="password" id="confirm_password" name="confirm_password" value=""class="login-input"/> </td> -->
				</tr>
				<tr>
				<td> <br /><input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm password" value=""class="login-input"/> </td>
				</tr>
				<tr>
				<!-- <td> *required fields </td> -->
				<td><br><input type="submit" name="register" value="Register" class="login-button" /> </td>
				</tr>
			</table>
		</form>
	</div>
	</body>

<?php include("./layouts/footer.php"); ?>
</html>
