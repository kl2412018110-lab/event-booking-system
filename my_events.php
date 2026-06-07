<?php
session_start();
include '../config/db.php';
if(!isset($_SESSION['user'])){ header("Location: login.php"); exit; }
$user=$_SESSION['user'];

// Fetch student's registered events
$stmt = $conn->prepare("
    SELECT r.status, e.title, e.event_date, e.venue 
    FROM registrations r 
    JOIN events e ON r.event_id = e.event_id 
    WHERE r.user_id=?
    ORDER BY e.event_date ASC
");
$stmt->bind_param("i",$user['id']);
$stmt->execute();
$results = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Registered Events</title>
<style>
body{font-family:Poppins;background:#f4f6f9;margin:0;}
.navbar{display:flex;justify-content:space-between;padding:20px 50px;background:#0b3d91;color:white;}
.navbar a{color:white;text-decoration:none;margin-left:20px;font-weight:500;}
.navbar a:hover{color:#ffd700;}
.container{width:90%;max-width:800px;margin:50px auto;background:white;padding:30px;border-radius:12px;box-shadow:0 8px 30px rgba(0,0,0,0.12);}
h2{text-align:center;color:#0b3d91;margin-bottom:20px;}
table{width:100%;border-collapse:collapse;}
th,td{padding:12px;text-align:left;border-bottom:1px solid #ddd;}
th{background:#0b3d91;color:white;}
tr:hover{background:#f1f1f1;}
.status-pending{color:orange;font-weight:bold;}
.status-approved{color:green;font-weight:bold;}
</style>
</head>
<body>

<div class="navbar">
<span>Welcome, <?php echo $user['name']; ?></span>
<div>
<a href="register_event.php">Register Event</a>
<a href="dashboard.php">Dashboard</a>
<a href="../logout.php">Logout</a>
</div>
</div>

<div class="container">
<h2>My Registered Events</h2>
<table>
<tr>
<th>Event</th>
<th>Status</th>
</tr>
<?php while($row=$results->fetch_assoc()){ ?>
<tr>
<td><?php echo $row['title']; ?></td>
<td class="status-<?php echo $row['status']; ?>"><?php echo ucfirst($row['status']); ?></td>
</tr>
<?php } ?>
</table>
</div>

</body>
</html>