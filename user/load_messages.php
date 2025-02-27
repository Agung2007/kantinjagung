<?php
session_start();
include('db_connection.php');

$user_id = $_SESSION['user_id'];
$messages = $conn->query("SELECT * FROM chats WHERE user_id = $user_id ORDER BY created_at ASC");

while ($row = $messages->fetch_assoc()): ?>
    <div class="mb-2 <?= $row['sender'] == 'user' ? 'text-right' : 'text-left' ?>">
        <span class="px-3 py-2 rounded-lg inline-block <?= $row['sender'] == 'user' ? 'bg-blue-500 text-white' : 'bg-gray-300' ?>">
            <?= htmlspecialchars($row['message']) ?>
        </span>
    </div>
<?php endwhile; ?>
