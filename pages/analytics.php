<?php
require_once "config.php";
require_once "auth_check.php";
$activeId = null;
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <title>Analytics - Lumio Module</title>
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/modules.css">
</head>
<body>
    <div class="app-layout">
        <?php include "sidebar.php"; ?>

        <div class="main-content">
            <div class="dashboard-header">
                <h1>Reports & Analytics 📈</h1>
                <p>Track team productivity, document views, and system usage metrics.</p>
            </div>

            <div class="team-placeholder">
                <div class="module-icon">📊</div>
                <h2>Reports & Analytics Module</h2>
                <p>This module is prepared for Team Member 3 to implement chart visualizers, exportable reports, and usage metrics.</p>
                <span class="badge badge-warning">Pending Team Implementation</span>
            </div>
        </div>
    </div>
</body>
</html>
