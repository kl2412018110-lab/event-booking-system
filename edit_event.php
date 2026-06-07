<?php
session_start();
include '../config/db.php';
if(!isset($_SESSION['admin'])){ header("Location: login.php"); exit; }
$admin=$_SESSION['admin'];

$event_id = $_GET['event_id'];
$stmt = $conn->prepare("SELECT * FROM events WHERE event_id=?");
$stmt->bind_param("i",$event_id);
$stmt->execute();
$res = $stmt->get_result()->fetch_assoc();

if(isset($_POST['update'])){
    $title=$_POST['title'];
    $desc=$_POST['description'];
    $date=$_POST['event_date'];
    $venue=$_POST['venue'];
    $stmt = $conn->prepare("UPDATE events SET title=?, description=?, event_date=?, venue=? WHERE event_id=?");
    $stmt->bind_param("ssssi",$title,$desc,$date,$venue,$event_id);
    $stmt->execute();
    header("Location: manage.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Event</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
<style>
body{font-family:Poppins;background:#f4f6f9;margin:0;}
.container{width:500px;margin:60px auto;background:white;padding:30px;border-radius:12px;box-shadow:0 8px 30px rgba(0,0,0,0.12);}
h2{text-align:center;color:#0b3d91;margin-bottom:20px;}
input,textarea{width:100%;padding:10px;margin:5px 0;border:1px solid #ccc;border-radius:8px;}
button{padding:10px 15px;background:#0b3d91;color:white;border:none;border-radius:8px;cursor:pointer;margin-top:10px;}
button:hover{background:#082c6c;}
</style>
</head>
<body>

<div class="container">
<h2>Edit Event</h2>
<form method="POST">
<input type="text" name="title" placeholder="Event Title" value="<?php echo $res['title']; ?>" required>
<textarea name="description" placeholder="Description" rows="3" required><?php echo $res['description']; ?></textarea>
<input type="date" name="event_date" value="<?php echo $res['event_date']; ?>" required>
<input type="text" name="venue" placeholder="Venue" value="<?php echo $res['venue']; ?>" required>
<button name="update">Update Event</button>
</form>
</div>

</body>
</html>