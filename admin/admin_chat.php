<?php
session_start();
include('db_connection.php');

// Admin harus login dulu
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

$admin_id = $_SESSION['admin_id'];

// Ambil daftar user yang pernah chat
$users = $conn->query("SELECT DISTINCT user_id FROM chats");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Live Chat</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-gray-100">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-lg mt-10">
        <h2 class="text-xl font-bold text-center">Admin Chat Panel</h2>

        <div class="flex">
            <!-- Sidebar User -->
            <div class="w-1/3 border-r p-4">
                <h3 class="font-semibold mb-3">Daftar User</h3>
                <ul id="user-list">
                    <?php while ($user = $users->fetch_assoc()): ?>
                        <li class="cursor-pointer p-2 border rounded-lg bg-gray-200 mb-2 hover:bg-gray-300"
                            onclick="loadChat(<?= $user['user_id'] ?>)">
                            User ID: <?= $user['user_id'] ?>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </div>

            <!-- Chat Box -->
            <div class="w-2/3 p-4">
                <div id="chat-box" class="h-96 overflow-y-auto border p-4 bg-gray-50 rounded-lg"></div>

                <form id="chat-form" class="mt-4 flex">
                    <input type="hidden" id="selected-user" value="">
                    <input type="text" id="message" class="flex-1 p-2 border rounded-l-lg" placeholder="Ketik pesan...">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-r-lg">Kirim</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function loadChat(userId) {
            $('#selected-user').val(userId);
            $.ajax({
                url: 'load_messages.php',
                method: 'GET',
                data: { user_id: userId },
                success: function(data) {
                    $('#chat-box').html(data);
                    $('#chat-box').scrollTop($('#chat-box')[0].scrollHeight);
                }
            });
        }

        $('#chat-form').submit(function(e) {
            e.preventDefault();
            var message = $('#message').val();
            var userId = $('#selected-user').val();
            if (message.trim() !== '' && userId !== '') {
                $.ajax({
                    url: 'send_message.php',
                    method: 'POST',
                    data: { message: message, user_id: userId, sender: 'admin' },
                    success: function() {
                        $('#message').val('');
                        loadChat(userId);
                    }
                });
            }
        });

        setInterval(function() {
            var userId = $('#selected-user').val();
            if (userId !== '') {
                loadChat(userId);
            }
        }, 3000);
    </script>
</body>
</html>
