<?php
session_start();
include 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Get chat partner id from URL param (e.g. chat.php?user=2)
if (!isset($_GET['user'])) {
    echo "No chat user selected.";
    exit;
}

$chat_partner_id = (int)$_GET['user'];

// Fetch chat partner info
$stmt = $conn->prepare("SELECT id, username FROM users WHERE id = ?");
$stmt->bind_param("i", $chat_partner_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows == 0) {
    echo "User not found.";
    exit;
}
$partner = $result->fetch_assoc();

// Handle sending message
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['message'])) {
    $msg = trim($_POST['message']);
    $stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $user_id, $chat_partner_id, $msg);
    $stmt->execute();
    header("Location: chat.php?user=" . $chat_partner_id);
    exit;
}

// Fetch chat messages between logged user and partner (both directions)
$stmt = $conn->prepare("SELECT m.*, u.username AS sender_name FROM messages m JOIN users u ON m.sender_id = u.id
    WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?)
    ORDER BY m.created_at ASC");
$stmt->bind_param("iiii", $user_id, $chat_partner_id, $chat_partner_id, $user_id);
$stmt->execute();
$messages = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Chat with <?= htmlspecialchars($partner['username']) ?></title>
<style>
  body { font-family: Arial, sans-serif; margin: 20px; }
  .chat-box { border: 1px solid #ccc; padding: 10px; max-width: 600px; margin-bottom: 20px; height: 400px; overflow-y: scroll; }
  .message { margin: 5px 0; padding: 8px 12px; border-radius: 15px; max-width: 70%; }
  .sent { background: #d1ffd1; align-self: flex-end; margin-left: auto; }
  .received { background: #f1f1f1; }
  .chat-container { display: flex; flex-direction: column; }
  form { display: flex; max-width: 600px; }
  textarea { flex-grow: 1; padding: 8px; font-size: 14px; resize: none; }
  button { padding: 10px 15px; background: #28a745; border: none; color: white; cursor: pointer; }
</style>
</head>
<body>

<h2>Chat with <?= htmlspecialchars($partner['username']) ?></h2>

<div class="chat-box" id="chat-box">
  <?php while ($msg = $messages->fetch_assoc()): ?>
    <?php
      $class = ($msg['sender_id'] == $user_id) ? "message sent" : "message received";
    ?>
    <div class="<?= $class ?>">
      <strong><?= htmlspecialchars($msg['sender_name']) ?>:</strong><br>
      <?= nl2br(htmlspecialchars($msg['message'])) ?>
      <div style="font-size: 10px; color: #666; text-align: right;"><?= $msg['created_at'] ?></div>
    </div>
  <?php endwhile; ?>
</div>

<form method="POST" action="chat.php?user=<?= $chat_partner_id ?>">
  <textarea name="message" rows="2" placeholder="Type your message here..." required></textarea>
  <button type="submit">Send</button>
</form>

<script>
// Auto scroll to bottom of chat box
var chatBox = document.getElementById('chat-box');
chatBox.scrollTop = chatBox.scrollHeight;
</script>

</body>
</html>
