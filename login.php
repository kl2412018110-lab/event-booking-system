<?php
session_start();
include '../config/db.php';
$error = "";


if(isset($_POST['login'])){
    $email = $_POST['email'];
    $password= $_POST['password'];


    $stmt = $conn->prepare("SELECT * FROM users WHERE email=? AND role='student'");
    $stmt->bind_param("s",$email);
    $stmt->execute();
    $res = $stmt->get_result();


    if($res->num_rows > 0){
        $row = $res->fetch_assoc();
        if(password_verify($password,$row['password'])){
            $_SESSION['user']=$row;
            header("Location: dashboard.php");
            exit;
        } else { $error="Incorrect password!"; }
    } else { $error="Email not registered!"; }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Student Login</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
<style>
body{font-family:Poppins;background:linear-gradient(to right,#eef2f7,#f8fbff);margin:0;}
.container{width:360px;margin:100px auto;background:white;padding:40px;border-radius:12px;box-shadow:0 8px 30px rgba(0,0,0,0.12);}
h2{text-align:center;color:#0b3d91;margin-bottom:25px;}
input{width:100%;padding:12px;margin:10px 0;border:1px solid #ccc;border-radius:8px;}
button{width:100%;padding:12px;background:#0b3d91;color:white;border:none;border-radius:8px;font-size:16px;cursor:pointer;}
button:hover{background:#082c6c;}
.link{text-align:center;margin-top:15px;}
.link a{color:#0b3d91;text-decoration:none;font-weight:500;}
.error{color:red;text-align:center;margin-bottom:10px;}
</style>
</head>
<body>



<div class="container">
<h2>Student Login</h2>
<?php if($error) echo "<p class='error'>$error</p>"; ?>
<form method="POST">
<input type="email" name="email" placeholder="Email" required>
<input type="password" name="password" placeholder="Password" required>
<button name="login">Login</button>
</form>
<div class="link">
<p>Don't have an account? <a href="register.php">Register Here</a></p>
</div>
<div class="link">
<p>Back to <a href="../index.php">Homepage</a></p>
</div>
</div>

</body>
</html>