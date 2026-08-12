<?php

require_once "config.php";
require_once "auth_check.php";

$templates = [
    [
        "name" => "Meeting Notes",
        "description" => "Template for meetings, discussions and action items.",
        "icon" => "📝"
    ],
    [
        "name" => "Project Plan",
        "description" => "Organize project goals, tasks and deadlines.",
        "icon" => "📋"
    ],
    [
        "name" => "Daily Notes",
        "description" => "Simple template for your daily notes and ideas.",
        "icon" => "📒"
    ],
    [
        "name" => "Documentation",
        "description" => "Create organized technical documentation.",
        "icon" => "📚"
    ]
];

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Templates Library - Lumio</title>

    <link rel="stylesheet" href="../css/base.css">

    <link rel="stylesheet" href="../css/modules.css">

</head>

<body>

<div class="app-layout">

    <?php include "sidebar.php"; ?>

    <div class="main-content">

        <div class="dashboard-header">

            <h1>Templates Library 📑</h1>

            <p>
                Browse pre-designed page layouts for meetings, notes, and docs.
            </p>

        </div>


        <div class="templates-grid">

            <?php foreach ($templates as $template): ?>

                <div class="template-card">

                    <div class="template-icon">
                        <?= $template["icon"] ?>
                    </div>

                    <div class="template-content">

                        <h2>
                            <?= htmlspecialchars($template["name"]) ?>
                        </h2>

                        <p>
                            <?= htmlspecialchars($template["description"]) ?>
                        </p>

                        <a
                            href="file_manager.php?template=<?= urlencode($template["name"]) ?>"
                            class="template-btn"
                        >
                            Use Template
                        </a>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>

    </div>

</div>

</body>

</html>

