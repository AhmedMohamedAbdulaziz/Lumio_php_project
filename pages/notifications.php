<?php

require_once "config.php";
require_once "auth_check.php";
require_once "theme.php";


$userId = $_SESSION["user_id"];
$theme = getUserTheme($pdo, $userId);



/* =========================================
   Handle Notification Actions
========================================= */

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $action = $_POST["action"] ?? "";


    /* =====================================
       Mark One Notification As Read
    ===================================== */

    if ($action === "read") {

        $notificationId =
            (int) ($_POST["notification_id"] ?? 0);


        if ($notificationId > 0) {

            $sql = "UPDATE notifications
                    SET is_read = 1
                    WHERE id = ?
                    AND user_id = ?";

            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                $notificationId,
                $userId
            ]);
        }

        header("Location: notifications.php");
        exit;
    }


    /* =====================================
       Mark All Notifications As Read
    ===================================== */

    if ($action === "read_all") {

        $sql = "UPDATE notifications
                SET is_read = 1
                WHERE user_id = ?";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            $userId
        ]);

        header("Location: notifications.php");
        exit;
    }


    /* =====================================
       Delete Notification
    ===================================== */

    if ($action === "delete") {

        $notificationId =
            (int) ($_POST["notification_id"] ?? 0);


        if ($notificationId > 0) {

            $sql = "DELETE FROM notifications
                    WHERE id = ?
                    AND user_id = ?";

            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                $notificationId,
                $userId
            ]);
        }

        header("Location: notifications.php");
        exit;
    }
}


/* =========================================
   Get Notifications
========================================= */

$sql = "SELECT *
        FROM notifications
        WHERE user_id = ?
        ORDER BY created_at DESC";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    $userId
]);

$notifications =
    $stmt->fetchAll(PDO::FETCH_ASSOC);


/* =========================================
   Count Unread Notifications
========================================= */

$sql = "SELECT COUNT(*)
        FROM notifications
        WHERE user_id = ?
        AND is_read = 0";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    $userId
]);

$unreadCount =
    $stmt->fetchColumn();

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

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Notifications - Lumio</title>


    <link
        rel="stylesheet"
        href="../css/base.css"
    >

    <link
        rel="stylesheet"
        href="../css/task.css"
    >

</head>


<body>


<div class="app-layout">


    <?php include "sidebar.php"; ?>


    <main class="main-content">


        <!-- =================================
             Header
        ================================== -->

        <div class="module-header">

            <div>

                <h1>Notifications</h1>

                <p>
                    Stay updated with your workspace activity.
                </p>

            </div>


            <?php if ($unreadCount > 0): ?>

                <form method="POST">

                    <input
                        type="hidden"
                        name="action"
                        value="read_all"
                    >

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        Mark All as Read
                    </button>

                </form>

            <?php endif; ?>

        </div>


        <!-- =================================
             Unread Count
        ================================== -->

        <div class="notification-counter">

            <strong>
                <?= $unreadCount ?>
            </strong>

            unread notifications

        </div>


        <!-- =================================
             Notifications
        ================================== -->

        <section class="notifications-section">


            <?php if (empty($notifications)): ?>


                <div class="empty-state">

                    <h3>
                        No notifications
                    </h3>

                    <p>
                        You're all caught up.
                    </p>

                </div>


            <?php else: ?>


                <div class="notifications-list">


                    <?php foreach ($notifications as $notification): ?>


                        <article
                            class="notification-card
                            <?= $notification["is_read"]
                                ? "read"
                                : "unread" ?>"
                        >


                            <div class="notification-content">


                                <div class="notification-header">


                                    <span
                                        class="notification-type
                                        type-<?= htmlspecialchars(
                                            $notification["type"]
                                        ) ?>"
                                    >

                                        <?= strtoupper(
                                            htmlspecialchars(
                                                $notification["type"]
                                            )
                                        ) ?>

                                    </span>


                                    <?php if (!$notification["is_read"]): ?>

                                        <span class="unread-dot"></span>

                                    <?php endif; ?>


                                </div>


                                <h3>

                                    <?= htmlspecialchars(
                                        $notification["title"]
                                    ) ?>

                                </h3>


                                <p>

                                    <?= htmlspecialchars(
                                        $notification["message"]
                                    ) ?>

                                </p>


                                <small>

                                    <?= htmlspecialchars(
                                        $notification["created_at"]
                                    ) ?>

                                </small>


                            </div>


                            <div class="notification-actions">


                                <?php if (!$notification["is_read"]): ?>


                                    <form method="POST">

                                        <input
                                            type="hidden"
                                            name="action"
                                            value="read"
                                        >

                                        <input
                                            type="hidden"
                                            name="notification_id"
                                            value="<?= $notification["id"] ?>"
                                        >


                                        <button
                                            type="submit"
                                            class="btn btn-save"
                                        >
                                            Mark as Read
                                        </button>

                                    </form>


                                <?php endif; ?>


                                <form method="POST">

                                    <input
                                        type="hidden"
                                        name="action"
                                        value="delete"
                                    >

                                    <input
                                        type="hidden"
                                        name="notification_id"
                                        value="<?= $notification["id"] ?>"
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