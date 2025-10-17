<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Peer-to-Peer Learning Platform</title>
<style>
  body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: #f0f4f8;
    margin: 0;
    padding: 0;
  }
  header {
    background-color: #007BFF;
    padding: 15px 30px;
    color: white;
    text-align: center;
  }
  nav {
    background: white;
    padding: 15px 30px;
    box-shadow: 0 2px 4px rgb(0 0 0 / 0.1);
    text-align: center;
  }
  nav a {
    margin: 0 15px;
    text-decoration: none;
    color: #007BFF;
    font-weight: 600;
    font-size: 16px;
    transition: color 0.3s ease;
  }
  nav a:hover {
    color: #0056b3;
  }
  main {
    max-width: 900px;
    margin: 40px auto;
    padding: 20px;
    background: white;
    box-shadow: 0 4px 8px rgb(0 0 0 / 0.1);
    border-radius: 8px;
  }
  h1 {
    margin-bottom: 20px;
    font-weight: 700;
    color: #333;
  }
  p {
    font-size: 18px;
    color: #555;
  }
  .welcome {
    margin-top: 20px;
    font-style: italic;
    color: #007BFF;
  }
</style>
</head>
<body>

<header>
  <h1>Welcome to Peer-to-Peer Learning Platform</h1>
</header>

<nav>
  <?php if (isset($_SESSION['user_id'])): ?>
    <a href="dashboard.php">Dashboard</a>
    <a href="friends.php">Friends</a>
    <a href="chat.php">Chat</a>
    <a href="schedule.php">Schedule</a>
    <a href="logout.php">Logout</a>
  <?php else: ?>
    <a href="login.php">Login</a>
    <a href="register.php">Register</a>
  <?php endif; ?>
</nav>

<main>
  <h1>Learn Together, Grow Together</h1>
  <p>Our platform allows students and tutors to connect, share study materials, chat, schedule sessions, and much more.</p>

  <?php if (isset($_SESSION['username'])): ?>
    <p class="welcome">Hello, <?= htmlspecialchars($_SESSION['username']) ?>! Glad to have you here.</p>
  <?php else: ?>
    <p>Please login or register to get started.</p>
  <?php endif; ?>
</main>

</body>
</html>
