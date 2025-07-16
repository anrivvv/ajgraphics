<?php 
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $username = $_POST['username'];
  $email = $_POST['email'];
  $phone_number = $_POST['phone_number'];  
  $password = $_POST['password'];

  
  
  try {
    // get the database 
    require_once "get_db.inc.php";
    
    // check for duplicates
    $sql = "SELECT * FROM users WHERE username = :username OR email = :email or phone_number = :phone_number";
    $stmt = $pdo -> prepare($sql);
    $stmt -> execute(['username' => $username , 'email' => $email, 'phone_number' => $phone_number]);
    
    if ($stmt -> rowCount() <= 0) {

    $query = "INSERT INTO users (username, email, phone_number, password) VALUES (?, ?, ?, ?)";
    $stmt = $pdo -> prepare($query); 
    $stmt -> execute([$username, $email, $phone_number, $password,]);
    
    $pdo = null;  
    $stmt = null; 
    
    header("Location: Login_SignUp.php");

    die();

    }
      
    else {
      $_SESSION['error'] = 'username, email or number already exists';
      header("Location: Login_SignUp.php");
      die();
    }

  }
   
  catch (PDOException $e) {
    die("Error: " . $e -> getMessage());
  }


}

else {
  header("Location: signup.php");
}