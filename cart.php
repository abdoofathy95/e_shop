<?php require_once("db_connection.php"); ?>
<?php require_once("functions.php"); ?>
<?php require_once("session.php"); ?>
<?php include("./layouts/header.php"); ?>
<?php require_once("cart_handler.php"); ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title> eShop | Shopping Cart </title>
	</head>
	<body>
		<div class="login-div" style="margin: 0em auto; margin-top: 0em">
			<?php
				if(isset($_GET['id'])){	// check if add to cart button was clicked
					$order_quantity = 1;
					$item_id = $_GET['id'];
					if(!is_item_added($_SESSION['id'],$item_id)) // check if item was added before
					add_item_to_cart($cart_id,$order_quantity,$item_id);
					else
					echo "<p id=\"login-p\">Items</p><br/>";
				}
				$showQuery = "SELECT * FROM cart_item";
				$showQuery .= " WHERE cart_id = {$cart_id}";
				$results = mysqli_query($db,$showQuery);

				while($cart_item = mysqli_fetch_row($results)) // cart body
				{
					$item=get_item($cart_item[3]); // column of item_id is 4th
					echo "<p id=\"login-p\">".$item[1] . ", Price: $".$item[2]."</p><br>";
			?>
				<form id="update" method="post" action="cart.php">
				</form>
				<input form="update" type="number" min="1" max="<?php echo $item[3]?>" name="<?php echo $cart_item[0] ?>" value=<?php echo $cart_item[2] ?> />

				<form action="cart.php" method="POST">
					<input type="hidden" name="id" value="<?php echo $cart_item[0]?>" />
					<br><input name="remove" type="submit" value="Remove" class="login-button" style="width: 20%"/><hr style="margin-top: 0.5em">
				</form>
				<br/>
			<?php
				}
			?>
			<?php
				$total = calculate_total_price($cart_id); // total price
				if ($total > 0) {
					echo "<p id=\"login-p\" style=\"font-weight: bold\">Total: $".$total."</p>";
			?>
			<br>
			<input form="update" type="submit" name="update" value="Update Shopping Cart" class="login-button"/>
			<form action="cart.php" method="POST">
				<br><input type="submit" name="checkout" value="Checkout" class="login-button"><br>
			</form>
			<form action="cart.php" method="POST">
				<br><input type="submit" name="clear" value="Clear Shopping Cart" class="login-button"/>
			</form>
			<?php
				} else {
					echo "<p id=\"cart-h1\">Shopping Cart is Empty</p>";
				}
				mysqli_free_result($results);
			?>
			<br><a href="index.php"><p id="login-p"> Continue Shopping </p></a>
		</div>
		<?php include("./layouts/footer.php"); ?>
	</body>
</html>
