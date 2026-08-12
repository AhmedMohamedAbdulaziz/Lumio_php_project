<?php

require_once "config.php";
require_once "auth_check.php";
require_once "theme.php";


$userId = $_SESSION["user_id"];
$theme = getUserTheme($pdo, $userId);



/* =========================
   Handle Events
========================= */

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $action = $_POST["action"] ?? "";


    /* =========================
       Add Event
    ========================= */

    if ($action === "add") {

        $title = trim($_POST["title"] ?? "");
        $description = trim($_POST["description"] ?? "");
        $eventDate = $_POST["event_date"] ?? "";
        $eventTime = $_POST["event_time"] ?? null;
        $reminder = (int) ($_POST["reminder"] ?? 0);

        if ($title !== "" && $eventDate !== "") {

            $sql = "INSERT INTO events
                    (user_id, title, description, event_date, event_time, reminder)
                    VALUES (?, ?, ?, ?, ?, ?)";

            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                $userId,
                $title,
                $description,
                $eventDate,
                $eventTime ?: null,
                $reminder
            ]);
        }

        header("Location: calendar.php");
        exit;
    }


    /* =========================
       Edit Event
    ========================= */

    if ($action === "edit") {

        $eventId = (int) ($_POST["event_id"] ?? 0);

        $title = trim($_POST["title"] ?? "");
        $description = trim($_POST["description"] ?? "");
        $eventDate = $_POST["event_date"] ?? "";
        $eventTime = $_POST["event_time"] ?? null;
        $reminder = (int) ($_POST["reminder"] ?? 0);

        if ($eventId > 0 && $title !== "" && $eventDate !== "") {

            $sql = "UPDATE events
                    SET title = ?,
                        description = ?,
                        event_date = ?,
                        event_time = ?,
                        reminder = ?
                    WHERE id = ? AND user_id = ?";

            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                $title,
                $description,
                $eventDate,
                $eventTime ?: null,
                $reminder,
                $eventId,
                $userId
            ]);
        }

        header("Location: calendar.php");
        exit;
    }


    /* =========================
       Delete Event
    ========================= */

    if ($action === "delete") {

        $eventId = (int) ($_POST["event_id"] ?? 0);

        if ($eventId > 0) {

            $sql = "DELETE FROM events
                    WHERE id = ? AND user_id = ?";

            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                $eventId,
                $userId
            ]);
        }

        header("Location: calendar.php");
        exit;
    }
}


/* =========================
   Get Events
========================= */

$sql = "SELECT *
        FROM events
        WHERE user_id = ?
        ORDER BY event_date ASC, event_time ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute([$userId]);

$events = $stmt->fetchAll(PDO::FETCH_ASSOC);

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

    <title>Calendar - Lumio</title>

    <link rel="stylesheet" href="../css/base.css">

    <link rel="stylesheet" href="../css/calendar.css">

</head>


<body>

