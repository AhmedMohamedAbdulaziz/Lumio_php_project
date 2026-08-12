<?php

require_once "config.php";
require_once "auth_check.php";
require_once "theme.php";


$userId = $_SESSION["user_id"];
$theme = getUserTheme($pdo, $userId);



/* =========================
   Handle Projects
========================= */

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $action = $_POST["action"] ?? "";


    /* =========================
       Add Project
    ========================= */

    if ($action === "add") {

        $name = trim($_POST["name"] ?? "");
        $description = trim($_POST["description"] ?? "");
        $priority = $_POST["priority"] ?? "Medium";
        $dueDate = $_POST["due_date"] ?? null;

        if ($name !== "") {

            $sql = "INSERT INTO projects
                    (user_id, name, description, priority, due_date)
                    VALUES (?, ?, ?, ?, ?)";

            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                $userId,
                $name,
                $description,
                $priority,
                $dueDate ?: null
            ]);
        }

        header("Location: projects.php");
        exit;
    }


    /* =========================
       Edit Project
    ========================= */

    if ($action === "edit") {

        $projectId = (int) ($_POST["project_id"] ?? 0);

        $name = trim($_POST["name"] ?? "");
        $description = trim($_POST["description"] ?? "");
        $priority = $_POST["priority"] ?? "Medium";
        $status = $_POST["status"] ?? "Planning";
        $dueDate = $_POST["due_date"] ?? null;

        if ($projectId > 0 && $name !== "") {

            $sql = "UPDATE projects
                    SET name = ?,
                        description = ?,
                        priority = ?,
                        status = ?,
                        due_date = ?
                    WHERE id = ? AND user_id = ?";

            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                $name,
                $description,
                $priority,
                $status,
                $dueDate ?: null,
                $projectId,
                $userId
            ]);
        }

        header("Location: projects.php");
        exit;
    }


    /* =========================
       Change Status
    ========================= */

    if ($action === "status") {

        $projectId = (int) ($_POST["project_id"] ?? 0);

        $status = $_POST["status"] ?? "Planning";

        $allowedStatuses = [
            "Planning",
            "In Progress",
            "Completed"
        ];

        if (
            $projectId > 0 &&
            in_array($status, $allowedStatuses, true)
        ) {

            $sql = "UPDATE projects
                    SET status = ?
                    WHERE id = ? AND user_id = ?";

            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                $status,
                $projectId,
                $userId
            ]);
        }

        header("Location: projects.php");
        exit;
    }


    /* =========================
       Delete Project
    ========================= */

    if ($action === "delete") {

        $projectId = (int) ($_POST["project_id"] ?? 0);

        if ($projectId > 0) {

            $sql = "DELETE FROM projects
                    WHERE id = ? AND user_id = ?";

            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                $projectId,
                $userId
            ]);
        }

        header("Location: projects.php");
        exit;
    }
}


/* =========================
   Get Projects
========================= */

$sql = "SELECT *
        FROM projects
        WHERE user_id = ?
        ORDER BY created_at DESC";

$stmt = $pdo->prepare($sql);

$stmt->execute([$userId]);

$projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>

<html
    lang="en"
    class="<?= $theme === "light"
        ? "light-mode"
        : "dark-mode" ?>"
>
<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Projects - Lumio</title>

    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/task.css">

</head>

<body>

