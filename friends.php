<?php
session_start();
include 'db.php';

// Check user login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Handle sending friend request
if (isset($_POST['send_request']) && isset($_POST['friend_id'])) {
    $friend_id = (int)$_POST['friend_id'];

    // Prevent duplicate requests or already friends
    $check = $conn->prepare("SELECT * FROM friends WHERE (user_id = ? AND friend_id = ?) OR (user_id = ? AND friend_id = ?)");
    $check->bind_param("iiii", $user_id, $friend_id, $friend_id, $user_id);
    $check->execute();
    $res = $check->get_result();
    if ($res->num_rows == 0 && $friend_id != $user_id) {
        // Insert friend request with status = 0 (pending)
        $stmt = $conn->prepare("INSERT INTO friends (user_id, friend_id, status) VALUES (?, ?, 0)");
        $stmt->bind_param("ii", $user_id, $friend_id);
        $stmt->execute();
    }
}

// Get friends (status = 1 means accepted)
$stmt = $conn->prepare("SELECT u.id, u.username FROM users u
    JOIN friends f ON (u.id = f.friend_id OR u.id = f.user_id)
    WHERE (f.user_id = ? OR f.friend_id = ?) AND f.status = 1 AND u.id != ?");
$stmt->bind_param("iii", $user_id, $user_id, $user_id);
$stmt->execute();
$friends_result = $stmt->get_result();

// Get all users except logged-in user for sending requests
$all_users = $conn->prepare("SELECT id, username FROM users WHERE id != ?");
$all_users->bind_param("i", $user_id);
$all_users->execute();
$all_users_result = $all_users->get_result();

// Get pending requests sent by user
$pending_sent = $conn->prepare("SELECT friend_id FROM friends WHERE user_id = ? AND status = 0");
$pending_sent->bind_param("i", $user_id);
$pending_sent->execute();
$pending_sent_result = $pending_sent->get_result();
$pending_sent_ids = [];
while ($row = $pending_sent_result->fetch_assoc()) {
    $pending_sent_ids[] = $row['friend_id'];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Friends List</title>
<style>
  body { font-family: Arial, sans-serif; padding: 20px; }
  h2 { margin-bottom: 15px; }
  .friends, .all-users { margin-bottom: 30px; }
  .friend, .user { margin-bottom: 10px; }
  button { padding: 5px 10px; margin-left: 10px; cursor: pointer; }
  .pending { color: orange; font-style: italic; margin-left: 10px; }
</style>
</head>
<body>

<h2>Your Friends</h2>
<div class="friends">
  <?php if ($friends_result->num_rows > 0): ?>
    <ul>
    <?php while ($friend = $friends_result->fetch_assoc()): ?>
      <li class="friend"><?= htmlspecialchars($friend['username']) ?></li>
    <?php endwhile; ?>
    </ul>
  <?php else: ?>
    <p>You have no friends yet.</p>
  <?php endif; ?>
</div>

<h2>Send Friend Request</h2>
<div class="all-users">
  <form method="POST" action="friends.php">
    <select name="friend_id" required>
      <option value="">Select user</option>
      <?php while ($user = $all_users_result->fetch_assoc()): ?>
        <?php if (!in_array($user['id'], $pending_sent_ids)): ?>
          <option value="<?= $user['id'] ?>"><?= htmlspecialchars($user['username']) ?></option>
        <?php endif; ?>
      <?php endwhile; ?>
    </select>
    <button type="submit" name="send_request">Send Request</button>
  </form>
  <?php if (!empty($pending_sent_ids)): ?>
    <p>Pending requests sent to users with IDs: <?= implode(", ", $pending_sent_ids) ?></p>
  <?php endif; ?>
</div>

</body>
</html>
