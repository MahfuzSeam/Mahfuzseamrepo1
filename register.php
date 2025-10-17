<?php
session_start();
include 'db.php'; // DB connection

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if ($username === '' || $password === '' || $confirm_password === '') {
        $error = "Please fill in all required fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) && $email !== '') {
        $error = "Invalid email format.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        // Check if username already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Username already taken.";
        } else {
            // Insert new user with hashed password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $email, $hashed_password);
            if ($stmt->execute()) {
                $success = "Registration successful! You can now <a href='login.php'>login</a>.";
            } else {
                $error = "Error during registration. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Register - P2P Learning</title>
<style>
  body {
    font-family: Arial, sans-serif;
    background: #f0f2f5;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
  }
  .register-box {
    background: white;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgb(0 0 0 / 0.15);
    width: 320px;
  }
  h2 {
    margin-top: 0;
    color: #2d89ef;
    text-align: center;
  }
  input[type="text"], input[type="email"], input[type="password"] {
    width: 100%;
    padding: 10px;
    margin: 12px 0;
    border: 1px solid #ddd;
    border-radius: 4px;
    box-sizing: border-box;
  }
  button {
    width: 100%;
    padding: 10px;
    background: #2d89ef;
    border: none;
    color: white;
    font-weight: bold;
    cursor: pointer;
    border-radius: 4px;
  }
  button:hover {
    background: #1b5fbd;
  }
  .error {
    color: red;
    margin: 10px 0;
    text-align: center;
  }
  .success {
    color: green;
    margin: 10px 0;
    text-align: center;
  }
  .login-link {
    margin-top: 15px;
    text-align: center;
  }
  .login-link a {
    color: #2d89ef;
    text-decoration: none;
  }
</style>
</head>
<body>

<div class="register-box">
  <h2>Register</h2>
  <?php if ($error): ?>
    <div class="error"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>
  <?php if ($success): ?>
    <div class="success"><?= $success ?></div>
  <?php else: ?>
    <form method="POST" action="">
      <input type="text" name="username" placeholder="Username" required autofocus />
      <input type="email" name="email" placeholder="Email (optional)" />
      <input type="password" name="password" placeholder="Password" required />
      <input type="password" name="confirm_password" placeholder="Confirm Password" required />
      <button type="submit">Register</button>
    </form>
  <?php endif; ?>
  <div class="login-link">
    Already have an account? <a href="login.php">Login here</a>
  </div>
</div>

</body>
</html>