<div class="app-layout">

    <?php include "sidebar.php"; ?>


    <main class="main-content">

        <!-- Header -->

        <div class="calendar-header">

            <div>

                <h1>Calendar</h1>

                <p>
                    Manage your events and schedule.
                </p>

            </div>

        </div>


        <!-- Add Event -->

        <section class="calendar-form">

            <h2>Add New Event</h2>

            <form method="POST">

                <input
                    type="hidden"
                    name="action"
                    value="add"
                >


                <div class="form-group">

                    <label>Event Title</label>

                    <input
                        type="text"
                        name="title"
                        placeholder="Enter event title"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>Description</label>

                    <textarea
                        name="description"
                        placeholder="Enter event description"
                    ></textarea>

                </div>


                <div class="calendar-form-row">

                    <div class="form-group">

                        <label>Date</label>

                        <input
                            type="date"
                            name="event_date"
                            required
                        >

                    </div>


                    <div class="form-group">

                        <label>Time</label>

                        <input
                            type="time"
                            name="event_time"
                        >

                    </div>


                    <div class="form-group">

                        <label>Reminder</label>

                        <select name="reminder">

                            <option value="0">
                                No Reminder
                            </option>

                            <option value="10">
                                10 Minutes Before
                            </option>

                            <option value="30">
                                30 Minutes Before
                            </option>

                            <option value="60">
                                1 Hour Before
                            </option>

                            <option value="1440">
                                1 Day Before
                            </option>

                        </select>

                    </div>

                </div>


                <button
                    type="submit"
                    class="btn-calendar"
                >
                    Add Event
                </button>

            </form>

        </section>


        <!-- Events -->

        <section class="events-section">

            <div class="section-header">

                <h2>My Events</h2>

                <span>
                    <?= count($events) ?> events
                </span>

            </div>


            <?php if (empty($events)): ?>

                <div class="empty-calendar">

                    <div class="calendar-icon">
                        📅
                    </div>

                    <h3>No events yet</h3>

                    <p>
                        Add your first event to your calendar.
                    </p>

                </div>


            <?php else: ?>


                <div class="events-list">

                    <?php foreach ($events as $event): ?>

                        <article class="event-card">


                            <div class="event-date-box">

                                <strong>
                                    <?= date(
                                        "d",
                                        strtotime($event["event_date"])
                                    ) ?>
                                </strong>

                                <span>
                                    <?= date(
                                        "M",
                                        strtotime($event["event_date"])
                                    ) ?>
                                </span>

                            </div>


                            <div class="event-info">

                                <h3>
                                    <?= htmlspecialchars(
                                        $event["title"]
                                    ) ?>
                                </h3>


                                <?php if (!empty($event["description"])): ?>

                                    <p>
                                        <?= htmlspecialchars(
                                            $event["description"]
                                        ) ?>
                                    </p>

                                <?php endif; ?>


                                <div class="event-meta">

                                    <span>
                                        📅
                                        <?= htmlspecialchars(
                                            $event["event_date"]
                                        ) ?>
                                    </span>


                                    <?php if ($event["event_time"]): ?>

                                        <span>
                                            ⏰
                                            <?= date(
                                                "h:i A",
                                                strtotime(
                                                    $event["event_time"]
                                                )
                                            ) ?>
                                        </span>

                                    <?php endif; ?>


                                    <?php if ($event["reminder"] > 0): ?>

                                        <span>
                                            🔔
                                            Reminder:
                                            <?= $event["reminder"] ?> min
                                        </span>

                                    <?php endif; ?>

                                </div>

                            </div>


                            <div class="event-actions">


                                <!-- Edit -->

                                <details>

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
                                            name="event_id"
                                            value="<?= $event["id"] ?>"
                                        >


                                        <input
                                            type="text"
                                            name="title"
                                            value="<?= htmlspecialchars(
                                                $event["title"]
                                            ) ?>"
                                            required
                                        >


                                        <textarea
                                            name="description"
                                        ><?= htmlspecialchars(
                                            $event["description"]
                                        ) ?></textarea>


                                        <input
                                            type="date"
                                            name="event_date"
                                            value="<?= htmlspecialchars(
                                                $event["event_date"]
                                            ) ?>"
                                            required
                                        >


                                        <input
                                            type="time"
                                            name="event_time"
                                            value="<?= htmlspecialchars(
                                                $event["event_time"] ?? ""
                                            ) ?>"
                                        >


                                        <select name="reminder">

                                            <option
                                                value="0"
                                                <?= $event["reminder"] == 0
                                                    ? "selected"
                                                    : "" ?>
                                            >
                                                No Reminder
                                            </option>

                                            <option
                                                value="10"
                                                <?= $event["reminder"] == 10
                                                    ? "selected"
                                                    : "" ?>
                                            >
                                                10 Minutes
                                            </option>

                                            <option
                                                value="30"
                                                <?= $event["reminder"] == 30
                                                    ? "selected"
                                                    : "" ?>
                                            >
                                                30 Minutes
                                            </option>

                                            <option
                                                value="60"
                                                <?= $event["reminder"] == 60
                                                    ? "selected"
                                                    : "" ?>
                                            >
                                                1 Hour
                                            </option>

                                            <option
                                                value="1440"
                                                <?= $event["reminder"] == 1440
                                                    ? "selected"
                                                    : "" ?>
                                            >
                                                1 Day
                                            </option>

                                        </select>


                                        <button
                                            type="submit"
                                            class="btn-save"
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
                                        name="event_id"
                                        value="<?= $event["id"] ?>"
                                    >

                                    <button
                                        type="submit"
                                        class="btn-delete"
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