<?php
include_once 'includes/config.php';
include_once 'includes/functions.php';
include_once 'includes/header.php';

if(isset($_POST['submit']))
  $errormessage = $user->login($_POST['uname'], $_POST['upass']);


?>


<div class="container">
  <?php
    //OM du nyligen registrerat dig -> visa
    if(isset($_GET['newuser'])){
      echo "<div class='alert alert-success text-center mt-2' role='alert'>
         You have successfully registered. Please log in using the form below.
      </div>";
    }
    //Om deet finns ett errormessage -> visa
    if(isset($errorMessage)){
      echo "<div class='alert alert-danger text-center mt-2' role='alert'>";

      foreach($errorMessage as $item){
        echo $item;
      }
      echo "</div>";
    }

    
  ?>


<div class="container">
<h1>Login form</h1>
  <form action="" method="post">
    <label for="uname">Username or email</label><br>
    <input type="text" name="uname" id="uname"><br>
    <label for="upass">Password</label><br>
    <input type="password" name="upass" id="upass"><br>
    <input type="submit" name="submit" value="Next"><br>
  </form>
</div>	
<?php 
include_once 'includes/footer.php';
?>