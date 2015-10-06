<?php require_once("functions.php") ?>
<?php require_once("db_connection.php") ?>
<?php require_once("session.php") ?>
<?php
	if (!isset($_SESSION['id']))	// if logged out , can't edit (add flash message)
	{
	 redirect_to("index.php");
	} else { // fill the form
		$result = find_user_by_id($_SESSION['id']);
		$old_info = mysqli_fetch_row($result);
	}
 ?>
<?php	if(isset($_POST["edit"])) // checks whether register button was clicked
	{
		$errors = array(); //errors array
		validate_fields($_POST);
		if(!empty($_FILES["avatar"]["name"])){
			validate_uploaded_image($_FILES["avatar"]); // validate the extension
		}
		if(empty($errors)){
			// try to register
			$id = $_SESSION['id'];
			$firstname = mysqli_real_escape_string($db,$_POST["firstname"]); // to avoid SQL INjection
			$lastname = mysqli_real_escape_string($db,$_POST["lastname"]);
			$password = password_hash($_POST["password"],PASSWORD_DEFAULT);
			$email = mysqli_real_escape_string($db,$_POST["email"]);
			$credit = $_POST['credit'];
			if($_FILES["avatar"]["name"] !== "") // check if the field is empty
			{
				$old_image = get_user_avatar($_SESSION['id']);
				if($old_image !== "uploaded-images/default.jpg") unlink($old_image); // deletes the old image
				$avatar = "uploaded-images/" . $_FILES["avatar"]["name"];
				$avatar = $avatar.time(); // append time stamp (3a4an el filename maytkarar4)
				resize_image($_FILES["avatar"]["tmp_name"],$avatar,50,50); // resize and move the uploaded image
			}
			$query = "UPDATE user SET ";
			$query .=	"first_name = '{$firstname}', ";
			$query .= "last_name = '{$lastname}', ";
			$query .= "email = '{$email}', ";
			if($_POST["password"] !== "") $query .= "password_hash = '{$password}', ";
			if(!empty($_FILES["avatar"]["name"])) $query .= "avatar = '{$avatar}', ";
			$query .= "credit = '{$credit}' ";
			$query .= "WHERE id = {$id}";
			mysqli_query($db,$query);
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
		<title>	eShop | My Profile </title>
	</head>
	<?php include("./layouts/header.php"); ?>
	<body>
		<div class="login-div" style="margin: 0em auto">
			<form action="edit_profile.php" method="post" enctype="multipart/form-data">
				<table style="width: 100%">
					<tr>
						<div class="member-profile-photo">
								<img src="<?php echo get_user_avatar($_SESSION['id']) ?>" style="width: 100%;height: 100%; -moz-border-radius: 100%;-khtml-border-radius: 100%;-webkit-border-radius: 100%;"/>
						</div>
					</tr>
					<tr>
						<td><br /><input type="text" name="firstname" value="<?php echo $old_info[1] ?>" placeholder="Edit your first name" class="login-input" /> </td>
					</tr>
					<tr>
						<td><br /><input type="text" name="lastname" value="<?php echo $old_info[2] ?>" placeholder="Edit your last name" class="login-input"/> </td>
					</tr>
					<tr>
						<td><br /><input type="text" name="email" value="<?php echo $old_info[3] ?>" placeholder="Edit your email address" class="login-input"/> </td>
					</tr>
					<tr>
						<td><br> <p id="login-p">Avatar</p> <br /> <input type="file" name="avatar" /> </td>
					</tr>
					<tr>
						<td><br /><input type="password" id="password" name="password" value="" placeholder="Edit your password" class="login-input"/> </td>
					</tr>
					<tr>
						<td><br /><input type="password" id="confirm_password" name="confirm_password" value="" placeholder="Confirm password" class="login-input"/> </td>
					</tr>
					<tr>
						<td><br> <p id="login-p">Credit </p> <input typr="text" name="credit" value="<?php echo $old_info[5] ?>" class="login-input" /> </td>
					</tr>
				</table>
				<br><input type="submit" name="edit" value="Save" class="login-button" />
			</form>
		</div>
	</body>

<?php include("./layouts/footer.php"); ?>
</html>
