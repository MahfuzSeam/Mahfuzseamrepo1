<?php
session_start();

// Initialize schedule array in session if not set
if (!isset($_SESSION['schedule'])) {
    $_SESSION['schedule'] = [];
}

// Handle adding new schedule
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title'], $_POST['date'], $_POST['time'])) {
    $title = trim($_POST['title']);
    $date = $_POST['date'];
    $time = $_POST['time'];

    if ($title && $date && $time) {
        $_SESSION['schedule'][] = [
            'id' => uniqid(),
            'title' => htmlspecialchars($title),
            'date' => $date,
            'time' => $time,
        ];
    }
    header("Location: schedule.php");
    exit;
}

// Handle deleting a schedule entry
if (isset($_GET['delete'])) {
    $deleteId = $_GET['delete'];
    foreach ($_SESSION['schedule'] as $key => $entry) {
        if ($entry['id'] === $deleteId) {
            unset($_SESSION['schedule'][$key]);
            // reindex array
            $_SESSION['schedule'] = array_values($_SESSION['schedule']);
            break;
        }
    }
    header("Location: schedule.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Schedule - P2P Learning</title>
<style>
  body { font-family: Arial, sans-serif; background: #f5f5f5; padding: 20px; }
  h1 { color: #222; }
  form {
    margin-bottom: 20px;
    background: white;
    padding: 15px;
    border-radius: 8px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.1);
    max-width: 500px;
  }
  label {
    display: block;
    margin-bottom: 6px;
    font-weight: bold;
  }
  input[type="text"], input[type="date"], input[type="time"] {
    width: 100%;
    padding: 8px 10px;
    margin-bottom: 12px;
    border-radius: 4px;
    border: 1px solid #ccc;
  }
  button {
    background-color: #007bff;
    color: white;
    border: none;
    padding: 10px 18px;
    border-radius: 5px;
    cursor: pointer;
  }
  button:hover {
    opacity: 0.9;
  }
  .schedule-list {
    max-width: 600px;
    background: white;
    padding: 15px;
    border-radius: 8px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.1);
  }
  .schedule-item {
    display: flex;
    justify-content: space-between;
    border-bottom: 1px solid #eee;
    padding: 10px 0;
  }
  .schedule-item:last-child {
    border-bottom: none;
  }
  .delete-btn {
    background: #dc3545;
    color: white;
    border: none;
    padding: 5px 12px;
    border-radius: 4px;
    cursor: pointer;
  }
  .delete-btn:hover {
    opacity: 0.9;
  }
</style>
</head>
<body>

<h1>Schedule Your Study Sessions</h1>

<form method="POST" action="schedule.php">
  <label for="title">Session Title</label>
  <input type="text" id="title" name="title" required placeholder="E.g., Math Group Study" />

  <label for="date">Date</label>
  <input type="date" id="date" name="date" required />

  <label for="time">Time</label>
  <input type="time" id="time" name="time" required />

  <button type="submit">Add to Schedule</button>
</form>

<div class="schedule-list">
  <h2>Your Scheduled Sessions</h2>
  <?php if (!empty($_SESSION['schedule'])): ?>
    <?php foreach ($_SESSION['schedule'] as $session): ?>
      <div class="schedule-item">
        <div>
          <strong><?= $session['title'] ?></strong><br>
          <?= date('F j, Y', strtotime($session['date'])) ?> at <?= date('g:i A', strtotime($session['time'])) ?>
        </div>
        <div>
          <a href="schedule.php?delete=<?= $session['id'] ?>" onclick="return confirm('Delete this session?');">
            <button class="delete-btn">Delete</button>
          </a>
        </div>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <p>No sessions scheduled yet.</p>
  <?php endif; ?>
</div>

</body>
</html>
