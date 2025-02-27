<?php
session_start();
include('db_connection.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$admin_id = 1; // ID admin tetap, sesuaikan dengan database

// Jika ada pesan yang dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['message'])) {
    $message = trim($_POST['message']);
    if (!empty($message)) {
        $stmt = $conn->prepare("INSERT INTO chats (user_id, admin_id, message, sender) VALUES (?, ?, ?, 'user')");
        $stmt->bind_param("iis", $user_id, $admin_id, $message);
        $stmt->execute();
        $stmt->close();
    }
}

// Ambil semua pesan
$messages = $conn->query("SELECT * FROM chats WHERE user_id = $user_id ORDER BY created_at ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Chat</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-gray-100">
    <div class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow-lg mt-10 relative">
        <h2 class="text-2xl font-bold text-center mb-4 text-blue-600">💬 Live Chat dengan Admin</h2>

        <div id="chat-box" class="h-96 overflow-y-auto border p-4 bg-gray-50 rounded-lg shadow-inner">
            <?php while ($row = $messages->fetch_assoc()): ?>
                <div class="mb-2 <?= $row['sender'] == 'user' ? 'text-right' : 'text-left' ?>">
                    <span class="px-4 py-2 rounded-lg inline-block <?= $row['sender'] == 'user' ? 'bg-blue-500 text-white' : 'bg-gray-300 text-gray-800' ?>">
                        <?= htmlspecialchars($row['message']) ?>
                    </span>
                </div>
            <?php endwhile; ?>
        </div>

        <form id="chat-form" method="POST" class="mt-4 flex">
            <input type="text" name="message" id="message" class="flex-1 p-3 border rounded-l-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Ketik pesan...">
            <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-r-lg hover:bg-blue-700 transition font-bold">Kirim</button>
        </form>

        <!-- Tombol Kembali -->
        <div class="mt-6 text-center">
            <a href="menu.php" class="bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition font-bold shadow-md">
                ⬅ Kembali ke Menu
            </a>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            function loadMessages() {
                $.ajax({
                    url: 'load_messages.php',
                    method: 'GET',
                    success: function(data) {
                        $('#chat-box').html(data);
                        $('#chat-box').scrollTop($('#chat-box')[0].scrollHeight);
                    }
                });
            }

            $('#chat-form').submit(function(e) {
                e.preventDefault();
                var message = $('#message').val();
                if (message.trim() !== '') {
                    $.ajax({
                        url: 'chat.php',
                        method: 'POST',
                        data: { message: message },
                        success: function() {
                            $('#message').val('');
                            loadMessages();
                        }
                    });
                }
            });

            setInterval(loadMessages, 3000);
        });
    </script>
</body>
</html>
