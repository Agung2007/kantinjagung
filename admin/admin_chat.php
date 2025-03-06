<?php
session_start();
include('db_connection.php');

// Pastikan admin sudah login
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

$admin_id = $_SESSION['admin_id'];

// Jika ada pesan yang dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['message'], $_POST['user_id'])) {
    $message = trim($_POST['message']);
    $user_id = intval($_POST['user_id']);
    
    if (!empty($message)) {
        $stmt = $conn->prepare("INSERT INTO chats (user_id, admin_id, message, sender) VALUES (?, ?, ?, 'admin')");
        $stmt->bind_param("iis", $user_id, $admin_id, $message);
        $stmt->execute();
        $stmt->close();
    }
}

$query = "
    SELECT user_id, MAX(created_at) as last_message_time
    FROM chats
    GROUP BY user_id
    ORDER BY last_message_time DESC
";

$users = $conn->query($query);

// Tambahkan pengecekan error
if (!$users) {
    die("Query Error: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Chat</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="max-w-4xl w-full bg-white p-6 rounded-xl shadow-lg mt-10">
        <h2 class="text-3xl font-extrabold text-center mb-6 text-blue-700">Admin Chat</h2>
        
        <div class="flex">
            <!-- Sidebar daftar user -->
            <div class="w-1/3 bg-blue-900 text-white p-4 rounded-l-xl overflow-y-auto h-96">
                <h3 class="text-xl font-bold mb-4">Users</h3>
                <ul>
                    <?php while ($user = $users->fetch_assoc()): ?>
                        <li>
                            <button class="user-btn w-full text-left p-2 mb-2 bg-blue-700 hover:bg-blue-600 rounded"
                                    data-user-id="<?= $user['user_id'] ?>">
                                User <?= $user['user_id'] ?>
                            </button>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </div>

            <!-- Chat Box -->
            <div class="w-2/3 p-4">
                <div id="chat-box" class="h-96 overflow-y-auto border border-gray-300 p-4 bg-gray-50 rounded-xl shadow-inner"></div>
                
                <form id="chat-form" method="POST" class="mt-4 flex">
                    <input type="hidden" name="user_id" id="user_id">
                    <input type="text" name="message" id="message"
                           class="flex-1 p-3 border border-gray-300 rounded-l-xl focus:outline-none focus:ring-2 focus:ring-blue-500 placeholder-gray-400"
                           placeholder="Ketik pesan...">
                    <button type="submit"
                            class="bg-blue-700 text-white px-6 py-3 rounded-r-xl hover:bg-blue-800 transition font-bold shadow-md">
                        Kirim
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            function loadMessages(userId) {
                $.ajax({
                    url: 'load_admin_messages.php',
                    method: 'GET',
                    data: { user_id: userId },
                    success: function (data) {
                        $('#chat-box').html(data);
                        $('#chat-box').scrollTop($('#chat-box')[0].scrollHeight);
                    }
                });
            }

            $('.user-btn').click(function () {
                var userId = $(this).data('user-id');
                $('#user_id').val(userId);
                loadMessages(userId);
            });

            $('#chat-form').submit(function (e) {
                e.preventDefault();
                var message = $('#message').val();
                var userId = $('#user_id').val();
                
                if (message.trim() !== '' && userId !== '') {
                    $.ajax({
                        url: 'admin_chat.php',
                        method: 'POST',
                        data: { message: message, user_id: userId },
                        success: function () {
                            $('#message').val('');
                            loadMessages(userId);
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>
