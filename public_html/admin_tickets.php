<?php
require_once __DIR__ . '/src/db.php';
require_once __DIR__ . '/src/helpers/functions.php';

requireLogin();

if ($_SESSION['user']['role'] !== 'admin') {
    die("Access Denied: Admins only.");
}

$stmt = $pdo->query("
    SELECT 
        t.*,
        u.name AS author_name,
        (SELECT name FROM users WHERE id = 
            (SELECT assignee_id FROM ticket_assignments 
             WHERE ticket_id = t.id LIMIT 1)
        ) AS assigned_to
    FROM tickets t
    LEFT JOIN users u ON t.author_id = u.id
    ORDER BY t.id DESC
");

$tickets = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Tickets - Admin</title>
    <link rel="stylesheet" href="assets/styles.css">

    <style>
        body {
            margin: 0;
            background: #f2f4f7;
            height: 100vh;
            overflow: hidden;
            font-family: Arial, sans-serif;
        }

        .page-container {
            margin-top: 80px;
            padding: 20px;
            height: calc(100vh - 80px);
            overflow-y: auto;
            overflow-x: hidden;
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

        .dashboard-btn:hover {
            background: #0b5ed7;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            table-layout: fixed;
            border-radius: 8px;
            overflow: hidden;
        }

        th, td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            text-align: left;
            word-wrap: break-word;
            white-space: normal;
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

        td:nth-child(2),
        td:nth-child(3),
        td:nth-child(4) {
            width: 20%;
        }
    </style>
</head>
<body>

<?php include __DIR__ . "/layout/header.php"; ?>

<div class="page-container">

    <a class="dashboard-btn" href="index.php">← Go to Dashboard</a>

    <h2>All Tickets</h2>

    <table>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Author</th>
            <th>Assigned To</th>
            <th>Status</th>
            <th>Created</th>
            <th>Action</th>
        </tr>

        <?php foreach ($tickets as $t): ?>
        <tr>
            <td><?= $t['id'] ?></td>
            <td><?= htmlspecialchars($t['title']) ?></td>
            <td><?= htmlspecialchars($t['author_name']) ?></td>
            <td><?= $t['assigned_to'] ? $t['assigned_to'] : "<b style='color:red;'>Unassigned</b>" ?></td>
            <td><?= $t['status'] ?></td>
            <td><?= $t['created_at'] ?></td>
            <td>
                <a href="tickets_edit.php?id=<?= $t['id'] ?>">Edit</a> |
                <a href="tickets_assign.php?id=<?= $t['id'] ?>">Assign</a> |
                <a href="tickets_delete.php?id=<?= $t['id'] ?>" onclick="return confirm('Delete this ticket?');" style="color:red;">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

</div>

</body>
</html>
