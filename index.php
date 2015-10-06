<?php require_once("db_connection.php"); ?>
<?php require_once("session.php"); ?>
<?php require_once("./functions.php") ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<!-- <link rel="stylesheet" href="stylesheets/index.css">
		<link rel="stylesheet" href="stylesheets/mm.css"> -->

		<title> eShop | Home </title>
	</head>
		<?php include("./layouts/header.php"); ?>
	<body>
		<div class="products">
			<?php
				if(isset($_SESSION['message'])) {
					echo "<div id='message'>".$_SESSION['message']."</div>";
					$_SESSION['message'] = null;
				}
			?>
			<?php
				$query = "SELECT * FROM item";
				$result = mysqli_query($db,$query);
				while ($item = mysqli_fetch_assoc($result))
				{
					echo "<div class='product'>";
					echo "<img src=products-image/default.jpg />";
					echo "<br/>";
					echo "<div class='product-name'>".ucfirst($item['name'])."</div>";
					echo "<div class='price'> $".$item['price']."</div>";
					echo "<br/>";
					if($item['quantity']==0){
						echo "<h5 id='error'>Out of Stock</h5>";
					}else
					{
			?>
			<?php
				if (isset($_SESSION['id'])) {
					$link = "cart.php?id=".$item['id'];
				} else {
					$link = "index.php";
				}
			?>
			 <a href="<?php echo $link?>" class="link"> Buy </a>
			<?php
					}
					echo "</div>";
					// add buy button and pass the id (that creates a cart_item and redirects to the cart page)
				}
			?>
		</div>
	</body>
<?php include("./layouts/footer.php"); ?>
</html>
