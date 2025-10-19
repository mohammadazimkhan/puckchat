<?php
// Utility functions for PuckChat
function generateUserId() {
    return uniqid('user_', true);
}

function sanitizeInput($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}
?>