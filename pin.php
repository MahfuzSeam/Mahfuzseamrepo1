<?php
session_start();

// Simulated materials (replace with DB later)
$materials = [
    ['id' => 1, 'title' => 'Algebra Basics'],
    ['id' => 2, 'title' => 'Physics Formulas'],
    ['id' => 3, 'title' => 'World History Notes'],
];

// Initialize pinned materials in session if not set
if (!isset($_SESSION['pinned'])) {
    $_SESSION['pinned'] = [];
}

// Handle pin/unpin actions
if (isset($_GET['action'], $_GET['id'])) {
    $id = (int)$_GET['id'];
    if ($_GET['action'] == 'pin') {
        if (!in_array($id, $_SESSION['pinned'])) {
            $_SESSION['pinned'][] = $id;
        }
    } elseif ($_GET['action'] == 'unpin') {
        if (($key = array_search($id, $_SESSION['pinned'])) !== false) {
            unset($_SESSION['pinned'][$key]);
            // reindex array
            $_SESSION['pinned'] = array_values($_SESSION['pinned']);
        }
    }
    header('Location: pin.php');
    exit;
}

// Helper function to check if material is pinned
function isPinned($id) {
    return in_array($id, $_SESSION['pinned']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Pin Materials - P2P Learning</title>
<style>
  body { font-family: Arial, sans-serif; background: #f9f9f9; padding: 20px; }
  h1 { color: #333; }
  .material-list {
    max-width: 600px;
    margin: 0 auto;
    background: white;
    padding: 20px;
    border-radius: 6px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
  }
  .material-item {
    display: flex;
    justify-content: space-between;
    padding: 12px 0;
    border-bottom: 1px solid #eee;
  }
  .material-item:last-child {
    border-bottom: none;
  }
  .btn {
    padding: 6px 14px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
  }
  .pin-btn {
    background-color: #28a745;
    color: white;
  }
  .unpin-btn {
    background-color: #dc3545;
    color: white;
  }
  .btn:hover {
    opacity: 0.9;
  }
</style>
</head>
<body>

<h1>Pin / Unpin Materials</h1>

<div class="material-list">
  <?php foreach ($materials as $material): ?>
    <div class="material-item">
      <div><?= htmlspecialchars($material['title']) ?></div>
      <div>
        <?php if (isPinned($material['id'])): ?>
          <a href="pin.php?action=unpin&id=<?= $material['id'] ?>" class="btn unpin-btn">Unpin</a>
        <?php else: ?>
          <a href="pin.php?action=pin&id=<?= $material['id'] ?>" class="btn pin-btn">Pin</a>
        <?php endif; ?>
      </div>
    </div>
  <?php endforeach; ?>
</div>

</body>
</html>
