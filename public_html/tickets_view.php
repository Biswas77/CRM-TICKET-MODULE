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
            font-family: Arial, sans-serif;
            background: #eef2f5;
            margin: 0;
            padding: 0;
        }
        .navbar {
            background: #0d6efd;
            padding: 15px 25px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar a {
            color: white;
            text-decoration: none;
            margin-left: 18px;
            font-size: 15px;
        }
        .container {
            width: 85%;
            margin: 30px auto;
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.1);
        }
        h2 {
            margin-top: 0;
            font-size: 24px;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            border-radius: 10px;
            overflow: hidden;
        }
        table th {
            background: #f8f9fa;
            padding: 14px;
            text-align: left;
            font-weight: bold;
            font-size: 14px;
        }
        table td {
            padding: 14px;
            border-bottom: 1px solid #eee;
            font-size: 14px;
        }
        tr:hover {
            background: #f4f8ff;
        }
        .status {
            padding: 6px 12px;
            border-radius: 20px;
            color: white;
            font-size: 12px;
        }
        .pending { background: #6c757d; }
        .inprogress { background: #0d6efd; }
        .completed { background: #198754; }
        .onhold { background: #ffc107; color: black; }
        .success-message {
            background: #d1e7dd;
            border-left: 5px solid #0f5132;
            padding: 12px 15px;
            margin-bottom: 15px;
            border-radius: 6px;
            color: #0f5132;
            font-size: 14px;
        }
        .btn {
            padding: 7px 14px;
            text-decoration: none;
            border-radius: 6px;
            font-size: 13px;
            margin-right: 6px;
            color: white;
            display: inline-block;
        }
        .view-btn { background: #0d6efd; }
        .edit-btn { background: #198754; }
        .delete-btn { background: #dc3545; }
        .btn:hover {
            opacity: 0.85;
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
        <div class="success-message">Ticket Created Successfully</div>
    <?php endif; ?>

    <?php if (!empty($_GET['updated'])): ?>
        <div class="success-message">Ticket Updated Successfully</div>
    <?php endif; ?>

    <table>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Status</th>
            <th>Created At</th>
            <th>Updated At</th>
            <th>Action</th>
        </tr>

        <?php foreach ($tickets as $t): ?>
        <tr>
            <td><?= $t['id'] ?></td>
            <td><?= htmlspecialchars($t['title']) ?></td>
            <td><span class="status <?= $t['status'] ?>"><?= ucfirst($t['status']) ?></span></td>
            <td><?= $t['created_at'] ?></td>
            <td><?= $t['updated_at'] ?></td>
            <td>
                <a class="btn edit-btn" href="tickets_edit.php?id=<?= $t['id'] ?>">Edit</a>
                <a class="btn delete-btn" href="tickets_delete.php?id=<?= $t['id'] ?>"
                   onclick="return confirm('Delete this ticket?');">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>

    </table>

</div>

</body>
</html>
