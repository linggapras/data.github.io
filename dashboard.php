<?php
include 'includes/auth.php';
include 'includes/db.php';

if ($_SESSION['role'] == 'admin') {
    $users = $conn->query("SELECT * FROM users");
} else {
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param('s', $_SESSION['username']);
    $stmt->execute();
    $users = $stmt->get_result();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h2>Dashboard</h2>
    <?php if ($_SESSION['role'] == 'admin'): ?>
        <h3>All Users</h3>
        <table>
            <tr>
                <th>Name</th>
                <th>Username</th>
                <th>Address</th>
                <th>Email</th>
            </tr>
            <?php while ($user = $users->fetch_assoc()): ?>
                <tr>
                    <td><?= $user['name'] ?></td>
                    <td><?= $user['username'] ?></td>
                    <td><?= $user['address'] ?></td>
                    <td><?= $user['email'] ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <h3>Your Profile</h3>
        <?php $user = $users->fetch_assoc(); ?>
        <p>Name: <?= $user['name'] ?></p>
        <p>Address: <?= $user['address'] ?></p>
        <p>Email: <?= $user['email'] ?></p>
    <?php endif; ?>
    <a href="logout.php">Logout</a>
</body>
</html>
