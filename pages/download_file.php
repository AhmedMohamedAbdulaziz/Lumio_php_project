<?php

require_once "config.php";
require_once "auth_check.php";

$userId = $_SESSION["user_id"];

if (!isset($_GET["id"])) {
    die("Invalid file.");
}

$fileId = (int) $_GET["id"];

$stmt = $pdo->prepare("
    SELECT *
    FROM files
    WHERE id = ? AND user_id = ?
");

$stmt->execute([
    $fileId,
    $userId
]);

$file = $stmt->fetch();

if (!$file) {
    die("File not found.");
}

$filePath = dirname(__DIR__) . "/" . $file["file_path"];

if (!file_exists($filePath)) {
    die("Physical file not found.");
}

header("Content-Type: " . $file["file_type"]);

header(
    'Content-Disposition: attachment; filename="' .
    basename($file["original_name"]) .
    '"'
);

header("Content-Length: " . filesize($filePath));

readfile($filePath);

exit;