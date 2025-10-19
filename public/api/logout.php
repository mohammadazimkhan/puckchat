<?php
require_once '../../includes/auth.php';

header('Content-Type: application/json');

$result = Auth::logout();
echo json_encode($result);
?>