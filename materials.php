<?php
session_start();

// Example materials array (you can replace with DB query results later)
$materials = [
    [
        'id' => 1,
        'title' => 'Introduction to Algebra',
        'description' => 'Basic concepts of algebra with examples.',
        'filename' => 'algebra.pdf',
        'downloads' => 15,
        'pinned' => false,
    ],
    [
        'id' => 2,
        'title' => 'Physics Notes - Mechanics',
        'description' => 'Comprehensive notes on Mechanics.',
        'filename' => 'mechanics.pdf',
        'downloads' => 28,
        'pinned' => true,
    ],
    [
        'id' => 3,
        'title' => 'Chemistry Formula Sheet',
        'description' => 'Quick reference to key chemistry formulas.',
        'filename' => 'chemistry_formulas.pdf',
        'downloads' => 10,
        'pinned' => false,
    ],
];

// Handle pin/unpin action (simulate)
if (isset($_GET['pin']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $action = $_GET['pin'] === '1' ? 'Pinned' : 'Unpinned';
    // Here you would update the DB to set pinned status for the material $id

    // Just simulate message:
    $_SESSION['msg'] = "Material #$id $action successfully.";
    header('Location: material.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Study Materials - P2P Learning</title>
<style>
  body {
    font-family: Arial, sans-serif;
    background: #f8fafc;
    margin: 0;
    padding: 0;
  }
  header {
    background: #007BFF;
    padding: 15px 30px;
    color: white;
    text-align: center;
  }
  main {
    max-width: 900px;
    margin: 40px auto;
    background: white;
    padding: 20px;
    border-radius: 6px;
    box-shadow: 0 3px 8px rgba(0,0,0,0.1);
  }
  h1 {
    margin-bottom: 20px;
    color: #222;
  }
  .msg {
    padding: 10px;
    background: #d4edda;
    color: #155724;
    border-radius: 5px;
    margin-bottom: 15px;
    border: 1px solid #c3e6cb;
  }
  table {
    width: 100%;
    border-collapse: collapse;
  }
  table th, table td {
    padding: 12px 10px;
    border-bottom: 1px solid #ddd;
    text-align: left;
  }
  table th {
    background: #f1f5f9;
  }
  .btn {
    padding: 6px 12px;
    background: #007BFF;
    border: none;
    color: white;
    border-radius: 4px;
    cursor: pointer;
    text-decoration: none;
    font-size: 14px;
  }
  .btn:hover {
    background: #0056b3;
  }
  .pinned {
    color: #dc3545;
    font-weight: bold;
  }
</style>
</head>
<body>

<header>
  <h1>Study Materials</h1>
</header>

<main>

  <?php if (!empty($_SESSION['msg'])): ?>
    <div class="msg"><?= htmlspecialchars($_SESSION['msg']) ?></div>
    <?php unset($_SESSION['msg']); ?>
  <?php endif; ?>

  <table>
    <thead>
      <tr>
        <th>Title</th>
        <th>Description</th>
        <th>Downloads</th>
        <th>Pin</th>
        <th>Download</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($materials as $mat): ?>
        <tr>
          <td>
            <?= htmlspecialchars($mat['title']) ?>
            <?php if ($mat['pinned']): ?>
              <span class="pinned">📌</span>
            <?php endif; ?>
          </td>
          <td><?= htmlspecialchars($mat['description']) ?></td>
          <td><?= $mat['downloads'] ?></td>
          <td>
            <?php if ($mat['pinned']): ?>
              <a class="btn" href="material.php?pin=0&id=<?= $mat['id'] ?>">Unpin</a>
            <?php else: ?>
              <a class="btn" href="material.php?pin=1&id=<?= $mat['id'] ?>">Pin</a>
            <?php endif; ?>
          </td>
          <td>
            <a class="btn" href="uploads/<?= urlencode($mat['filename']) ?>" download>Download</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

</main>

</body>
</html>
