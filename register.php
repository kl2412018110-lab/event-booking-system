<?php
session_start();
include '../config/db.php';

$error = "";
$success = "";

if(isset($_POST['register'])){
    $name = $_POST['name'];
    $student_id = $_POST['student_id'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check duplicate email
    $check = $conn->prepare("SELECT * FROM users WHERE email=?");
    $check->bind_param("s",$email);
    $check->execute();
    $res = $check->get_result();

    if($res->num_rows > 0){
        $error = "Email already registered";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (name,student_id,email,password,role) VALUES (?,?,?,?,?)");
        $role="student";
        $stmt->bind_param("sssss",$name,$student_id,$email,$password,$role);
        $stmt->execute();

        // Success → redirect to login
        header("Location: login.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Student Registration</title>
<style>
body {font-family:Poppins;background:#f4f6f9;margin:0;}
.container {width:350px;margin:80px auto;background:white;padding:30px;border-radius:12px;box-shadow:0 5px 20px rgba(0,0,0,0.1);} 
h2 {text-align:center;color:#0b3d91;}
input {width:100%;padding:10px;margin:10px 0;border:1px solid #ccc;border-radius:8px;}
button {width:100%;padding:10px;background:#0b3d91;color:white;border:none;border-radius:8px;cursor:pointer;}
button:hover {background:#082c6c;}
.link {text-align:center;margin-top:15px;}
.link a {color:#0b3d91;text-decoration:none;}
.error {color:red;text-align:center;}
.success {color:green;text-align:center;}
</style>
</head>
<body>

<div class="container">
<h2>Student Registration</h2>

<?php if($error) echo "<p class='error'>$error</p>"; ?>

<form method="POST">
<input type="text" name="name" placeholder="Full Name" required>
<input type="text" name="student_id" placeholder="Student ID" required>
<input type="email" name="email" placeholder="Email" required>
<input type="password" name="password" placeholder="Password" required>
<button name="register">Register</button>
</form>

<div class="link">
<p>Already have an account?</p>
<a href="login.php">Login Here</a>
</div>
</div>

</body>
</html>