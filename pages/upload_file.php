<?php

require_once "config.php";
require_once "auth_check.php";

$userId = $_SESSION["user_id"];

if (!isset($_FILES["file"])) {
    die("No file selected.");
}

$file = $_FILES["file"];

if ($file["error"] !== UPLOAD_ERR_OK) {
    die("File upload failed.");
}

// Maximum file size: 10 MB
$maxSize = 10 * 1024 * 1024;

if ($file["size"] > $maxSize) {
    die("File is too large. Maximum size is 10 MB.");
}

// Allowed file types
$allowedExtensions = [
    "jpg",
    "jpeg",
    "png",
    "gif",
    "pdf",
    "doc",
    "docx",
    "xls",
    "xlsx",
    "txt",
    "zip"
];

$originalName = basename($file["name"]);

$extension = strtolower(
    pathinfo($originalName, PATHINFO_EXTENSION)
);

if (!in_array($extension, $allowedExtensions)) {
    die("This file type is not allowed.");
}

// Create uploads folder
$uploadDirectory = dirname(__DIR__) . "/uploads/";

if (!is_dir($uploadDirectory)) {
    mkdir($uploadDirectory, 0755, true);
}

// Create unique filename
$newFileName = uniqid("file_", true) . "." . $extension;

$filePath = $uploadDirectory . $newFileName;

// Move uploaded file
if (!move_uploaded_file($file["tmp_name"], $filePath)) {
    die("Could not save the file.");
}

// Get file MIME type
$finfo = new finfo(FILEINFO_MIME_TYPE);
$fileType = $finfo->file($filePath);

// Save file information in database
$stmt = $pdo->prepare("
    INSERT INTO files
    (
        user_id,
        filename,
        original_name,
        file_type,
        file_size,
        file_path
    )
    VALUES (?, ?, ?, ?, ?, ?)
");

$stmt->execute([
    $userId,
    $newFileName,
    $originalName,
    $fileType,
    $file["size"],
    "uploads/" . $newFileName
]);

// Go back to File Manager
header("Location: file_manager.php");

exit;