<?php
require_once __DIR__ . '/src/db.php';
require_once __DIR__ . '/src/helpers/functions.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($name === '' || $email === '' || $password === '') {
        $error = "All fields are required.";
    } else {
        $check = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $check->execute([$email]);

        if ($check->fetch()) {
            $error = "Email already registered.";
        } else {
            $hash = password_hash($password, PASSWORD_BCRYPT);

            $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$name, $email, $hash]);

            header("Location: auth_login.php?registered=1");
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register - CRM Ticket System</title>

    <style>
        body {
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #0d6efd, #6610f2);
            font-family: "Segoe UI", Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .card {
            width: 380px;
            background: rgba(255,255,255,0.9);
            backdrop-filter: blur(8px);
            padding: 35px;
            border-radius: 18px;
            box-shadow: 0 10px 28px rgba(0,0,0,0.15);
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from { transform: translateY(15px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #0d6efd;
            letter-spacing: 1px;
        }

        label {
            font-weight: bold;
            color: #333;
        }

        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 8px 0 18px 0;
            border-radius: 10px;
            border: 1px solid #ccd1d9;
            background: #f9fbff;
            transition: 0.2s;
        }

        input:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 5px rgba(13, 110, 253, 0.4);
            outline: none;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #0d6efd;
            border: none;
            border-radius: 10px;
            color: white;
            font-size: 17px;
            cursor: pointer;
            transition: 0.25s;
        }

        button:hover {
            background: #0b5ed7;
        }

        .error {
            text-align: center;
            color: #d62828;
            margin-bottom: 15px;
            font-weight: bold;
        }

        .footer {
            text-align: center;
            margin-top: 15px;
        }

        .footer a {
            color: #0d6efd;
            font-weight: bold;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

<div class="card">

    <h2>Create Account</h2>

    <?php if (!empty($error)): ?>
        <div class="error"><?= $error ?></div>
    <?php endif; ?>

    <form method="post">
        <label>Full Name</label>
        <input type="text" name="name" placeholder="Your name">

        <label>Email Address</label>
        <input type="email" name="email" placeholder="Email">

        <label>Password</label>
        <input type="password" name="password" placeholder="Create password">

        <button type="submit">Register</button>
    </form>

    <div class="footer">
        Already have an account? <a href="auth_login.php">Login</a>
    </div>

</div>

</body>
</html>
