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
    $message = trim(string: $_POST['message']);
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

<body class="bg-white flex items-center justify-center min-h-screen">
    <div class="max-w-lg w-full bg-white p-6 rounded-2xl shadow-2xl mt-10 relative">
        <h2 class="text-3xl font-extrabold text-center mb-6 text-blue-700 flex items-center justify-center">
            CHAT ADMIN
        </h2>

        <div id="chat-box" class="h-96 overflow-y-auto border border-gray-300 p-4 bg-gray-50 rounded-xl shadow-inner">
            <?php while ($row = $messages->fetch_assoc()): ?>
            <div class="mb-2 flex <?= $row['sender'] == 'user' ? 'justify-end' : 'justify-start' ?>">
                <span
                    class="px-4 py-2 rounded-xl inline-block <?= $row['sender'] == 'user' ? 'bg-blue-600 text-white' : 'bg-gray-300 text-gray-900' ?> shadow-md">
                    <?= htmlspecialchars($row['message']) ?>
                </span>
            </div>
            <?php endwhile; ?>
        </div>

        <form id="chat-form" method="POST" class="mt-4 flex">
            <input type="text" name="message" id="message"
                class="flex-1 p-3 border border-gray-300 rounded-l-xl focus:outline-none focus:ring-2 focus:ring-blue-500 placeholder-gray-400"
                placeholder="Ketik pesan...">
            <button type="submit"
                class="bg-blue-700 text-white px-6 py-3 rounded-r-xl hover:bg-blue-800 transition font-bold shadow-md">
                 Kirim
            </button>
        </form>

        <!-- Tombol Kembali -->
        <div class="mt-6 text-center">
            <a href="menu.php"
                class="bg-gray-700 text-white px-6 py-3 rounded-xl hover:bg-gray-800 transition font-bold shadow-md inline-flex items-center">
                 Kembali ke Menu
            </a>
        </div>
    </div>
</body>

    <script>
        $(document).ready(function () {
            function loadMessages() {
                $.ajax({
                    url: 'load_messages.php',
                    method: 'GET',
                    success: function (data) {
                        $('#chat-box').html(data);
                        $('#chat-box').scrollTop($('#chat-box')[0].scrollHeight);
                    }
                });
            }

            $('#chat-form').submit(function (e) {
                e.preventDefault();
                var message = $('#message').val();
                if (message.trim() !== '') {
                    $.ajax({
                        url: 'chat.php',
                        method: 'POST',
                        data: {
                            message: message
                        },
                        success: function () {
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