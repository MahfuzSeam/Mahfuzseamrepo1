<?php
// Sample data to search from (simulate users, materials, topics)
$items = [
    ['type' => 'user', 'name' => 'Alice Johnson'],
    ['type' => 'user', 'name' => 'Bob Smith'],
    ['type' => 'material', 'name' => 'Algebra Basics PDF'],
    ['type' => 'material', 'name' => 'Calculus Video Lecture'],
    ['type' => 'topic', 'name' => 'Linear Equations'],
    ['type' => 'topic', 'name' => 'Probability Theory'],
];

$searchTerm = '';
$results = [];

if (isset($_GET['q'])) {
    $searchTerm = trim($_GET['q']);
    if ($searchTerm !== '') {
        // Case-insensitive search in 'name' field
        $results = array_filter($items, function($item) use ($searchTerm) {
            return stripos($item['name'], $searchTerm) !== false;
        });
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Search - P2P Learning</title>
<style>
  body { font-family: Arial, sans-serif; padding: 20px; background: #fafafa; }
  h1 { color: #222; }
  form {
    margin-bottom: 20px;
  }
  input[type="search"] {
    padding: 10px;
    width: 300px;
    font-size: 16px;
    border: 2px solid #007bff;
    border-radius: 5px;
  }
  button {
    padding: 10px 15px;
    font-size: 16px;
    background: #007bff;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
  }
  button:hover {
    opacity: 0.9;
  }
  .results {
    max-width: 600px;
    background: white;
    padding: 15px;
    border-radius: 8px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.1);
  }
  .result-item {
    border-bottom: 1px solid #eee;
    padding: 10px 0;
  }
  .result-item:last-child {
    border-bottom: none;
  }
  .type-label {
    font-weight: bold;
    color: #007bff;
    text-transform: uppercase;
    margin-right: 8px;
  }
</style>
</head>
<body>

<h1>Search Users, Materials, Topics</h1>

<form method="GET" action="search.php">
  <input 
    type="search" 
    name="q" 
    placeholder="Type to search..." 
    value="<?= htmlspecialchars($searchTerm) ?>" 
    required
  />
  <button type="submit">Search</button>
</form>

<div class="results">
  <?php if ($searchTerm === ''): ?>
    <p>Type something and click Search to find results.</p>
  <?php elseif (empty($results)): ?>
    <p>No results found for "<strong><?= htmlspecialchars($searchTerm) ?></strong>".</p>
  <?php else: ?>
    <p>Found <?= count($results) ?> result(s) for "<strong><?= htmlspecialchars($searchTerm) ?></strong>":</p>
    <?php foreach ($results as $item): ?>
      <div class="result-item">
        <span class="type-label"><?= strtoupper($item['type']) ?></span>
        <?= htmlspecialchars($item['name']) ?>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>

</body>
</html>
