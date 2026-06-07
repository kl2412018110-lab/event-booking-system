<?php
session_start();
include '../config/db.php';
if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit;
}

// Search functionality
$search = "";
if(isset($_GET['search'])){
    $search = $_GET['search'];
    $stmt = $conn->prepare("SELECT * FROM events WHERE title LIKE ? ORDER BY event_date ASC");
    $like = "%$search%";
    $stmt->bind_param("s",$like);
} else {
    $stmt = $conn->prepare("SELECT * FROM events ORDER BY event_date ASC");
}

$stmt->execute();
$events = $stmt->get_result();
$admin = $_SESSION['admin'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Events</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
<style>
body{font-family:Poppins;background:#f4f6f9;margin:0;}
.navbar{display:flex;justify-content:space-between;padding:20px 50px;background:#0b3d91;color:white;}
.navbar a{color:white;text-decoration:none;margin-left:20px;font-weight:500;}
.navbar a:hover{color:#ffd700;}
.container{width:90%;max-width:1000px;margin:50px auto;background:white;padding:30px;border-radius:12px;box-shadow:0 8px 30px rgba(0,0,0,0.12);}
h2{text-align:center;color:#0b3d91;margin-bottom:20px;}
input[type=text]{padding:10px;width:250px;border:1px solid #ccc;border-radius:8px;margin-right:10px;}
        button, a.button {padding: 8px 12px;border-radius: 6px;text-decoration: none;color: white;font-size: 14px;display: inline-block;margin-right: 0;}
button{background:#0b3d91;}
button:hover, a.button:hover{background:#082c6c;}
a.button{background:#0b3d91;margin-right:15px;}
a.delete{background:#d9534f;} 
a.delete:hover{background:#c9302c;}
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
<a href="add_event.php">Add Event</a>
<a href="dashboard.php">Dashboard</a>
<a href="../logout.php">Logout</a>
</div>
</div>

<div class="container">
<h2>Events</h2>

<!-- Search Form -->
<form method="GET" style="margin-bottom:20px;">
<input type="text" name="search" placeholder="Search by title..." value="<?php echo htmlspecialchars($search); ?>">
<button type="submit">Search</button>
<a href="manage.php" class="button" style="background:#777;">Reset</a>
</form>

<table>
<tr>
<th>Title</th>
<th>Date</th>
<th>Venue</th>
<th>Actions</th>
</tr>

<?php while($row = $events->fetch_assoc()){ ?>
<tr>
<td><?php echo $row['title']; ?></td>
<td><?php echo $row['event_date']; ?></td>
<td><?php echo $row['venue']; ?></td>
<td>
<a href="edit_event.php?event_id=<?php echo $row['event_id']; ?>" class="button">Edit</a>
<a href="delete_event.php?event_id=<?php echo $row['event_id']; ?>" class="button delete">Delete</a>
</td>
</tr>
<?php } ?>
</table>
</div>

</body>
</html>