<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit;
}

include 'includes/db.php';
$username = $_SESSION['username'];

// Celah: Tidak melakukan validasi atau sanitasi input
$sql = "SELECT * FROM users WHERE username = '$username'"; // Celah SQL Injection
$result = $conn->query($sql);
$user = $result->fetch_assoc();

// Menangani pengiriman pesan
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['message'])) {
    $message = $_POST['message'];
    $receiver = $_POST['receiver'];

    // Celah: Tidak ada sanitasi input dari pesan dan penerima
    $sql_send = "INSERT INTO messages (sender, receiver, message) VALUES ('$username', '$receiver', '$message')";
    $conn->query($sql_send);
}

// Menampilkan pesan
$sql_messages = "SELECT * FROM messages WHERE sender = '$username' OR receiver = '$username' ORDER BY created_at DESC"; // Celah: SQL Injection
$messages_result = $conn->query($sql_messages);
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #e0f7fa; /* Biru langit */
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column; /* Menyusun elemen dalam kolom */
        }

        h2 {
            text-align: center;
            color: #333;
        }

        .profile-card {
            width: 80%;
            max-width: 400px;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            text-align: center;
        }

        .profile-card h3 {
            color: #007BFF; /* Warna biru langit pada judul */
            margin-bottom: 20px;
        }

        .profile-card p {
            font-size: 18px;
            color: #555;
            margin: 10px 0;
        }

        .profile-card a {
            display: block;
            text-align: center;
            margin-top: 20px;
            padding: 10px;
            background-color: #007BFF; /* Warna biru langit untuk tombol */
            color: white;
            text-decoration: none;
            border-radius: 5px;
            width: 200px;
            margin: 0 auto;
        }

        .profile-card a:hover {
            background-color: #0091ea; /* Warna biru langit lebih gelap pada hover */
        }

        /* Chat Area */
        .chat-container {
            margin-top: 30px;
            width: 80%;
            max-width: 400px;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .messages {
            height: 200px;
            overflow-y: scroll;
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 20px;
        }

        .messages p {
            font-size: 14px;
            margin: 5px 0;
        }

        .message-form input, .message-form button {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .message-form button {
            background-color: #007BFF;
            color: white;
            cursor: pointer;
        }

        .message-form button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="profile-card">
        <h3>Welcome, <?= $user['name'] ?></h3>
        <p><strong>Name:</strong> <?= $user['name'] ?></p>
        <p><strong>Address:</strong> <?= $user['address'] ?></p>
        <p><strong>Email:</strong> <?= $user['email'] ?></p>
        <a href="logout.php">Logout</a>
    </div>

    <!-- Chat Feature -->
    <div class="chat-container">
        <h3>Chat</h3>
        <div class="messages">
            <?php
            while ($message = $messages_result->fetch_assoc()) {
                echo "<p><strong>{$message['sender']}:</strong> {$message['message']}</p>";
            }
            ?>
        </div>
        <form class="message-form" method="POST">
            <input type="text" name="receiver" placeholder="Recipient Username" required />
            <textarea name="message" placeholder="Write your message..." required></textarea>
            <button type="submit">Send Message</button>
        </form>
    </div>
</body>
</html>