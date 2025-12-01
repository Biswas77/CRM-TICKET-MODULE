<?php
require_once __DIR__ . '/src/db.php';
require_once __DIR__ . '/src/helpers/functions.php';

requireLogin();

if ($_SESSION['user']['role'] !== 'admin') {
    die("Access Denied: Admins only.");
}

$users = $pdo->query("SELECT * FROM users ORDER BY id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>All Users - Admin</title>
    <link rel="stylesheet" href="assets/styles.css">

    <style>
        body {
            margin: 0;
            background: #f2f4f7;
            font-family: Arial, sans-serif;
            height: 100vh;
            overflow: hidden;
        }

        .page-container {
            margin-top: 80px;
            padding: 20px;
            height: calc(100vh - 80px);
            overflow-y: auto;
            overflow-x: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            table-layout: fixed;
        }

        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            word-wrap: break-word;
            white-space: normal;
            text-align: left;
        }

        th {
            background: #0d6efd;
            color: white;
            font-weight: bold;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        tr:hover {
            background: #f8f9fa;
        }

       a {
            text-decoration: none;
            color: #0d6efd;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }
        
        .dashboard-btn:hover {
            background: #0b5ed7;
        }
        
        .dashboard-btn {
            display: inline-block;
            background: #0d6efd;
            color: white;
            padding: 10px 16px;
            margin-bottom: 15px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
        }


        td:nth-child(2),
        td:nth-child(3),
        td:nth-child(4) {
            width: 20%;
        }

        td:nth-child(1) {
            width: 5%;
        }

        td:nth-child(2),
        td:nth-child(3),
        td:nth-child(4) {
            width: 20%;
        }

        td:nth-child(5),
        td:nth-child(6) {
            width: 15%;
        }
    </style>
</head>

<body>

<?php include __DIR__ . "/layout/header.php"; ?>

<div class="page-container">
    <a class="dashboard-btn" href="index.php">← Go to Dashboard</a>
    <h2>All Users</h2>

    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Created</th>
            <th>Updated</th>
        </tr>

        <?php foreach ($users as $u): ?>
        <tr>
            <td><?= $u['id'] ?></td>
            <td><?= htmlspecialchars($u['name']) ?></td>
            <td><?= htmlspecialchars($u['email']) ?></td>
            <td style="text-transform: capitalize;"><?= $u['role'] ?></td>
            <td><?= $u['created_at'] ?></td>
            <td><?= $u['updated_at'] ?></td>
        </tr>
        <?php endforeach; ?>

    </table>
</div>

</body>
</html>
