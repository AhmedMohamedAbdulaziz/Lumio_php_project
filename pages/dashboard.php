<?php
require_once "config.php";
require_once "auth_check.php";

$userId = $_SESSION["user_id"];

$stmt = $pdo->prepare("SELECT COUNT(*) FROM pages WHERE user_id = ?");
$stmt->execute([$userId]);
$totalPages = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT COUNT(*) FROM pages WHERE user_id = ? AND parent_id IS NULL");
$stmt->execute([$userId]);
$rootPages = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT COUNT(*) FROM pages WHERE user_id = ? AND parent_id IS NOT NULL");
$stmt->execute([$userId]);
$subPages = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT id, title, icon, updated_at FROM pages WHERE user_id = ? ORDER BY updated_at DESC LIMIT 5");
$stmt->execute([$userId]);
$recentPages = $stmt->fetchAll();

$activeId = null;
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <title>Lumio - Dashboard</title>
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/dashboard.css">
</head>
<body>
    <div class="app-layout">
        <?php include "sidebar.php"; ?>

        <div class="main-content">
            <div class="dashboard-header">
                <h1>Welcome back, <?= htmlspecialchars($_SESSION["username"]) ?> 👋</h1>
                <p>Here is your workspace overview and quick stats.</p>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">📑</div>
                    <div class="stat-value"><?= $totalPages ?></div>
                    <div class="stat-label">Total Workspace Pages</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">📄</div>
                    <div class="stat-value"><?= $rootPages ?></div>
                    <div class="stat-label">Root Level Pages</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">🌿</div>
                    <div class="stat-value"><?= $subPages ?></div>
                    <div class="stat-label">Nested Sub-Pages</div>
                </div>
            </div>
            <div class="card-panel">
                <h2>Recent Workspace Pages</h2>
                <?php if (count($recentPages) > 0): ?>
                    <ul class="page-tree">
                        <?php foreach ($recentPages as $p): ?>
                            <li>
                                <a class="page-link" href="page.php?id=<?= $p['id'] ?>">
                                    <?= htmlspecialchars($p['icon']) ?> <?= htmlspecialchars($p['title']) ?>
                                    <span style="float: right; color: #737373; font-size: 12px;"><?= $p['updated_at'] ?></span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p style="color: #a3a3a3; font-size: 14px;">No pages created yet. Click "+ New Page" in the sidebar to create one!</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
