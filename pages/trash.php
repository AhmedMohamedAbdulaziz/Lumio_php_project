
<?php

require_once "config.php";
require_once "auth_check.php";
require_once "theme.php";

$userId = $_SESSION["user_id"];
$theme = getUserTheme($pdo, $userId);


/*
|--------------------------------------------------------------------------
| Restore File
|--------------------------------------------------------------------------
*/

if (
    $_SERVER["REQUEST_METHOD"] === "POST"
    && isset($_POST["restore"])
) {

    $fileId = (int) $_POST["file_id"];

    $stmt = $pdo->prepare("
        UPDATE files
        SET deleted_at = NULL
        WHERE id = ?
        AND user_id = ?
    ");

    $stmt->execute([
        $fileId,
        $userId
    ]);

    header("Location: trash.php");
    exit;
}


/*
|--------------------------------------------------------------------------
| Permanent Delete
|--------------------------------------------------------------------------
*/

if (
    $_SERVER["REQUEST_METHOD"] === "POST"
    && isset($_POST["permanent_delete"])
) {

    $fileId = (int) $_POST["file_id"];


    $stmt = $pdo->prepare("
        SELECT file_path
        FROM files
        WHERE id = ?
        AND user_id = ?
        AND deleted_at IS NOT NULL
    ");

    $stmt->execute([
        $fileId,
        $userId
    ]);

    $file = $stmt->fetch();


    if ($file) {

        $filePath = dirname(__DIR__) . "/" . $file["file_path"];

        if (file_exists($filePath)) {
            unlink($filePath);
        }


        $stmt = $pdo->prepare("
            DELETE FROM files
            WHERE id = ?
            AND user_id = ?
        ");

        $stmt->execute([
            $fileId,
            $userId
        ]);
    }


    header("Location: trash.php");
    exit;
}


/*
|--------------------------------------------------------------------------
| Get Deleted Files
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare("
    SELECT *
    FROM files
    WHERE user_id = ?
    AND deleted_at IS NOT NULL
    ORDER BY deleted_at DESC
");

$stmt->execute([
    $userId
]);

$deletedFiles = $stmt->fetchAll();

?>

<!DOCTYPE html>

<html lang="en" dir="ltr" class="<?= $theme === 'light' ? 'light-mode' : 'dark-mode' ?>">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Trash - Lumio Module</title>

    <link
        rel="stylesheet"
        href="../css/base.css"
    >

    <link
        rel="stylesheet"
        href="../css/trash.css"
    >

</head>


<body>


<div class="app-layout">


    <?php include "sidebar.php"; ?>


    <div class="main-content">


        <div class="dashboard-header">

            <h1>
                Trash & Archive 🗑
            </h1>

            <p>
                Recover deleted files or permanently remove them.
            </p>

        </div>


        <div class="files-container">


            <h2>
                Deleted Files
            </h2>


            <?php if (count($deletedFiles) > 0): ?>


                <?php foreach ($deletedFiles as $file): ?>


                    <div class="file-item">


                        <div class="file-info">


                            <span class="file-icon">
                                🗑️
                            </span>


                            <div>


                                <div class="file-name">

                                    <?= htmlspecialchars(
                                        $file["original_name"]
                                    ) ?>

                                </div>


                                <div class="file-size">

                                    <?= number_format(
                                        $file["file_size"] / 1024,
                                        2
                                    ) ?>

                                    KB

                                </div>


                                <div class="file-size">

                                    Deleted:
                                    <?= htmlspecialchars(
                                        $file["deleted_at"]
                                    ) ?>

                                </div>


                            </div>


                        </div>


                        <div class="file-actions">


                            <!-- Restore -->

                            <form
                                method="POST"
                                onsubmit="
                                    return confirm(
                                        'Restore this file?'
                                    );
                                "
                            >

                                <input
                                    type="hidden"
                                    name="file_id"
                                    value="<?= $file["id"] ?>"
                                >

                                <button
                                    type="submit"
                                    name="restore"
                                    class="download-btn"
                                >
                                    Restore
                                </button>

                            </form>


                            <!-- Permanent Delete -->

                            <form
                                method="POST"
                                onsubmit="
                                    return confirm(
                                        'Permanently delete this file? This cannot be undone.'
                                    );
                                "
                            >

                                <input
                                    type="hidden"
                                    name="file_id"
                                    value="<?= $file["id"] ?>"
                                >

                                <button
                                    type="submit"
                                    name="permanent_delete"
                                    class="delete-btn"
                                >
                                    Delete Permanently
                                </button>

                            </form>


                        </div>


                    </div>


                <?php endforeach; ?>


            <?php else: ?>


                <div class="no-files">

                    🗑️

                    <p>
                        Trash is empty.
                    </p>

                </div>


            <?php endif; ?>


        </div>


    </div>


</div>


</body>

</html>

