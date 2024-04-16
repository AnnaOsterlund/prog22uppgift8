<?php
class User {
    public $username;
    public $email;
    private $password;
    public $role;
    private $status;
    public $pdo;
    private $errorMessages = [];
    private $errorState = 0;


    function __construct ($pdo) {
        $this->role = 4;
        $this->username = "RandomGuest123";
        $this->pdo = $pdo;
    }

    private function clearInput($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspelialchars($data);
        return $data;
    }

    public function checkUserRegisterInput($uname, $umail, $upass, $upassrepeat) {
        $errorMessages = [];
        $errorState = 0;
        //START Kolla om användaresn inmatade username eller email finns i databasen
        $stmt_checkUsername = $this->pdo->prepare('SELECT * FROM table_user WHERE u_name = :uname OR u_email = :email');
        $stmt_checkUsername->bindParam(":uname", $uname, PDO::PARAM_STR);
        $stmt_checkUsername->bindParam(":email", $umail, PDO::PARAM_STR);
        $stmt_checkUsername->execute();

        //Kolla om queryn returnerar något resultat
        if($stmt_checkUsername->rowCount() > 0){
            array_push($errorMessages,"Username or email is already taken!");
        }

       //SLUT kolla om användarens inmatade username eller email finns i databasen

       //START Kolla om användarens inmatade lösenord stämmer överens
        if($upass !==$upassrepeat){
            array_push($errorMessages,"Passwords do not match! ");
            $errorState = 1;
        }

        else{
            if(strlen($upass) < 8){
                array_push($errorMessages,"Passwords is to short! ");
            $errorState = 1;
            }
        }
        //SLUT Kolla om användarens inmatade lösenord stämmer överens

        //START Kolla om användarens inmatade email är en "riktig" adress
        if (!filter_var($umail, FILTER_VALIDATE_EMAIL)) {
            array_push($errorMessages,"Email not in correct format! ");
            $errorState = 1;
        }

        if($errorState === 1) {
        return $errorMessages;
        }
        else {
                return 1;
            }
        }
    }

public function register($uname, $umail, $upass){
        $hashedPassword = password_hash($upass,PASSWORD_DEFAULT);
        $uname = $this->clearInput($uname);
     

        $stmt_registerUser = $this->pdo->prepare('INSERT INTO table_user (u_name, u_password, u_email, u_role_fk, u_status) VALUES (:uname, :pw, :email, 1, 1)'); 
        $stmt_registerUser->bindParam(":uname", $uname, PDO::PARAM_STR);
        $stmt_registerUser->bindParam(":pw", $hashedPassword, PDO::PARAM_STR);
        $stmt_registerUser->bindParam(":email", $umail, PDO::PARAM_STR);

        if($stmt_registerUser->execute()){
            header("Location: index.php?newuser=1");
        }
        else{
        array_push($this->errorMessages, "Your info was input correctly, but something went wrong when saving to database, please be in touch with support!");
        }

    }

 public function login($unamemail, $upass){
    $stmt_checkUsername = $this->pdo->prepare('SELECT * FROM table_user WHERE u_name = :uname OR u_email = :email');
    $stmt_checkUsername->bindParam(":uname", $unamemail, PDO::PARAM_STR);
    $stmt_checkUsername->bindParam(":email", $unamemail, PDO::PARAM_STR);
    $stmt_checkUsername->execute();
    //Check if statement returns a reslut
    if($stmt_checkUsername->rowCount() === 0){
        array_push($this->errorMessages,"Username or email does not exist!");
        $this->$errorState = 1;
    }
    //Save user ata to an array
    $userData = $stmt_checkUsername->fetch();

   if(password_verify($upass, $userData['u_password'])){
    $_SESSION['user_id'] = $userData['u_id'];
    $_SESSION['user_name'] = $userData['u_name'];
    $_SESSION['user_role'] = $userData['u_role_fk'];
    header("Location: home.php");
    }
    else{
        array_push($this->errorMessages,"Password is incorrect");
        return $this->errorMessages;
    }


 }

    public function checkLoginStatus(){
    if(isset($_SESSION['user_id'])){
        return true;
    }
    else{
      //  header("Location: index.php");
    }
 }

public function checkUserRole($val){

$stmt_checkUserRoleLevel = $this->pdo->prepare('SELECT * FROM tables_roles WHERE r_id = :id');

$stmt_checkUserRoleLevel->bindParam(":id", $_SESSION['user_role'], PDO::PARAM_STR);
$stmt_checkUserRoleLevel->execute();
$result = $stmt_checkUserRoleLevel->fetch();

if($result['r_level'] >= $val) {
    return "true";
}

else{
    return "false";
}
}


 public function logout(){
    session_unset();
    session_destroy();
    header("Location: index.php");

 }

}

?>