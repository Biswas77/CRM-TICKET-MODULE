<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>CRM Ticket System</title>
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>

<?php if (!empty($_SESSION['user'])): ?>
<nav class="navbar">
    <div class="nav-left">CRM Ticket System</div>

    <div class="nav-right">


        <a href="index.php">Dashboard</a>
        <a href="tickets_list.php">My Tickets</a>
        <a href="tickets_create.php">Create Ticket</a>

        
        <?php if (!empty($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin'): ?>
            <a href="admin_users.php">All Users</a>
            <a href="admin_tickets.php">All Tickets</a>
        <?php endif; ?>

        <a href="logout.php" class="logout-btn">Logout</a>
    </div>
</nav>
<?php endif; ?>

<div class="container">
