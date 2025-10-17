<?php
session_start();

// Example notifications array (replace with DB data later)
$notifications = [
    ['id' => 1, 'message' => 'New material uploaded: Calculus Notes', 'read' => false],
    ['id' => 2, 'message' => 'Friend request from Alice', 'read' => true],
    ['id' => 3, 'message' => 'Your study session is scheduled for tomorrow', 'read' => false],
];

// Mark notifications as read (simulate)
if (isset($_GET['mark']) && $_GET['mark'] == 'all') {
    // Here you would update the DB to mark notifications as read for the user
    $_SESSION['msg'] = "All notifications marked as read.";
    header("Location: notifications.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Notifications - P2P Learning</title>
<style>
  body {
    font-family: Arial, sans-serif;
    background: #f2f6fa;
    margin: 0; padding: 0;
  }
  header {
    background: #17a2b8;
    color: white;
    padding: 15px 30px;
    text-align: center;
  }
  main {
    max-width: 800px;
    margin: 40px auto;
    background: white;
    border-radius: 6px;
    box-shadow: 0 3px 8px rgba(0,0,0,0.1);
    padding: 20px;
  }
  h1 {
    margin-bottom: 20px;
  }
  .msg {
    background: #d1ecf1;
    color: #0c5460;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #bee5eb;
    border-radius: 5px;
  }
  ul.notifications {
    list-style: none;
    padding: 0;
  }
  ul.notifications li {
    padding: 12px 15px;
    border-bottom: 1px solid #ddd;
    font-size: 15px;
  }
  ul.notifications li.unread {
    background: #e9f7fd;
    font-weight: bold;
  }
  .mark-read-btn {
    background: #007bff;
    color: white;
    padding: 8px 16px;
    border: none;
    border-radius: 4px;
    text-decoration: none;
    cursor: pointer;
    float: right;
    margin-bottom: 15px;
    font-size: 14px;
  }
  .mark-read-btn:hover {
    background: #0056b3;
  }
</style>
</head>
<body>

<header>
  <h1>Notifications</h1>
</header>

<main>

  <?php if (!empty($_SESSION['msg'])): ?>
    <div class="msg"><?= htmlspecialchars($_SESSION['msg']) ?></div>
    <?php unset($_SESSION['msg']); ?>
  <?php endif; ?>

  <a href="notifications.php?mark=all" class="mark-read-btn">Mark all as read</a>

  <?php if (count($notifications) > 0): ?>
    <ul class="notifications">
      <?php foreach ($notifications as $note): ?>
        <li class="<?= $note['read'] ? '' : 'unread' ?>">
          <?= htmlspecialchars($note['message']) ?>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php else: ?>
    <p>No notifications.</p>
  <?php endif; ?>

</main>

</body>
</html>
