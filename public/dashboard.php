<?php
require_once '../includes/auth.php';

// Check if user is logged in
if (!Auth::isLoggedIn()) {
    header('Location: login.html');
    exit;
}

$user = Auth::getCurrentUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - PuckChat</title>
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="auth-container">
            <div class="auth-header">
                <h1>Welcome to PuckChat! 🏒</h1>
                <p>Hey <strong><?php echo htmlspecialchars($user['username']); ?></strong>!</p>
            </div>
            
            <div class="auth-form">
                <div style="text-align: center; padding: 40px 0;">
                    <h2>🚧 Coming Soon</h2>
                    <p>Chat system is under development</p>
                    <p>You're successfully logged in!</p>
                    
                    <div style="margin: 30px 0;">
                        <p><strong>Your Info:</strong></p>
                        <p>Username: <?php echo htmlspecialchars($user['username']); ?></p>
                        <p>Login Time: <?php echo date('Y-m-d H:i:s', $user['login_time']); ?></p>
                    </div>
                    
                    <button onclick="logout()" class="auth-btn">Logout</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function logout() {
            fetch('api/logout.php', { method: 'POST' })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = 'index.html';
                    }
                });
        }
    </script>
</body>
</html>