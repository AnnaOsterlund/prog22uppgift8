<?php

include_once 'includes/header.php';

if(isset($_POST['register-submit'])){
  $feedbackMessage = $user->checkUserRegisterInput($_POST['uname'], $_POST['umail'], $_POST['upass'], $_POST['upassrepeat'],);

  if($errorState === 1) {
   $user->register($_POST['uname'], $_POST['umail'], $_POST['upass']);
  }
  else{
      foreach($feedback as $item){
      echo $item;
      }
  }
}
?>


<div class="container">
<h1>Register form</h1>
  <form method="post">
    <label for="uname">Username</label><br>
    <input type="text" name="uname" id="uname"><br>
    <label for="umail">Email</label><br>
    <input type="text" name="umail" id="umail"><br>
    <label for="upass">Password</label><br>
    <input type="password" name="upass" id="upass"><br>
    <label for="upassrepeat">Repeat password</label><br>
    <input type="password" name="upassrepeat" id="upassrepeat"><br>
    <input type="submit" name="register-submit" value="Register"><br>
  </form>
</div>	
<?php 
include_once 'includes/footer.php';
?>