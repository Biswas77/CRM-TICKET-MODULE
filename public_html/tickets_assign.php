<?php
require_once __DIR__ . '/src/db.php';
require_once __DIR__ . '/src/helpers/functions.php';

requireLogin();

$ticketId = $_GET['id'] ?? 0;
$userId = currentUserId();
$userRole = $_SESSION['user']['role'] ?? 'user';

// Fetch ticket
$stmt = $pdo->prepare("SELECT * FROM tickets WHERE id = ?");
$stmt->execute([$ticketId]);
$ticket = $stmt->fetch();

if (!$ticket) {
    die("Ticket not found.");
}

// Permissions: Only admin or ticket creator
if ($ticket['author_id'] != $userId && $userRole !== 'admin') {
    die("Access denied: You cannot assign this ticket.");
}

// Fetch all users
$usersStmt = $pdo->query("SELECT id, name FROM users ORDER BY name ASC");
$users = $usersStmt->fetchAll();

// If form submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $assignee = $_POST['assignee'] ?? 0;

    if (!$assignee) {
        $error = "Please select a user.";
    } else {

        // Remove old assignments
        $pdo->prepare("DELETE FROM ticket_assignments WHERE ticket_id = ?")
            ->execute([$ticketId]);

        // Add new assignment
        $stmtAssign = $pdo->prepare("
            INSERT INTO ticket_assignments (ticket_id, assignee_id) 
            VALUES (?, ?)
        ");
        $stmtAssign->execute([$ticketId, $assignee]);

        header("Location: admin_tickets.php?id=" . $ticketId);
        exit;
    }
}
?>

<?php require_once __DIR__ . '/layout/header.php'; ?>

<div class="card">
    <h2>Assign Ticket</h2>
    <p>Ticket: <b><?= htmlspecialchars($ticket['title']) ?></b></p>

    <?php if (!empty($error)): ?>
        <p style="color:red;"><?= $error ?></p>
    <?php endif; ?>

    <form method="post">
        <label><b>Select user to assign:</b></label>
        <select name="assignee" required style="width:100%;padding:10px;border-radius:6px;">
            <option value="">-- Select User --</option>

            <?php foreach ($users as $u): ?>
                <option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['name']) ?></option>
            <?php endforeach; ?>

        </select>

        <br><br>
        <button class="btn" type="submit">Assign Ticket</button>
    </form>

    <br>
    <a class="btn" style="background:#6c757d;" href="tickets_view.php?id=<?= $ticketId ?>">Back</a>
</div>

<?php require_once __DIR__ . '/layout/footer.php'; ?>
