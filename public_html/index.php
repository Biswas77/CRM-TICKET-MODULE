<?php
require_once __DIR__ . '/src/db.php';
require_once __DIR__ . '/src/helpers/functions.php';

requireLogin();
$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="assets/styles.css">

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f7fb;
        }

        .topbar {
            width: 100%;
            background: #0d6efd;
            padding: 12px 25px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .topbar h2 {
            margin: 0;
            font-size: 22px;
        }

        .logout-btn {
            background: #dc3545;
            padding: 8px 14px;
            border-radius: 8px;
            color: white;
            text-decoration: none;
            font-weight: bold;
        }

        .logout-btn:hover {
            background: #b02a37;
        }

        .dashboard-container {
            max-width: 1100px;
            margin: 30px auto;
            padding: 10px;
        }

        .dashboard-header {
            background: #0d6efd;
            padding: 20px;
            border-radius: 12px;
            color: white;
            text-align: center;
            margin-bottom: 25px;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 20px;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            transition: 0.25s;
        }

        .card:hover {
            transform: translateY(-6px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.12);
        }

        .card h3 {
            margin-bottom: 10px;
            color: #0d6efd;
        }

        .card p {
            margin: 0 0 15px;
            color: #555;
        }

        .btn {
            display: inline-block;
            background: #0d6efd;
            color: white;
            padding: 10px 16px;
            border-radius: 10px;
            text-decoration: none;
            transition: 0.2s;
        }

        .btn:hover {
            background: #084ec2;
        }
    </style>
</head>
<body>

<div class="topbar">
    <h2>CRM Ticket System</h2>
    <a class="logout-btn" href="logout.php">Logout</a>
</div>

<div class="dashboard-container">

    <div class="dashboard-header">
        <h1>Welcome, <?= htmlspecialchars($user['name']) ?> 👋</h1>
        <p>Your Smart Ticket Management Dashboard</p>
    </div>

    <div class="cards">

        <div class="card">
            <h3>Create Ticket</h3>
            <p>Raise a new ticket and describe the issue clearly for faster resolution.</p>
            <a class="btn" href="tickets_create.php">Create Now</a>
        </div>

        <div class="card">
            <h3>My Tickets</h3>
            <p>View, track, and edit your existing tickets anytime.</p>
            <a class="btn" href="tickets_view.php">View Tickets</a>
        </div>

        <?php if ($user['role'] === 'admin'): ?>
        <div class="card">
            <h3>Manage All Tickets</h3>
            <p>View, assign, edit, or delete any ticket across the system.</p>
            <a class="btn" href="admin_tickets.php">Go to Admin</a>
        </div>

        <div class="card">
            <h3>Manage Users</h3>
            <p>Add new users, update roles, or remove inactive accounts.</p>
            <a class="btn" href="admin_users.php">Manage Users</a>
        </div>
        <?php endif; ?>

    </div>

</div>

</body>
</html>
