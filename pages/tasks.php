<?php

require_once "config.php";
require_once "auth_check.php";
require_once "theme.php";


$userId = $_SESSION["user_id"];
$theme = getUserTheme($pdo, $userId);


/* =========================
   Handle Tasks
========================= */

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $action = $_POST["action"] ?? "";


    /* =========================
       Add Task
    ========================= */

    if ($action === "add") {

        $title = trim($_POST["title"] ?? "");
        $description = trim($_POST["description"] ?? "");
        $priority = $_POST["priority"] ?? "Medium";
        $dueDate = $_POST["due_date"] ?? null;

        if ($title !== "") {

            $sql = "INSERT INTO tasks
                    (user_id, title, description, priority, due_date)
                    VALUES (?, ?, ?, ?, ?)";

            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                $userId,
                $title,
                $description,
                $priority,
                $dueDate ?: null
            ]);
            $sql = "INSERT INTO notifications
            (user_id, title, message, type)
            VALUES (?, ?, ?, ?)";

            $stmt = $pdo->prepare($sql);
    
            $stmt->execute([
                $userId,
                "New Task Created",
                "Your task \"" . $title . "\" was created successfully.",
                "success"
            ]);
            
        }

        header("Location: tasks.php");
        exit;
    }


    /* =========================
       Edit Task
    ========================= */

    if ($action === "edit") {

        $taskId = (int) ($_POST["task_id"] ?? 0);

        $title = trim($_POST["title"] ?? "");
        $description = trim($_POST["description"] ?? "");
        $priority = $_POST["priority"] ?? "Medium";
        $dueDate = $_POST["due_date"] ?? null;

        if ($taskId > 0 && $title !== "") {

            $sql = "UPDATE tasks
                    SET title = ?,
                        description = ?,
                        priority = ?,
                        due_date = ?
                    WHERE id = ? AND user_id = ?";

            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                $title,
                $description,
                $priority,
                $dueDate ?: null,
                $taskId,
                $userId
            ]);
        }

        header("Location: tasks.php");
        exit;
    }



    if ($action === "status") {

        $taskId = (int) ($_POST["task_id"] ?? 0);
        $status = $_POST["status"] ?? "Pending";

        $allowedStatuses = [
            "Pending",
            "In Progress",
            "Completed"
        ];

        if (
            $taskId > 0 &&
            in_array($status, $allowedStatuses, true)
        ) {

            $sql = "UPDATE tasks
                    SET status = ?
                    WHERE id = ? AND user_id = ?";

            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                $status,
                $taskId,
                $userId
            ]);
        }

        header("Location: tasks.php");
        exit;
    }


    /* =========================
       Delete Task
    ========================= */

    if ($action === "delete") {

        $taskId = (int) ($_POST["task_id"] ?? 0);

        if ($taskId > 0) {

            $sql = "DELETE FROM tasks
                    WHERE id = ? AND user_id = ?";

            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                $taskId,
                $userId
            ]);
        }

        header("Location: tasks.php");
        exit;
    }
}


$sql = "SELECT *
        FROM tasks
        WHERE user_id = ?
        ORDER BY created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute([$userId]);

$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

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

    <title>Tasks - Lumio</title>

    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/task.css">
  

</head>

<body>

<div class="app-layout">

    <?php include "sidebar.php"; ?>


    <main class="main-content">

        <div class="module-header">

            <div>

                <h1>Tasks</h1>

                <p>
                    Manage your tasks and stay organized.
                </p>

            </div>

        </div>


        <!-- Add Task -->

        <section class="task-form">

            <h2>Add New Task</h2>

            <form method="POST">

                <input
                    type="hidden"
                    name="action"
                    value="add"
                >

                <div class="form-group">

                    <label>
                        Task Title
                    </label>

                    <input
                        type="text"
                        name="title"
                        placeholder="Enter task title"
                        
                    >

                </div>


                <div class="form-group">

                    <label>
                        Description
                    </label>

                    <textarea
                        name="description"
                        placeholder="Enter task description"
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


                <button class="btn btn-primary" type="submit">
                    Add Task
                </button>

            </form>

        </section>


        <section class="tasks-section">

            <div class="section-header">

                <h2>My Tasks</h2>

                <span>
                    <?= count($tasks) ?> tasks
                </span>

            </div>


            <?php if (empty($tasks)): ?>

                <div class="empty-state">

                    <h3>No tasks yet</h3>

                    <p>
                        Create your first task to get started.
                    </p>

                </div>

            <?php else: ?>


                <div class="tasks-list">

                    <?php foreach ($tasks as $task): ?>

                        <article class="task-card">

                            <div class="task-info">

                                <h3>
                                    <?= htmlspecialchars($task["title"]) ?>
                                </h3>


                                <?php if ($task["description"]): ?>

                                    <p>
                                        <?= htmlspecialchars($task["description"]) ?>
                                    </p>

                                <?php endif; ?>


                                <div class="task-meta">

                                    <span class="priority">
                                        <?= htmlspecialchars($task["priority"]) ?>
                                    </span>


                                    <?php if ($task["due_date"]): ?>

                                        <span>
                                            Due:
                                            <?= htmlspecialchars($task["due_date"]) ?>
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
                                        name="task_id"
                                        value="<?= $task["id"] ?>"
                                    >

                                    <select
                                        name="status"
                                        onchange="this.form.submit()"
                                    >

                                        <option
                                            value="Pending"
                                            <?= $task["status"] === "Pending" ? "selected" : "" ?>
                                        >
                                            Pending
                                        </option>

                                        <option
                                            value="In Progress"
                                            <?= $task["status"] === "In Progress" ? "selected" : "" ?>
                                        >
                                            In Progress
                                        </option>

                                        <option
                                            value="Completed"
                                            <?= $task["status"] === "Completed" ? "selected" : "" ?>
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
                                            name="task_id"
                                            value="<?= $task["id"] ?>"
                                        >


                                        <input
                                            type="text"
                                            name="title"
                                            value="<?= htmlspecialchars($task["title"]) ?>"
                                            
                                        >


                                        <textarea
                                            name="description"
                                        ><?= htmlspecialchars($task["description"]) ?></textarea>


                                        <select name="priority">

                                            <option
                                                value="Low"
                                                <?= $task["priority"] === "Low" ? "selected" : "" ?>
                                            >
                                                Low
                                            </option>

                                            <option
                                                value="Medium"
                                                <?= $task["priority"] === "Medium" ? "selected" : "" ?>
                                            >
                                                Medium
                                            </option>

                                            <option
                                                value="High"
                                                <?= $task["priority"] === "High" ? "selected" : "" ?>
                                            >
                                                High
                                            </option>

                                        </select>


                                        <input
                                            type="date"
                                            name="due_date"
                                            value="<?= htmlspecialchars($task["due_date"] ?? "") ?>"
                                        >


                                        <button
                                            class="btn btn-save"
                                            type="submit"
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
                                        name="task_id"
                                        value="<?= $task["id"] ?>"
                                    >

                                    <button
                                        class="btn btn-delete"
                                        type="submit"
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