<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/src/db.php';
require_once __DIR__ . '/src/helpers/functions.php';

requireLogin();

$error = "";

$id = $_GET['id'] ?? 0;
$userId = currentUserId();

$userRole = $_SESSION['user']['role'] ?? 'user';

if ($userRole === 'admin') {
    $stmt = $pdo->prepare("SELECT * FROM tickets WHERE id = ?");
    $stmt->execute([$id]);
} else {
    $stmt = $pdo->prepare("SELECT * FROM tickets WHERE id = ? AND author_id = ?");
    $stmt->execute([$id, $userId]);
}

$ticket = $stmt->fetch();

if (!$ticket) {
    echo "Ticket not found or access denied.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $status = $_POST['status'] ?? $ticket['status'];
    $filePath = $ticket['file_path'];

    if ($title === '' || $description === '') {
        $error = "Title and Description are required.";
    } else {

        if (!empty($_FILES['file']['name'])) {
            $targetDir = __DIR__ . '/storage/uploads/';
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            $fileName = time() . "_" . basename($_FILES['file']['name']);
            $targetFile = $targetDir . $fileName;

            if (move_uploaded_file($_FILES['file']['tmp_name'], $targetFile)) {
                $filePath = $fileName;
            }
        }

        $completed_at = ($status === "completed") ? date("Y-m-d H:i:s") : $ticket['completed_at'];
        $updated_at = date("Y-m-d H:i:s");

        $stmt = $pdo->prepare("
            UPDATE tickets SET 
                title = ?, 
                description = ?, 
                status = ?, 
                file_path = ?, 
                updated_at = ?, 
                completed_at = ?
            WHERE id = ? AND author_id = ?
        ");

        $stmt->execute([
            $title,
            $description,
            $status,
            $filePath,
            $updated_at,
            $completed_at,
            $id,
            $userId
        ]);

        header("Location: tickets_view.php?id=" . $id . "&updated=1");
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Ticket - CRM</title>

    <style>
        body {
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #0d6efd, #6610f2);
            font-family: "Segoe UI", Arial, sans-serif;
            display: flex;
            justify-content: center;
            padding-top: 50px;
        }

        .card {
            width: 500px;
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(10px);
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            animation: fadeIn 0.4s ease-out;
        }

        @keyframes fadeIn {
            from { opacity:0; transform:translateY(10px); }
            to { opacity:1; transform:translateY(0); }
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #0d6efd;
            letter-spacing: 1px;
        }

        label {
            font-weight: bold;
            color: #444;
            display: block;
            margin-top: 12px;
        }

        input, textarea, select {
            width: 100%;
            padding: 12px;
            margin-top: 6px;
            border: 1px solid #cfd1d7;
            border-radius: 10px;
            background: #f8f9fc;
            font-size: 15px;
            transition: 0.25s;
        }

        input:focus, textarea:focus, select:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 5px rgba(13,110,253,0.3);
            background: #fff;
            outline: none;
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        .file-info {
            font-size: 14px;
            margin-top: 6px;
        }
        .file-info a {
            color: #0d6efd;
            text-decoration: none;
        }

        button {
            width: 100%;
            padding: 14px;
            margin-top: 25px;
            background: #0d6efd;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            cursor: pointer;
            transition: 0.25s;
        }

        button:hover {
            background: #0b5ed7;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 18px;
            color: #fff;
            text-decoration: none;
            font-weight: bold;
        }
        .back-link:hover {
            text-decoration: underline;
        }

        .error {
            color: #d62828;
            font-weight: bold;
            text-align: center;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>

<div class="card">

    <h2>Edit Ticket</h2>

    <?php if (!empty($error)): ?>
        <div class="error"><?= $error ?></div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">

        <label>Title</label>
        <input type="text" name="title" value="<?= htmlspecialchars($ticket['title']) ?>">

        <label>Description</label>
        <textarea name="description"><?= htmlspecialchars($ticket['description']) ?></textarea>

        <label>Status</label>
        <select name="status">
            <option value="pending" <?= $ticket['status']=='pending'?'selected':'' ?>>Pending</option>
            <option value="inprogress" <?= $ticket['status']=='inprogress'?'selected':'' ?>>In Progress</option>
            <option value="completed" <?= $ticket['status']=='completed'?'selected':'' ?>>Completed</option>
            <option value="onhold" <?= $ticket['status']=='onhold'?'selected':'' ?>>On Hold</option>
        </select>

        <label>File (optional)</label>
        <input type="file" name="file">

        <?php if (!empty($ticket['file_path'])): ?>
            <p class="file-info">Current File: 
                <a href="storage/uploads/<?= htmlspecialchars($ticket['file_path']) ?>" target="_blank">View / Download</a>
            </p>
        <?php endif; ?>

        <button type="submit">Update Ticket</button>
    </form>

    <a class="back-link" href="tickets_view.php?id=<?= $ticket['id'] ?>">← Back to Ticket</a>

</div>

</body>
</html>