<div class="app-layout">

    <?php include "sidebar.php"; ?>


    <main class="main-content">


        <!-- =========================
             Header
        ========================== -->

        <div class="module-header">

            <h1>Projects</h1>

            <p>
                Organize and manage your projects.
            </p>

        </div>


        <!-- =========================
             Add Project
        ========================== -->

        <section class="task-form">

            <h2>Add New Project</h2>


            <form method="POST">

                <input
                    type="hidden"
                    name="action"
                    value="add"
                >


                <div class="form-group">

                    <label>
                        Project Name
                    </label>

                    <input
                        type="text"
                        name="name"
                        placeholder="Enter project name"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>
                        Description
                    </label>

                    <textarea
                        name="description"
                        placeholder="Enter project description"
                    ></textarea>

                </div>


                <div class="form-row">


                    <div class="form-group">

                        <label>
                            Priority
                        </label>

                        <select name="priority">

                            <option value="Low">
                                Low
                            </option>

                            <option value="Medium" selected>
                                Medium
                            </option>

                            <option value="High">
                                High
                            </option>

                        </select>

                    </div>


                    <div class="form-group">

                        <label>
                            Due Date
                        </label>

                        <input
                            type="date"
                            name="due_date"
                        >

                    </div>


                </div>


                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Add Project
                </button>

            </form>

        </section>


        <!-- =========================
             Projects List
        ========================== -->

        <section class="tasks-section">


            <div class="section-header">

                <h2>My Projects</h2>

                <span>
                    <?= count($projects) ?> projects
                </span>

            </div>


            <?php if (empty($projects)): ?>


                <div class="empty-state">

                    <h3>No projects yet</h3>

                    <p>
                        Create your first project to get started.
                    </p>

                </div>


            <?php else: ?>


                <div class="tasks-list">


                    <?php foreach ($projects as $project): ?>


                        <article class="task-card">


                            <div class="task-info">


                                <h3>
                                    <?= htmlspecialchars($project["name"]) ?>
                                </h3>


                                <?php if ($project["description"]): ?>

                                    <p>
                                        <?= htmlspecialchars($project["description"]) ?>
                                    </p>

                                <?php endif; ?>


                                <div class="task-meta">

                                    <span class="priority">

                                        <?= htmlspecialchars($project["priority"]) ?>

                                    </span>


                                    <span>

                                        <?= htmlspecialchars($project["status"]) ?>

                                    </span>


                                    <?php if ($project["due_date"]): ?>

                                        <span>

                                            Due:
                                            <?= htmlspecialchars($project["due_date"]) ?>

                                        </span>

                                    <?php endif; ?>


                                </div>


                            </div>


                            <div class="task-actions">


                                <!-- Status -->

                                <form method="POST">

                                    <input
                                        type="hidden"
                                        name="action"
                                        value="status"
                                    >

                                    <input
                                        type="hidden"
                                        name="project_id"
                                        value="<?= $project["id"] ?>"
                                    >


                                    <select
                                        name="status"
                                        onchange="this.form.submit()"
                                    >

                                        <option
                                            value="Planning"
                                            <?= $project["status"] === "Planning" ? "selected" : "" ?>
                                        >
                                            Planning
                                        </option>


                                        <option
                                            value="In Progress"
                                            <?= $project["status"] === "In Progress" ? "selected" : "" ?>
                                        >
                                            In Progress
                                        </option>


                                        <option
                                            value="Completed"
                                            <?= $project["status"] === "Completed" ? "selected" : "" ?>
                                        >
                                            Completed
                                        </option>

                                    </select>

                                </form>


                                <!-- Edit -->

                                <details class="edit-task">


                                    <summary>
                                        Edit
                                    </summary>


                                    <form method="POST">


                                        <input
                                            type="hidden"
                                            name="action"
                                            value="edit"
                                        >


                                        <input
                                            type="hidden"
                                            name="project_id"
                                            value="<?= $project["id"] ?>"
                                        >


                                        <input
                                            type="text"
                                            name="name"
                                            value="<?= htmlspecialchars($project["name"]) ?>"
                                            required
                                        >


                                        <textarea
                                            name="description"
                                        ><?= htmlspecialchars($project["description"]) ?></textarea>


                                        <select name="priority">

                                            <option
                                                value="Low"
                                                <?= $project["priority"] === "Low" ? "selected" : "" ?>
                                            >
                                                Low
                                            </option>


                                            <option
                                                value="Medium"
                                                <?= $project["priority"] === "Medium" ? "selected" : "" ?>
                                            >
                                                Medium
                                            </option>


                                            <option
                                                value="High"
                                                <?= $project["priority"] === "High" ? "selected" : "" ?>
                                            >
                                                High
                                            </option>

                                        </select>


                                        <select name="status">

                                            <option
                                                value="Planning"
                                                <?= $project["status"] === "Planning" ? "selected" : "" ?>
                                            >
                                                Planning
                                            </option>


                                            <option
                                                value="In Progress"
                                                <?= $project["status"] === "In Progress" ? "selected" : "" ?>
                                            >
                                                In Progress
                                            </option>


                                            <option
                                                value="Completed"
                                                <?= $project["status"] === "Completed" ? "selected" : "" ?>
                                            >
                                                Completed
                                            </option>

                                        </select>


                                        <input
                                            type="date"
                                            name="due_date"
                                            value="<?= htmlspecialchars($project["due_date"] ?? "") ?>"
                                        >


                                        <button
                                            type="submit"
                                            class="btn btn-save"
                                        >
                                            Save Changes
                                        </button>


                                    </form>

                                </details>


                                <!-- Delete -->

                                <form method="POST">

                                    <input
                                        type="hidden"
                                        name="action"
                                        value="delete"
                                    >


                                    <input
                                        type="hidden"
                                        name="project_id"
                                        value="<?= $project["id"] ?>"
                                    >


                                    <button
                                        type="submit"
                                        class="btn btn-delete"
                                    >
                                        Delete
                                    </button>

                                </form>


                            </div>


                        </article>


                    <?php endforeach; ?>


                </div>


            <?php endif; ?>


        </section>


    </main>

</div>

</body>

</html>