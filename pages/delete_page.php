<?php
require_once "config.php";
require_once "auth_check.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id     = (int)$_POST["id"];
    $userId = $_SESSION["user_id"];

    $stmt = $pdo->prepare("DELETE FROM pages WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $userId]);
}

header("Location: dashboard.php");
exit;
