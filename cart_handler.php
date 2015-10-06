<?php
if (!isset($_SESSION['id'])) {	// if not logged in , go register
	redirect_to("register.php");
}

$cart_id = get_user_cart_id($_SESSION['id']); // get the user cart
if(isset($_POST['update']))
{
	$invalid_items = array();
	$cart_items = $_POST;
	foreach ($cart_items as $key => $quantity) //key is the id of the cart_item
	{
		if($key === "update") break; // reached last index (avoid breaking the app :D)
		update_quantity($key,$quantity);
	}
	// clear the cart from invalid cart items with SORRY message (NOT IMPLEMENTED)
}
if(isset($_POST['checkout'])) // process the checkout request
{
	$total_price = calculate_total_price($cart_id);
	$user_credit = get_user_credit($_SESSION['id']);
	if($total_price > $user_credit){
		echo "Please Recharge , Credit not enough"; // error message (out of credit)
	}else
	{
		//get orders
		$results = get_all_orders($cart_id);
		//process each one
		//invalid item array
		$invalid_items = array();
		while($cart_item = mysqli_fetch_row($results))
		{
			$item_id = $cart_item[3];
			$item_quantity = (int)get_item($item_id)[3]; // quantity of item (IN STOCK)
			if($item_quantity == 0)
			{
				// put them in array
				$invalid_items [] = $cart_item[0]; // cart_item id
			}
			else
			{
				$new_quantity = $item_quantity - $cart_item[2]; // new in stock quantity
				reduce_quantity($item_id,$new_quantity);
			}
		}
		if(empty($invalid_items))
		{
			update_purchase_history($_SESSION['id'],$cart_id);
			$total_price = calculate_total_price($cart_id); // recalculate the total price
			$new_credit = $user_credit - $total_price;
			update_user_credit($_SESSION['id'],$new_credit);
			clear_cart($cart_id);
			echo "<body onload='myFunction()'>"; // redirect
			$_SESSION['message'] = "Thank you for your purchase";
			// CONGRATULATE THE USER WITH FLASH MESSAGE (NO IDEA HOW FOR NOW)
			//redirect_to("index.php");
			//update history (names of products , time of purchase , amount paid) as a batch
			}
		else
		{
		// clear the cart from the invalid cart_items
		clear_invalid_items($cart_id,$invalid_items);
		echo "<body onload='myFunction()'>"; // redirect
			foreach ($invalid_items as $invalid_item) {
				$item_name = get_item($invalid_item)[1];
				$_SESSION['message'] = "Sorry ${item_name} Just Got Out of Stock!!";
			}
		}
		mysqli_free_result($results); // free some memory
	}
}
if(isset($_POST['remove'])) // remove a cart_item
{
	remove_item_from_cart($_POST['id']);
}

if(isset($_POST['clear'])) // clear the cart
{
	clear_cart($cart_id);
}
?>

<script>
function myFunction() {
  window.location.replace("http://localhost/index.php");
}
</script>
