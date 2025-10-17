<?php
// Include DB connection
include 'db.php';

// For demo, simple user session simulation
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Fetch user info
$user_id = $_SESSION['user_id'];
$user_sql = "SELECT username, email FROM users WHERE id = $user_id";
$user_result = $conn->query($user_sql);
$user = $user_result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Dashboard - P2P Learning</title>
<link rel="stylesheet" href="style.css" />
<style>
  /* Minimal fresh dashboard style */
  body {
    font-family: Arial, sans-serif;
    background: #f0f2f5;
    margin: 0; padding: 0;
  }
  header {
    background: #2d89ef;
    color: white;
    padding: 20px;
    text-align: center;
  }
  nav {
    background: #fff;
    padding: 10px 20px;
    box-shadow: 0 2px 4px rgb(0 0 0 / 0.1);
    display: flex;
    gap: 15px;
    justify-content: center;
  }
  nav a {
    color: #2d89ef;
    text-decoration: none;
    font-weight: bold;
  }
  nav a:hover {
    text-decoration: underline;
  }
  main {
    padding: 20px;
    max-width: 960px;
    margin: auto;
  }
  .welcome {
    font-size: 1.3em;
    margin-bottom: 20px;
  }
  .cards {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
  }
  .card {
    background: white;
    padding: 15px 20px;
    border-radius: 8px;
    flex: 1 1 220px;
    box-shadow: 0 1px 3px rgb(0 0 0 / 0.1);
    cursor: pointer;
    transition: box-shadow 0.3s ease;
    text-align: center;
  }
  .card:hover {
    box-shadow: 0 4px 8px rgb(0 0 0 / 0.15);
  }
  .card h3 {
    margin-top: 0;
    color: #2d89ef;
  }
</style>
</head>
<body>

<header>
  <h1>P2P Learning Dashboard</h1>
</header>

<nav>
  <a href="dashboard.php">Home</a>
  <a href="friends.php">Friends</a>
  <a href="chat.php">Chat</a>
  <a href="schedule.php">Schedule</a>
  <a href="materials.php">Materials</a>
  <a href="notifications.php">Notifications</a>
  <a href="logout.php">Logout</a>
</nav>

<main>
  <p class="welcome">Welcome back, <strong><?= htmlspecialchars($user['username']) ?></strong>!</p>

  <div class="cards">
    <div class="card" onclick="location.href='friends.php'">
      <h3>Friends</h3>
      <p>Manage friend requests and connections</p>
    </div>
    <div class="card" onclick="location.href='chat.php'">
      <h3>Chat</h3>
      <p>Send messages & chat in real-time</p>
    </div>
    <div class="card" onclick="location.href='schedule.php'">
      <h3>Schedule</h3>
      <p>Plan your study sessions & meetings</p>
    </div>
    <div class="card" onclick="location.href='materials.php'">
      <h3>Materials</h3>
      <p>Upload and download study resources</p>
    </div>
    <div class="card" onclick="location.href='notifications.php'">
      <h3>Notifications</h3>
      <p>View alerts and updates</p>
    </div>
  </div>
</main>

</body>
</html>
