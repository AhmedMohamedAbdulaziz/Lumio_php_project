<?php

require_once "config.php";
require_once "auth_check.php";

$activeId = null;

$userId = $_SESSION["user_id"];

/* Total Pages */
$stmt = $pdo->prepare("
    SELECT COUNT(*)
    FROM pages
    WHERE user_id = ?
");
$stmt->execute([$userId]);
$totalPages = $stmt->fetchColumn();


/* Total Tasks */
$stmt = $pdo->prepare("
    SELECT COUNT(*)
    FROM tasks
    WHERE user_id = ?
");
$stmt->execute([$userId]);
$totalTasks = $stmt->fetchColumn();


/* Completed Tasks */
$stmt = $pdo->prepare("
    SELECT COUNT(*)
    FROM tasks
    WHERE user_id = ?
    AND status = 'done'
");
$stmt->execute([$userId]);
$completedTasks = $stmt->fetchColumn();


/* Total Projects */
$stmt = $pdo->prepare("
    SELECT COUNT(*)
    FROM projects
    WHERE user_id = ?
");
$stmt->execute([$userId]);
$totalProjects = $stmt->fetchColumn();


/* Total Events */
$stmt = $pdo->prepare("
    SELECT COUNT(*)
    FROM events
    WHERE user_id = ?
");
$stmt->execute([$userId]);
$totalEvents = $stmt->fetchColumn();

/* Total Files */

$stmt = $pdo->prepare("
    SELECT COUNT(*)
    FROM files
    WHERE user_id = ?
");

$stmt->execute([$userId]);

$totalFiles = $stmt->fetchColumn();

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

            <p>
                Track team productivity, document views, and system usage metrics.
            </p>

        </div>


        <div class="analytics-grid">


            <!-- Pages -->

            <div class="analytics-card">

                <div class="analytics-icon">
                    📄
                </div>

                <div>

                    <div class="analytics-number">
                        <?= $totalPages ?>
                    </div>

                    <div class="analytics-label">
                        Total Pages
                    </div>

                </div>

            </div>


            <!-- Tasks -->

            <div class="analytics-card">

                <div class="analytics-icon">
                    ✅
                </div>

                <div>

                    <div class="analytics-number">
                        <?= $totalTasks ?>
                    </div>

                    <div class="analytics-label">
                        Total Tasks
                    </div>

                </div>

            </div>


            <!-- Completed Tasks -->

            <div class="analytics-card">

                <div class="analytics-icon">
                    🎯
                </div>

                <div>

                    <div class="analytics-number">
                        <?= $completedTasks ?>
                    </div>

                    <div class="analytics-label">
                        Completed Tasks
                    </div>

                </div>

            </div>


            <!-- Projects -->

            <div class="analytics-card">

                <div class="analytics-icon">
                    📁
                </div>

                <div>

                    <div class="analytics-number">
                        <?= $totalProjects ?>
                    </div>

                    <div class="analytics-label">
                        Total Projects
                    </div>

                </div>

            </div>


            <!-- Events -->

            <div class="analytics-card">

                <div class="analytics-icon">
                    📅
                </div>

                <div>

                    <div class="analytics-number">
                        <?= $totalEvents ?>
                    </div>

                    <div class="analytics-label">
                        Total Events
                    </div>

                </div>

            </div>


            <!-- Page Views -->
<div class="analytics-card">

    <div class="analytics-icon">
        📂
    </div>

    <div>

        <div class="analytics-number">
            <?= $totalFiles ?>
        </div>

        <div class="analytics-label">
            Total Files
        </div>

    </div>

</div>

        </div>

    </div>

</div>

</body>

</html>