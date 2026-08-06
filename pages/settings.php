<?php
require_once "config.php";
require_once "auth_check.php";
$activeId = null;
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <title>System Settings - Lumio Module</title>
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/modules.css">
</head>
<body>
    <div class="app-layout">
        <?php include "sidebar.php"; ?>

        <div class="main-content">
            <div class="dashboard-header">
                <h1>Application Settings ⚙️</h1>
                <p>Configure app themes, security policies, and workspace preferences.</p>
            </div>

            <div class="team-placeholder">
                <div class="module-icon">🛠</div>
                <h2>Application Settings Module</h2>
                <p>This module is prepared for Team Member 4 to implement workspace configuration, theme toggles, and API keys.</p>
                <span class="badge badge-warning">Pending Team Implementation</span>
            </div>
        </div>
    </div>
</body>
</html>
