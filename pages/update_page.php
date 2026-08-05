<?php
require_once "config.php";
require_once "auth_check.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id      = (int)$_POST["id"];
    $title   = trim($_POST["title"]) !== "" ? trim($_POST["title"]) : "Untitled Page";
    $icon    = trim($_POST["icon"]) !== "" ? trim($_POST["icon"]) : "📄";
    $content = $_POST["content"];
    $userId  = $_SESSION["user_id"];

    $stmt = $pdo->prepare("UPDATE pages SET title = ?, icon = ?, content = ? WHERE id = ? AND user_id = ?");
    $stmt->execute([$title, $icon, $content, $id, $userId]);

    header("Location: page.php?id={$id}");
    exit;
}

header("Location: dashboard.php");
exit;
