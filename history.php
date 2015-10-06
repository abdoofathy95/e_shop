<?php require_once("db_connection.php"); ?>
<?php require_once("session.php"); ?>
<?php require_once("functions.php"); ?>
<?php include("./layouts/header.php"); ?>
<?php
	if (!isset($_SESSION['id'])) {	// if logged in , can't register (add flash message)
		redirect_to("index.php");
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<!-- <link rel="stylesheet" href="stylesheets/index.css"> -->
		<title> eShop | Purchase History </title>
	</head>
	<body>
		<table id="history">
			<tr>
				<th id="login-p" style="font-weight: bold">Product Name</th>
				<th id="login-p" style="font-weight: bold">Product Price</th>
				<th id="login-p" style="font-weight: bold">Product Quantity</th>
				<th id="login-p" style="font-weight: bold">Purchase Time</th>
			</tr>
			<?php
				$results = show_history($_SESSION['id']);
				while($record = mysqli_fetch_row($results))
				{
					echo "<tr>";
					echo "<td id='purchase'>";
					echo "<p id=\"login-p\">".$record[2]."</p>";
					echo "</td>";
					echo "<td id='purchase'>";
					echo "<p id=\"login-p\">".$record[3]."</p>";
					echo "</td>";
					echo "<td id='purchase'>";
					echo "<p id=\"login-p\">".$record[4]."</p>";
					echo "</td>";
					echo "<td id='purchase'>";
					echo "<p id=\"login-p\">".$record[5]."</p>";
					echo "</td>";
					echo "</tr>";
				}
			?>
		</table>
		<?php include("./layouts/footer.php"); ?>
	</body>
</html>
