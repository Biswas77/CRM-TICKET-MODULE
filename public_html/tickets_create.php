<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/src/db.php';
require_once __DIR__ . '/src/helpers/functions.php';

requireLogin();

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $title = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $status = $_POST['status'] ?? 'pending';
    $author_id = currentUserId();
    $filePath = null;

    $completed_at = ($status === "completed") ? date("Y-m-d H:i:s") : null;
    $updated_at = date("Y-m-d H:i:s");

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

        $stmt = $pdo->prepare("
            INSERT INTO tickets 
            (title, description, status, file_path, author_id, created_at, updated_at, completed_at, deleted_at)
            VALUES (?, ?, ?, ?, ?, NOW(), ?, ?, NULL)
        ");

        $stmt->execute([
            $title,
            $description,
            $status,
            $filePath,
            $author_id,
            $updated_at,
            $completed_at
        ]);

        header("Location: tickets_view.php?created=1");
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Create Ticket - CRM</title>

    <style>
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #f3f4f6;
            font-family: 'Segoe UI', Poppins, Arial, sans-serif;
        }

        .wrapper {
            width: 420px;
            background: #ffffff;
            padding: 35px 40px;
            border-radius: 18px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.12);
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        h2 {
            margin-top: 0;
            text-align: center;
            color: #111827;
            font-size: 26px;
            font-weight: bold;
        }

        label {
            margin-top: 12px;
            display: block;
            font-weight: 600;
            color: #374151;
            font-size: 14px;
        }

        input, textarea, select {
            width: 100%;
            margin-top: 6px;
            padding: 12px;
            border-radius: 10px;
            border: 1px solid #d1d5db;
            background: #f9fafb;
            font-size: 14px;
            transition: 0.2s;
        }

        input:focus, textarea:focus, select:focus {
            border-color: #6366f1;
            background: #fff;
            box-shadow: 0 0 5px rgba(99,102,241,0.3);
            outline: none;
        }

        button {
            width: 100%;
            padding: 12px;
            border: none;
            margin-top: 18px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 10px;
            cursor: pointer;
            background: linear-gradient(135deg, #4f46e5, #6366f1);
            color: white;
            transition: 0.2s;
        }

        button:hover {
            background: linear-gradient(135deg, #4338ca, #4f46e5);
        }

        .back {
            text-align: center;
            display: block;
            margin-top: 15px;
            color: #4f46e5;
            font-weight: 500;
            text-decoration: none;
        }

        .back:hover {
            text-decoration: underline;
        }

        .error {
            background: #fee2e2;
            padding: 10px;
            border-radius: 8px;
            color: #b91c1c;
            margin-bottom: 10px;
            font-size: 14px;
            text-align: center;
        }
    </style>
</head>

<body>

<div class="wrapper">
    <h2>Create Ticket</h2>

    <?php if (!empty($error)): ?>
        <div class="error"><?= $error ?></div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">

        <label>Title</label>
        <input type="text" name="name" placeholder="Enter a short title">

        <label>Description</label>
        <textarea name="description" rows="4" placeholder="Describe the issue..."></textarea>

        <label>Status</label>
        <select name="status">
            <option value="pending">Pending</option>
            <option value="inprogress">In Progress</option>
            <option value="completed">Completed</option>
            <option value="onhold">On Hold</option>
        </select>

        <label>Attach File</label>
        <input type="file" name="file">

        <button type="submit">Create Ticket</button>
    </form>

    <a class="back" href="index.php">← Back to Dashboard</a>
</div>

</body>
</html>
