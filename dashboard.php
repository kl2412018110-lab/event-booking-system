<?php
session_start();
include '../config/db.php';
if(!isset($_SESSION['user'])){ header("Location: login.php"); exit; }
$user = $_SESSION['user'];

$user_id = $user['id'];

$result = $conn->query("
SELECT e.*, 
CASE 
    WHEN r.event_id IS NOT NULL THEN 1 
    ELSE 0 
END AS joined
FROM events e
LEFT JOIN registrations r 
ON e.event_id = r.event_id AND r.user_id = '$user_id'
ORDER BY e.event_date ASC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Student Dashboard</title>
<style>
body{font-family:Poppins;background:#f4f6f9;margin:0;}
.navbar{display:flex;justify-content:space-between;padding:20px 50px;background:#0b3d91;color:white;}
.navbar a{color:white;text-decoration:none;margin-left:20px;font-weight:500;}
.navbar a:hover{color:#ffd700;}
.container{width:90%;max-width:900px;margin:40px auto;}
h2{text-align:center;color:#0b3d91;margin-bottom:30px;}
.event-card{background:white;padding:20px;margin-bottom:20px;border-radius:12px;box-shadow:0 5px 20px rgba(0,0,0,0.1);}
.event-card h3{margin:0;color:#0b3d91;}
.event-card p{margin:5px 0;color:#555;}
.event-card button{padding:8px 15px;background:#0b3d91;color:white;border:none;border-radius:6px;cursor:pointer;margin-top:10px;}
.event-card button:hover{background:#082c6c;}
</style>
</head>
<body>

<div class="navbar">
<span>Welcome, <?php echo $user['name']; ?></span>
<div>
<a href="register_event.php">Register Event</a>
<a href="my_events.php">My Events</a>
<a href="../logout.php">Logout</a>
</div>
</div>

<div class="container">
<h2>Available Campus Events</h2>
<?php while($row = $result->fetch_assoc()){ ?>
<div class="event-card">
<h3><?php echo $row['title']; ?></h3>
<p><?php echo $row['description']; ?></p>
<p>Date: <?php echo $row['event_date']; ?> | Venue: <?php echo $row['venue']; ?></p>

<?php if($row['joined']){ ?>
    <button disabled style="background:#28a745; color:white; cursor:not-allowed;">
        ✔ Joined
    </button>
<?php } else { ?>
    <form method="POST" action="register_event.php">
        <input type="hidden" name="event_id" value="<?php echo $row['event_id']; ?>">
        <button name="register_event">Register</button>
    </form>
<?php } ?>
</div>
<?php } ?>
</div>

</body>
</html>