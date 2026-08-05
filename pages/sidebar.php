<?php
$currentPage = basename($_SERVER['PHP_SELF']);

function renderPages($pdo, $userId, $parentId, $activeId = null) {
    $stmt = $pdo->prepare("SELECT id, title, icon FROM pages WHERE user_id = ? AND parent_id <=> ? ORDER BY created_at ASC");
    $stmt->execute([$userId, $parentId]);
    $pages = $stmt->fetchAll();

    if (count($pages) === 0) return;

    echo "<ul class='page-tree'>";
    foreach ($pages as $p) {
        $activeClass = ($activeId == $p["id"]) ? "active" : "";
        echo "<li>";
        echo "<a class='page-link {$activeClass}' href='page.php?id={$p['id']}'>";
        echo htmlspecialchars($p["icon"]) . " " . htmlspecialchars($p["title"]);
        echo "</a>";
        renderPages($pdo, $userId, $p["id"], $activeId);
        echo "</li>";
    }
    echo "</ul>";
}
?>

<div class="sidebar">
    <div class="sidebar-header">
        <span>Lumio ✨</span>
        <a href="logout.php" class="logout-link">Logout</a>
    </div>

    <div class="nav-section">
        <div class="nav-section-title">Core Pages</div>
        <a href="dashboard.php" class="nav-item <?= $currentPage === 'dashboard.php' ? 'active' : '' ?>">📊 Dashboard</a>
        <a href="profile.php" class="nav-item <?= $currentPage === 'profile.php' ? 'active' : '' ?>">👤 Profile</a>
    </div>

    <div class="nav-section">
        <div class="nav-section-title">Team Modules</div>
        <a href="tasks.php" class="nav-item <?= $currentPage === 'tasks.php' ? 'active' : '' ?>">✅ Tasks</a>
        <a href="calendar.php" class="nav-item <?= $currentPage === 'calendar.php' ? 'active' : '' ?>">📅 Calendar</a>
        <a href="projects.php" class="nav-item <?= $currentPage === 'projects.php' ? 'active' : '' ?>">📁 Projects</a>
        <a href="team.php" class="nav-item <?= $currentPage === 'team.php' ? 'active' : '' ?>">👥 Team Members</a>
        <a href="analytics.php" class="nav-item <?= $currentPage === 'analytics.php' ? 'active' : '' ?>">📈 Analytics</a>
        <a href="file_manager.php" class="nav-item <?= $currentPage === 'file_manager.php' ? 'active' : '' ?>">📂 File Manager</a>
        <a href="settings.php" class="nav-item <?= $currentPage === 'settings.php' ? 'active' : '' ?>">⚙️ Settings</a>
        <a href="notifications.php" class="nav-item <?= $currentPage === 'notifications.php' ? 'active' : '' ?>">🔔 Notifications</a>
        <a href="templates.php" class="nav-item <?= $currentPage === 'templates.php' ? 'active' : '' ?>">📑 Templates</a>
        <a href="trash.php" class="nav-item <?= $currentPage === 'trash.php' ? 'active' : '' ?>">🗑 Trash</a>
    </div>

    <div class="nav-section">
        <div class="nav-section-title">My Pages</div>
        <form action="create_page.php" method="POST" class="new-page-form">
            <input type="hidden" name="parent_id" value="">
            <button type="submit">+ New Page</button>
        </form>
        <div class="tree-wrapper">
            <?php renderPages($pdo, $_SESSION["user_id"], null, $activeId ?? null); ?>
        </div>
    </div>
</div>
