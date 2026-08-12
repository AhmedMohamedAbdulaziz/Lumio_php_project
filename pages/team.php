<?php

require_once "config.php";
require_once "auth_check.php";

$activeId = null;

$stmt = $pdo->query("
    SELECT id, username, email, created_at
    FROM users
    ORDER BY created_at DESC
");

$users = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>

    <meta charset="UTF-8">

    <title>Team - Lumio Module</title>

    <link rel="stylesheet" href="../css/base.css">

    <link rel="stylesheet" href="../css/modules.css">

</head>

<body>

<div class="app-layout">

    <?php include "sidebar.php"; ?>


    <div class="main-content">

        <div class="dashboard-header">

            <h1>Team Members 👥</h1>

            <p>
                View and manage members of your workspace.
            </p>

        </div>


        <div class="team-grid">

            <?php foreach ($users as $user): ?>

                <div class="team-card">

                    <div class="team-avatar">
                        <?= strtoupper(substr($user["username"], 0, 1)) ?>
                    </div>

                    <div class="team-info">

                        <h2>
                            <?= htmlspecialchars($user["username"]) ?>
                        </h2>

                        <p>
                            <?= htmlspecialchars($user["email"]) ?>
                        </p>

                        <span class="team-role">
                            Team Member
                        </span>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>

    </div>

</div>

</body>

</html>