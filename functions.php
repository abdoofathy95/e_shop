<?php
require_once("db_connection.php");
require_once ("ResizeImage.php");

function redirect_to($page)
{
	header("Location: http://localhost/".$page);
}

function has_presence($field)
{
	return isset($field) && $field !== "";
}

function validate_fields($fields)
{
	global $errors;
	if(!has_presence($fields["email"])) $errors[] = "Email can't be blank";
	if(!has_presence($fields["password"]) &&
	!isset($_SESSION['id'])) $errors[] = "Password can't be blank";
	if(has_presence($fields["password"]) &&
	$fields["confirm_password"] !== $fields["password"])
	{
		$errors[] = "Confirm Password Has To be Identical to Password";
	}
	if(has_presence($fields["email"]))
	{
		$current_user = "";
		$result = find_user_by_email($fields["email"]);
		$user = mysqli_fetch_row($result);
		if(isset($_SESSION['id']))
		$current_user = mysqli_fetch_row(find_user_by_id($_SESSION['id']));
		if($user && $user !== $current_user) // email is the 4th column in table user
		$errors[] = "Email Already Exists";
	}
	// user regex to check email validity
}

function find_user_by_id($id)
{
	global $db;
	$query = "SELECT * FROM user WHERE id ='{$id}'";
	$result = mysqli_query($db,$query);
	return $result;
}

function find_user_by_email($email)
{
	global $db;
	$query = "SELECT * FROM user WHERE email ='{$email}'";
	$result = mysqli_query($db,$query);
	return $result;
}

function resize_image($path,$save_path,$width,$height)
{
	$image = new ResizeImage($path);
	$image->resizeTo($width, $height, 'exact');
	$image->saveImage($save_path);
}

function validate_uploaded_image($file)
{
	global $errors;
	$allowed_extensions = ['.jpg','.jpeg','.png','.gif'];
	$file_extension = strrchr($file["name"],".");
	if(!in_array($file_extension, $allowed_extensions))
	{
		$errors[]="Please Upload An Image";
	}
}

function get_user_avatar($id)
{
	$result = find_user_by_id($id);
	$user = mysqli_fetch_row($result);
	return $user[6];
}

function get_user_cart_id($id)
{
	$result = find_user_by_id($id);
	$user = mysqli_fetch_row($result);
	return $user[7];
}

function is_item_added($user_id,$item_id)
{
	global $db;
	$cart_id = get_user_cart_id($user_id);
	$query = "SELECT * FROM cart_item";
	$query .= " WHERE cart_id = {$cart_id}";
	$query .= " AND item_id = {$item_id}";
	$results = mysqli_query($db,$query);
	$result = mysqli_fetch_row($results);
	if(empty($result)){
		return false;
	}
	mysqli_free_result($results);
	return true;
}

function add_item_to_cart($cart_id,$order_quantity,$item_id)
{
	global $db;
	$query = "INSERT INTO cart_item(";
	$query.= "cart_id,order_quantity,item_id";
	$query.= ") VALUES (";
	$query.= "{$cart_id},{$order_quantity},{$item_id}";
	$query.= ")";
	mysqli_query($db,$query);
}

function remove_item_from_cart($cart_item_id)
{
	global $db;
	$query = "DELETE FROM cart_item";
	$query.= " WHERE id = {$cart_item_id}";
	mysqli_query($db,$query);
}

function clear_cart($cart_id)
{
	global $db;
	$query = "DELETE FROM cart_item";
	$query .= " WHERE cart_id = {$cart_id}";
	mysqli_query($db,$query);
}

function get_item($item_id)
{
	global $db;
	$query = "SELECT * FROM item";
	$query .= " WHERE id={$item_id}";
	$results = mysqli_query($db,$query);
	$item = mysqli_fetch_row($results);
	mysqli_free_result($results);
	return $item;
}

function calculate_item_price($item_id,$order_quantity)
{
	global $db;
	$query = "SELECT * FROM item";
	$query .= " WHERE id = {$item_id}";
	$results = mysqli_query($db,$query);
	$item = mysqli_fetch_row($results);
	return $item[2]*$order_quantity; // price
}

function calculate_total_price($cart_id)
{
	global $db;
	$query = "SELECT * FROM cart_item";
	$query .= " WHERE cart_id = {$cart_id}";
	$results = mysqli_query($db,$query);
	$total_price = 0.0;
	while($cart_item = mysqli_fetch_row($results))
	{
		$total_price += calculate_item_price($cart_item[3],$cart_item[2]); // 3 is the item_id and 2 is the order quantity
	}
	return $total_price;
}

function update_quantity($cart_item_id,$value)
{
	global $db;
	$cart_item_id = (int)$cart_item_id; // casting to int
	$value = (int) $value;
	$query = "UPDATE cart_item SET ";
	$query.= "order_quantity = {$value}";
	$query.= " WHERE id = {$cart_item_id}";
	mysqli_query($db,$query);
}

function get_all_orders($cart_id)
{
	global $db;
	$query = "SELECT * FROM cart_item";
	$query.= " WHERE cart_id = {$cart_id}";
	$result = mysqli_query($db,$query);
	return $result;
}

function reduce_quantity($item_id,$quantity) // reduce quantity of ordered stuff
{
	global $db;
	$query = "UPDATE item SET ";
	$query.= "quantity = {$quantity}";
	$query.= " WHERE id = {$item_id}";
	mysqli_query($db,$query);
}

function update_user_credit($user_id,$new_credit)
{
	global $db;
	$query = "UPDATE user SET ";
	$query.= "credit = {$new_credit}";
	$query.= " WHERE id = {$user_id}";
	mysqli_query($db,$query);
}

function get_user_credit($user_id)
{
	$result = find_user_by_id($user_id);
	$user = mysqli_fetch_row($result);
	return $user[5];
}
// update purchase histroy for all
function update_purchase_history($user_id,$cart_id)
{
	$cart_items = get_all_orders($cart_id);
	$time = strftime("%H:%M:%S %d/%m/%Y" ,time());
	while($cart_item = mysqli_fetch_row($cart_items))
	{
		$item= get_item($cart_item[3]);
		$item_name = $item[1];
		$item_price = (float) $item[2];
		$item_quantity = (int) $cart_item[2];
		update_purchase_history_item($user_id,$item_name,$item_price,$item_quantity,$time);
	}
}

// update purchase histroy for only one item
function update_purchase_history_item($user_id,$product_name,$product_price,$product_quantity,$time)
{
	global $db;
	$query = "INSERT INTO purchase_history(";
	$query.= "user_id,product_name,product_price,order_quantity,purchase_time";
	$query.= ") VALUES (";
	$query.= "{$user_id},'{$product_name}',{$product_price},{$product_quantity},'{$time}'";
	$query.= ")";
	mysqli_query($db,$query);
}

function show_history($user_id)
{
	global $db;
	$query = "SELECT * FROM purchase_history";
	$query.= " WHERE user_id={$user_id}";
	return mysqli_query($db,$query);
}

function clear_invalid_items($cart_id , $invalid_items)
{
	global $db;
	foreach ($invalid_items as $invalid_item) {
		$query = "DELETE FROM cart_item";
		$query.= " WHERE cart_id = {$cart_id}";
		$query.= " AND id = {$invalid_item}";
		mysqli_query($db,$query);
	}
}
?>
