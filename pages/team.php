<?php

require_once "config.php";
require_once "auth_check.php";
require_once "theme.php";

$userId = $_SESSION["user_id"];
$theme = getUserTheme($pdo, $userId);

/* =========================
   Handle Team Members
========================= */

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $action = $_POST["action"] ?? "";

    if ($action === "add") {

        $fullName = trim($_POST["full_name"] ?? "");
        $email = trim($_POST["email"] ?? "");
        $role = trim($_POST["role"] ?? "Member");
        $department = trim($_POST["department"] ?? "");

        if ($fullName !== "" && $email !== "") {

            $sql = "INSERT INTO team_members
                    (user_id, full_name, email, role, department)
                    VALUES (?, ?, ?, ?, ?)";

            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                $userId,
                $fullName,
                $email,
                $role ?: "Member",
                $department ?: null
            ]);
        }

        header("Location: team.php");
        exit;
    }

    if ($action === "edit") {

        $memberId = (int) ($_POST["member_id"] ?? 0);
        $fullName = trim($_POST["full_name"] ?? "");
        $email = trim($_POST["email"] ?? "");
        $role = trim($_POST["role"] ?? "Member");
        $department = trim($_POST["department"] ?? "");

        if ($memberId > 0 && $fullName !== "" && $email !== "") {

            $sql = "UPDATE team_members
                    SET full_name = ?,
                        email = ?,
                        role = ?,
                        department = ?
                    WHERE id = ? AND user_id = ?";

            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                $fullName,
                $email,
                $role ?: "Member",
                $department ?: null,
                $memberId,
                $userId
            ]);
        }

        header("Location: team.php");
        exit;
    }

    if ($action === "delete") {

        $memberId = (int) ($_POST["member_id"] ?? 0);

        if ($memberId > 0) {

            $sql = "DELETE FROM team_members
                    WHERE id = ? AND user_id = ?";

            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                $memberId,
                $userId
            ]);
        }

        header("Location: team.php");
        exit;
    }
}

$sql = "SELECT id, full_name, email, role, department, joined_at
        FROM team_members
        WHERE user_id = ?
        ORDER BY joined_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute([$userId]);

$members = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en" dir="ltr" class="<?= $theme === 'light' ? 'light-mode' : 'dark-mode' ?>">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Team - Lumio Module</title>


    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/team.css">

</head>

<body>

<div class="app-layout">

    <?php include "sidebar.php"; ?>

    <div class="main-content">

        <div class="dashboard-header">

            <h1>Team Members 👥</h1>

            <p>
                Add and manage members of your workspace.
            </p>

        </div>

        <section class="team-form">

            <h2>Add Team Member</h2>

            <form method="POST">

                <input type="hidden" name="action" value="add">

                <div class="form-row">

                    <div class="form-group">

                        <label>Full Name</label>

                        <input
                            type="text"
                            name="full_name"
                            placeholder="Enter member name"
                            required
                        >

                    </div>

                    <div class="form-group">

                        <label>Email</label>

                        <input
                            type="email"
                            name="email"
                            placeholder="member@example.com"
                            required
                        >

                    </div>

                </div>

                <div class="form-row">

                    <div class="form-group">

                        <label>Role</label>

                        <input
                            type="text"
                            name="role"
                            placeholder="Member"
                            value="Member"
                        >

                    </div>

                    <div class="form-group">

                        <label>Department</label>

                        <input
                            type="text"
                            name="department"
                            placeholder="Optional"
                        >

                    </div>

                </div>

                <button class="btn btn-primary" type="submit">
                    Add Member
                </button>

            </form>

        </section>

        <div class="section-header">

            <h2>My Team</h2>

            <span><?= count($members) ?> members</span>

        </div>

        <?php if (empty($members)): ?>

            <div class="team-placeholder">

                <div class="module-icon">👥</div>

                <h2>No team members yet</h2>

                <p>
                    Add your first team member using the form above.
                </p>

            </div>

        <?php else: ?>

            <div class="team-grid">

                <?php foreach ($members as $member): ?>

                    <div class="team-card">

                        <div class="team-avatar">
                            <?= strtoupper(substr($member["full_name"], 0, 1)) ?>
                        </div>

                        <div class="team-info">

                            <h2>
                                <?= htmlspecialchars($member["full_name"]) ?>
                            </h2>

                            <p>
                                <?= htmlspecialchars($member["email"]) ?>
                            </p>

                            <?php if ($member["department"]): ?>

                                <p class="team-department">
                                    <?= htmlspecialchars($member["department"]) ?>
                                </p>

                            <?php endif; ?>

                            <span class="team-role">
                                <?= htmlspecialchars($member["role"]) ?>
                            </span>

                        </div>

                        <div class="team-actions">

                            <details class="edit-member">

                                <summary>Edit</summary>

                                <form method="POST">

                                    <input type="hidden" name="action" value="edit">
                                    <input type="hidden" name="member_id" value="<?= $member["id"] ?>">

                                    <input
                                        type="text"
                                        name="full_name"
                                        value="<?= htmlspecialchars($member["full_name"]) ?>"
                                        required
                                    >

                                    <input
                                        type="email"
                                        name="email"
                                        value="<?= htmlspecialchars($member["email"]) ?>"
                                        required
                                    >

                                    <input
                                        type="text"
                                        name="role"
                                        value="<?= htmlspecialchars($member["role"]) ?>"
                                    >

                                    <input
                                        type="text"
                                        name="department"
                                        value="<?= htmlspecialchars($member["department"] ?? "") ?>"
                                        placeholder="Department"
                                    >

                                    <button class="btn btn-save" type="submit">
                                        Save
                                    </button>

                                </form>

                            </details>

                            <form method="POST" onsubmit="return confirm('Remove this team member?');">

                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="member_id" value="<?= $member["id"] ?>">

                                <button class="btn" type="submit">
                                    Remove
                                </button>

                            </form>

                        </div>

                    </div>

                <?php endforeach; ?>

            </div>

        <?php endif; ?>

    </div>

</div>

</body>

</html>
