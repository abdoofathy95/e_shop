<?php require_once("./functions.php") ?>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<link href='https://fonts.googleapis.com/css?family=Lato:300,400,700' rel='stylesheet' type='text/css'>

<script type="text/javascript" src="javascripts/test.js"></script>
<link rel="stylesheet" href="stylesheets/index.css">
<link rel="stylesheet" href="stylesheets/mm.css">

<div class="header">
	<div style="float: left;width: 40%;height: 55%;position:relative;left:5em;border: 0px solid rgba(208, 208, 208, 0.67);position: relative;top:25%;">
		<span class="crew-logo" style="float:left;">eShop</span>
	</div>
	<div style="float:right;height: 100%;right: 15em;position:relative">
		<ul class="navbar-links">
			<li style="float: left">
				<a href="index.php" class="navbar-link"><p id="home">Home</p></a>
			</li>
			<?php if (isset($_SESSION['id'])) { ?>
				<li style="float: left">
					<a href="cart.php" class="navbar-link"><p id="home">Shopping Cart</p></a>
				</li>
				<li style="float: left">
					<div class="member-photo">
							<img src="<?php echo get_user_avatar($_SESSION['id']) ?>" style="width: 100%;height: 100%; -moz-border-radius: 100%;-khtml-border-radius: 100%;-webkit-border-radius: 100%;"/>
					</div>
					<p class="navbar-link" id="user">Me</p>
				</li>
			<?php } else { ?>
				<li style="float: left">
					<a href="login.php" class="navbar-link"><p id="home">Log in</p></a>
				</li>
			<?php }?>
		</ul>

		<!-- The "User" menu -->

		<div class="account-menu-div display-none">
			<table class="account-menu">
				<tr class="account-menu-item">
					<td><a href="edit_profile.php">Profile</a></td>
				</tr>

				<tr class="account-menu-item">
					<td><a href="history.php">Purchase History</a></td>
				</tr>

				<tr class="account-menu-item">
					<td>
						<form action="sessions.php" method="post">
							<input type="submit" name="logout" value="Log out" class="account-menu-button"/>
						</form>
						<!-- Log out -->
					</td>

				</tr>
			</table>
		</div>

	</div>
</div>
