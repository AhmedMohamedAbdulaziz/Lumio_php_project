<?php
require_once "config.php";
require_once "auth_check.php";

$activeId = isset($_GET["id"]) ? (int)$_GET["id"] : 0;
$userId = $_SESSION["user_id"];

$stmt = $pdo->prepare("SELECT * FROM pages WHERE id = ? AND user_id = ?");
$stmt->execute([$activeId, $userId]);
$page = $stmt->fetch();

if (!$page) {
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($page["title"]) ?> - Lumio</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="app-layout">
        <?php include "sidebar.php"; ?>

        <div class="main-content">
            <form action="update_page.php" method="POST" class="page-editor" id="pageForm">
                <input type="hidden" name="id" value="<?= $page['id'] ?>">

                <div class="page-toolbar">
                    <input type="text" name="icon" class="icon-input" value="<?= htmlspecialchars($page['icon']) ?>" maxlength="4">
                    <input type="text" name="title" class="title-input" value="<?= htmlspecialchars($page['title']) ?>" placeholder="Untitled">
                </div>

                <textarea name="content" class="content-area" placeholder="Type something here..."><?= htmlspecialchars($page['content']) ?></textarea>

                <div class="page-actions">
                    <button type="submit">💾 Save</button>
                    <span id="saveStatus"></span>
                </div>
            </form>

            <div class="sub-actions">
                <form action="create_page.php" method="POST">
                    <input type="hidden" name="parent_id" value="<?= $page['id'] ?>">
                    <button type="submit">+ Sub-page</button>
                </form>

                <form action="delete_page.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this page and all of its sub-pages?');">
                    <input type="hidden" name="id" value="<?= $page['id'] ?>">
                    <button type="submit" class="danger-btn">🗑 Delete Page</button>
                </form>
            </div>
        </div>
    </div>

    <script src="../js/script.js"></script>
</body>
</html>
