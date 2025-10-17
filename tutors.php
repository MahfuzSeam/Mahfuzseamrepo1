<?php
// tutors.php
// This is a simple page to display verified tutors

// Simulate tutors data — in real app, you fetch from database
$tutors = [
    [
        'name' => 'Alice Johnson',
        'subject' => 'Mathematics',
        'email' => 'alice@example.com',
        'rating' => 4.8,
        'bio' => 'Experienced Math tutor with 5 years of teaching algebra and calculus.'
    ],
    [
        'name' => 'Bob Smith',
        'subject' => 'Physics',
        'email' => 'bob@example.com',
        'rating' => 4.5,
        'bio' => 'Passionate about helping students understand physics concepts clearly.'
    ],
    [
        'name' => 'Carol Lee',
        'subject' => 'English Literature',
        'email' => 'carol@example.com',
        'rating' => 4.9,
        'bio' => 'Specialized in literature and essay writing skills.'
    ]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Verified Tutors - P2P Learning Platform</title>
    <link rel="stylesheet" href="style.css" />
</head>
<body>
    <div class="container">
        <h1>Verified Tutors</h1>
        <?php if (count($tutors) === 0): ?>
            <p>No tutors available at the moment.</p>
        <?php else: ?>
            <div class="results">
                <?php foreach ($tutors as $tutor): ?>
                    <div class="result-item">
                        <h3><?= htmlspecialchars($tutor['name']) ?></h3>
                        <p><strong>Subject:</strong> <?= htmlspecialchars($tutor['subject']) ?></p>
                        <p><strong>Email:</strong> <a href="mailto:<?= htmlspecialchars($tutor['email']) ?>"><?= htmlspecialchars($tutor['email']) ?></a></p>
                        <p><strong>Rating:</strong> <?= number_format($tutor['rating'], 1) ?> / 5 ⭐</p>
                        <p><?= htmlspecialchars($tutor['bio']) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
