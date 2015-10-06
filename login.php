<?php include("./layouts/header.php"); ?>

<title>eShop | Log in</title>
<div class="container-body">
    <!-- <div style="width: 100%;height:auto; margin:0em auto;"> -->
    </div>
    <br><br><br>
    <!-- <div class="container-container"> -->
        <div class="login-div" style="margin: 0em auto">
          <form action="sessions.php" method="post">
            <input type="text" name="email" placeholder="Enter your email" class="login-input" style="float:left;">
            <br><br><br>
            <input type="password" name="password" placeholder="Enter your password" class="login-input" style="float:left;">
            <br><br><br>
            <button type="submit" name="login" value="Login" class="login-button">Log in</button>
            <br><br>
            <p id="login-p">New User? <a href="register.php" style="color: rgba(255, 30, 30, 0.65);">Register</a></p>
          </form>
        </div>
    <!-- </div> -->
</div>
