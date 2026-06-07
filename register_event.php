<?php
session_start();
include '../config/db.php';  // <- pastikan path betul
if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit;
}
$user = $_SESSION['user'];
$success = "";
$error = "";

// Fetch all events
$events = $conn->query("SELECT * FROM events ORDER BY event_date ASC");

if(isset($_POST['register_event'])){
    $event_id = $_POST['event_id'];

    // check duplicate registration
    $stmt = $conn->prepare("SELECT * FROM registrations WHERE user_id=? AND event_id=?");
    $stmt->bind_param("ii",$user['id'],$event_id);
    $stmt->execute();
    $res = $stmt->get_result();

    if($res->num_rows > 0){
        $error = "You have already registered for this event.";
    } else {
        $status = "pending";
        $stmt = $conn->prepare("INSERT INTO registrations(user_id,event_id,status) VALUES(?,?,?)");
        $stmt->bind_param("iis",$user['id'],$event_id,$status);
        $stmt->execute();
        $success = "Event registration successful! Pending admin approval.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Register Event</title>
<style>
body{font-family:Poppins;background:#f4f6f9;margin:0;}
.navbar{display:flex;justify-content:space-between;padding:20px 50px;background:#0b3d91;color:white;}
.navbar a{color:white;text-decoration:none;margin-left:20px;font-weight:500;}
.navbar a:hover{color:#ffd700;}
.container{width:500px;margin:50px auto;background:white;padding:30px;border-radius:12px;box-shadow:0 8px 30px rgba(0,0,0,0.12);}
h2{text-align:center;color:#0b3d91;margin-bottom:20px;}
select{width:100%;padding:10px;margin:10px 0;border:1px solid #ccc;border-radius:8px;}
button{width:100%;padding:12px;background:#0b3d91;color:white;border:none;border-radius:8px;cursor:pointer;margin-top:10px;font-size:16px;}
button:hover{background:#082c6c;}
.success{color:green;text-align:center;margin-bottom:10px;}
.error{color:red;text-align:center;margin-bottom:10px;}
</style>
</head>
<body>

<div class="navbar">
<span>Welcome, <?php echo $user['name']; ?></span>
<div>
<a href="my_events.php">My Events</a>
<a href="dashboard.php">Dashboard</a>
<a href="../logout.php">Logout</a>
</div>
</div>

<div class="container">
<h2>Register for Event</h2>
<?php if($success) echo "<p class='success'>$success</p>"; ?>
<?php if($error) echo "<p class='error'>$error</p>"; ?>
<form method="POST">
<select name="event_id" required>
<option value="">-- Select Event --</option>
<?php while($row=$events->fetch_assoc()){ ?>
<option value="<?php echo $row['event_id']; ?>"><?php echo $row['title']." | ".$row['event_date']; ?></option>
<?php } ?>
</select>
<button name="register_event">Register</button>
</form>
</div>

</body>
</html>