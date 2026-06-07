<?php
session_start();
include '../config/db.php';
if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit;
}

// Approve action
if(isset($_GET['approve_id'])){
    $reg_id = $_GET['approve_id'];
    $stmt = $conn->prepare("UPDATE registrations SET status='approved' WHERE reg_id=?");
    $stmt->bind_param("i",$reg_id);
    $stmt->execute();
    header("Location: approve_registrations.php");
    exit;
}

// Get all pending registrations
$stmt = $conn->prepare("
    SELECT r.reg_id, u.name AS student_name, u.student_id, e.title AS event_title, r.status
    FROM registrations r
    JOIN users u ON r.user_id = u.id
    JOIN events e ON r.event_id = e.event_id
    WHERE r.status='pending'
    ORDER BY r.reg_id ASC
");
$stmt->execute();
$registrations = $stmt->get_result();
$admin = $_SESSION['admin'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Approve Registrations</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
<style>
body{font-family:Poppins;background:#f4f6f9;margin:0;}
.navbar{display:flex;justify-content:space-between;padding:20px 50px;background:#0b3d91;color:white;}
.navbar a{color:white;text-decoration:none;margin-left:20px;font-weight:500;}
.navbar a:hover{color:#ffd700;}
.container{width:90%;max-width:1000px;margin:50px auto;background:white;padding:30px;border-radius:12px;box-shadow:0 8px 30px rgba(0,0,0,0.12);}
h2{text-align:center;color:#0b3d91;margin-bottom:20px;}
a.button{padding:8px 15px;background:#28a745;color:white;border-radius:8px;text-decoration:none;margin-right:10px;}
a.button:hover{background:#218838;}
table{width:100%;border-collapse:collapse;margin-top:20px;}
th,td{padding:12px;text-align:left;border-bottom:1px solid #ddd;}
th{background:#0b3d91;color:white;}
tr:hover{background:#f1f1f1;}
</style>
</head>
<body>

<div class="navbar">
<span>Welcome, <?php echo $admin['name']; ?></span>
<div>
<a href="manage.php">Manage</a>
<a href="../logout.php">Logout</a>
</div>
</div>

<div class="container">
<h2>Pending Event Registrations</h2>

<table>
<tr>
<th>Student Name</th>
<th>Student ID</th>
<th>Event</th>
<th>Status</th>
<th>Action</th>
</tr>

<?php while($row = $registrations->fetch_assoc()){ ?>
<tr>
<td><?php echo $row['student_name']; ?></td>
<td><?php echo $row['student_id']; ?></td>
<td><?php echo $row['event_title']; ?></td>
<td><?php echo $row['status']; ?></td>
<td>
<a href="approve_registrations.php?approve_id=<?php echo $row['reg_id']; ?>" class="button">Approve</a>
</td>
</tr>
<?php } ?>
</table>
</div>

</body>
</html>