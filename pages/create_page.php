<?php
require_once "config.php";
require_once "auth_check.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $userId   = $_SESSION["user_id"];
    $parentId = !empty($_POST["parent_id"]) ? (int)$_POST["parent_id"] : null;

    $stmt = $pdo->prepare("INSERT INTO pages (user_id, parent_id, title, content) VALUES (?, ?, 'Untitled Page', '')");
    $stmt->execute([$userId, $parentId]);

    $newId = $pdo->lastInsertId();
    header("Location: page.php?id={$newId}");
    exit;
}

header("Location: dashboard.php");
exit;
