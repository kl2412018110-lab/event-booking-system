<?php
session_start();
include '../config/db.php';
if(!isset($_SESSION['admin'])){ header("Location: login.php"); exit; }
$admin=$_SESSION['admin'];

// Handle add event
if(isset($_POST['add'])){
    $title=$_POST['title'];
    $desc=$_POST['description'];
    $date=$_POST['event_date'];
    $venue=$_POST['venue'];
    $stmt=$conn->prepare("INSERT INTO events(title,description,event_date,venue) VALUES(?,?,?,?)");
    $stmt->bind_param("ssss",$title,$desc,$date,$venue);
    $stmt->execute();
}

// Fetch all events
$result = $conn->query("SELECT * FROM events ORDER BY event_date ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Events</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
<style>
body{font-family:Poppins;background:#f4f6f9;margin:0;}
.navbar{display:flex;justify-content:space-between;padding:20px 50px;background:#0b3d91;color:white;}
.navbar a{color:white;text-decoration:none;margin-left:20px;font-weight:500;}
.navbar a:hover{color:#ffd700;}
.container{width:90%;max-width:1000px;margin:40px auto;}
h2{text-align:center;color:#0b3d91;margin-bottom:20px;}
form{background:white;padding:20px;border-radius:12px;box-shadow:0 5px 20px rgba(0,0,0,0.1);margin-bottom:30px;}
input,textarea{width:100%;padding:10px;margin:5px 0;border:1px solid #ccc;border-radius:8px;}
button{padding:10px 15px;background:#0b3d91;color:white;border:none;border-radius:8px;cursor:pointer;margin-top:10px;}
button:hover{background:#082c6c;}
table{width:100%;border-collapse:collapse;background:white;box-shadow:0 5px 20px rgba(0,0,0,0.1);border-radius:8px;overflow:hidden;}
th,td{padding:12px;text-align:left;border-bottom:1px solid #ddd;}
th{background:#0b3d91;color:white;}
tr:hover{background:#f1f1f1;}
.btn-edit{background:orange;color:white;padding:5px 10px;border-radius:6px;border:none;}
.btn-edit:hover{background:#cc8400;}
.btn-delete{background:red;color:white;padding:5px 10px;border-radius:6px;border:none;}
.btn-delete:hover{background:#b30000;}
</style>
</head>
<body>

<div class="navbar">
<span>Welcome, <?php echo $admin['name']; ?></span>
<div>
<a href="dashboard.php">Dashboard</a>
<a href="manage.php">Manage</a>
<a href="../logout.php">Logout</a>
</div>
</div>

<div class="container">
<h2>Add New Event</h2>
<form method="POST">
<input type="text" name="title" placeholder="Event Title" required>
<textarea name="description" placeholder="Description" rows="3" required></textarea>
<input type="date" name="event_date" required>
<input type="text" name="venue" placeholder="Venue" required>
<button name="add">Add Event</button>
</form>
