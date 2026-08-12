
<?php

require_once "config.php";
require_once "auth_check.php";

$userId = $_SESSION["user_id"];

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: file_manager.php");
    exit;
}

$fileId = $_POST["id"] ?? 0;

if (!$fileId) {
    header("Location: file_manager.php");
    exit;
}

$stmt = $pdo->prepare("
    UPDATE files
    SET deleted_at = NOW()
    WHERE id = ? AND user_id = ?
");

$stmt->execute([
    $fileId,
    $userId
]);

header("Location: file_manager.php");
exit;

