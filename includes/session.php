<?php
// Session management for anonymous users
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = generateUserId();
    $_SESSION['created_at'] = time();
}
?>