<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/src/db.php';
require_once __DIR__ . '/src/helpers/functions.php';

requireLogin();

$userId = currentUserId();

$stmt = $pdo->prepare("SELECT * FROM tickets WHERE author_id = ? ORDER BY id DESC");
$stmt->execute([$userId]);
$tickets = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Tickets - CRM</title>

    <style>
        body {
            margin: 0;
            padding: 0;
            background: #f3f4f6;
            font-family: 'Segoe UI', Poppins, Arial, sans-serif;
        }

        /* Navigation */
        .navbar {
            background: #4f46e5;
            padding: 16px 30px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .navbar a {
            color: white;
            text-decoration: none;
            margin-left: 22px;
            font-weight: 500;
            font-size: 15px;
        }

        .navbar a:hover {
            text-decoration: underline;
        }

        /* Main container */
        .container {
            width: 90%;
            max-width: 1000px;
            margin: 40px auto;
            background: #ffffff;
            padding: 35px;
            border-radius: 18px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.08);
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        h2 {
            margin-top: 0;
            font-size: 26px;
            color: #111827;
        }

        /* Success alert */
        .success-message {
            background: #d1fae5;
            color: #065f46;
            border-left: 6px solid #0f766e;
            padding: 12px 15px;
            margin-bottom: 18px;
            border-radius: 8px;
            font-size: 15px;
        }

        /* Modern table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 18px;
        }

        table th {
            background: #f3f4f6;
            padding: 14px;
            text-align: left;
            font-size: 14px;
            color: #374151;
            border-bottom: 1px solid #e5e7eb;
        }

        table td {
            padding: 14px;
            border-bottom: 1px solid #f0f0f0;
            font-size: 15px;
            color: #374151;
        }

        tr:hover {
            background: #f9fafb;
        }

        /* Status badges */
        .status {
            padding: 7px 14px;
            border-radius: 30px;
            font-size: 12px;
            font-weight: 600;
            color: white;
        }

        .pending { background: #6b7280; }
        .inprogress { background: #2563eb; }
        .completed { background: #16a34a; }
        .onhold { background: #f59e0b; color: black; }

        /* View button */
        .action-btn {
            background: #4f46e5;
            padding: 8px 15px;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            transition: 0.2s;
        }

        .action-btn:hover {
            background: #4338ca;
        }
    </style>
</head>

<body>

<div class="navbar">
    <div><b>CRM Ticket System</b></div>
    <div>
        <a href="index.php">Dashboard</a>
        <a href="tickets_create.php">Create Ticket</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="container">
    <h2>My Tickets</h2>

    <?php if (!empty($_GET['created'])): ?>
        <div class="success-message">✔ Ticket Created Successfully</div>
    <?php endif; ?>

    <table>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Status</th>
            <th>Created</th>
            <th>Action</th>
        </tr>

        <?php foreach ($tickets as $t): ?>
        <tr>
            <td><?= $t['id'] ?></td>
            <td><?= htmlspecialchars($t['title']) ?></td>

            <td>
                <span class="status <?= $t['status'] ?>">
                    <?= ucfirst($t['status']) ?>
                </span>
            </td>

            <td><?= $t['created_at'] ?></td>

            <td>
                <a class="action-btn" href="tickets_view.php?id=<?= $t['id'] ?>">View</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

</body>
</html>
