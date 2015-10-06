<?php require_once("./session.php") ?>
<?php require_once("./functions.php") ?>

<?php
  if(isset($_POST["login"])) {
    $email = mysqli_real_escape_string($db,$_POST["email"]);
    $password = mysqli_real_escape_string($db,$_POST["password"]);
    $results = find_user_by_email($email);
    $user = mysqli_fetch_row($results);
    if($user){
      $hashed_pass = $user[4]; // position of the column (5th)
      if(password_verify($password,$hashed_pass))
      {
        $_SESSION['id'] = $user[0];
        $_SESSION['first_name'] = $user[1];
        // redirect_to("index.php");
      }
      else echo  "<label id='error'> Bad email/password </label>";
    }else
    {
      echo "<label id='error'> Bad email/password </label>";
    }
    mysqli_free_result($results); // free memory used to store the results
  } elseif (isset($_POST["logout"])) {
    //  $_SESSION = array();
    session_destroy();
    //  redirect_to("index.php");
  }
?>

<script>
  function myFunction(){
    window.location.replace("http://localhost/index.php");
  }
</script>

<body onload="myFunction()">
</body>
