<?php
session_start();
include '../config/db.php';
if(!isset($_SESSION['admin'])){ header("Location: login.php"); exit; }

if(isset($_GET['event_id'])){
    $event_id = $_GET['event_id'];
    $stmt = $conn->prepare("DELETE FROM events WHERE event_id=?");
    $stmt->bind_param("i",$event_id);
    $stmt->execute();
}
header("Location: manage.php");
exit;
?>