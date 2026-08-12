<?php

require_once "config.php";
require_once "auth_check.php";

$userId = $_SESSION["user_id"];

$template = $_GET["template"] ?? "";

$templateContent = "";


/*
| Default Templates
*/

if ($template === "Meeting Notes") {

    $templateContent = "
Meeting Notes

Date:

Attendees:

Discussion
Write your meeting discussion here...

Action Items
- Action item 1
- Action item 2
";

} elseif ($template === "Project Plan") {

    $templateContent = "
Project Plan

Project Name:

Goal:

Tasks
- Task 1
- Task 2
- Task 3

Deadline
Set project deadline here...
";

} elseif ($template === "Daily Notes") {

    $templateContent = "
Daily Notes

Date:

Today's Tasks
- Task 1
- Task 2

Notes
Write your notes here...
";

} elseif ($template === "Documentation") {

    $templateContent = "
Documentation

Title:

Introduction
Write the introduction here...

Details
Add your documentation details here...

Conclusion
Write the conclusion here...
";

}


/*
|--------------------------------------------------------------------------
| Save Template
|--------------------------------------------------------------------------
*/

if (
    $_SERVER["REQUEST_METHOD"] === "POST"
    && isset($_POST["save_template"])
) {

    $templateName = $_POST["template_name"] ?? "";
    $content = $_POST["template_content"] ?? "";

    if ($templateName !== "" && $content !== "") {

        $stmt = $pdo->prepare("
            INSERT INTO templates
            (
                user_id,
                template_name,
                content
            )
            VALUES (?, ?, ?)
        ");

        $stmt->execute([
            $userId,
            $templateName,
            $content
        ]);
    }

    header(
        "Location: file_manager.php?template="
        . urlencode($templateName)
        . "&saved=1"
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| Get User Files
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare("
    SELECT *
    FROM files
    WHERE user_id = ?
    AND deleted_at IS NULL
    ORDER BY created_at DESC
");

$stmt->execute([
    $userId
]);

$files = $stmt->fetchAll();

?>

<!DOCTYPE html>

<html lang="en" dir="ltr">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>File Manager - Lumio Module</title>

    <link
        rel="stylesheet"
        href="../css/base.css"
    >

    <link
        rel="stylesheet"
        href="../css/modules.css"
    >

</head>


<body>


<div class="app-layout">


    <?php include "sidebar.php"; ?>


    <div class="main-content">


        <!-- Header -->

        <div class="dashboard-header">

            <h1>
                File Storage & Attachments 📂
            </h1>

            <p>
                Upload, organize, and share assets and document attachments.
            </p>

        </div>


        <!-- Template -->

        <?php if ($template !== ""): ?>

            <div class="template-preview">

                <div class="template-preview-header">

                    <h2>
                        <?= htmlspecialchars($template) ?>
                    </h2>

                    <a
                        href="file_manager.php"
                        class="template-close"
                    >
                        ✕
                    </a>

                </div>


                <textarea
                    class="template-editor"
                    id="templateEditor"
                ><?= htmlspecialchars($templateContent) ?></textarea>


                <form
                    method="POST"
                    class="template-actions"
                >

                    <input
                        type="hidden"
                        name="template_name"
                        value="<?= htmlspecialchars($template) ?>"
                    >


                    <input
                        type="hidden"
                        name="template_content"
                        id="templateContent"
                    >


                    <button
                        type="submit"
                        name="save_template"
                        class="template-save-btn"
                        onclick="
                            document.getElementById('templateContent').value =
                            document.getElementById('templateEditor').value;
                        "
                    >
                        Save Template
                    </button>

                </form>

            </div>

        <?php endif; ?>


        <!-- Saved Message -->

        <?php if (isset($_GET["saved"])): ?>

            <div class="template-success">

                Template saved successfully! ✅

            </div>

        <?php endif; ?>


        <!-- Upload File -->

        <div class="file-upload-box">

            <h2>
                Upload New File
            </h2>


            <form
                class="file-upload-form"
                action="upload_file.php"
                method="POST"
                enctype="multipart/form-data"
            >

                <input
                    type="file"
                    name="file"
                    required
                >


                <button
                    type="submit"
                    class="upload-btn"
                >
                    Upload
                </button>

            </form>

        </div>


        <!-- Files List -->

        <div class="files-container">

            <h2>
                My Files
            </h2>


            <?php if (count($files) > 0): ?>

                <?php foreach ($files as $file): ?>

                    <div class="file-item">


                        <div class="file-info">

                            <span class="file-icon">
                                📄
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

                            </div>

                        </div>


                        <div class="file-actions">


                            <a
                                href="download_file.php?id=<?= $file["id"] ?>"
                                class="download-btn"
                            >
                                Download
                            </a>


                            <form
                                action="delete_file.php"
                                method="POST"
                                onsubmit="
                                    return confirm(
                                        'Are you sure you want to delete this file?'
                                    );
                                "
                            >

                                <input
                                    type="hidden"
                                    name="id"
                                    value="<?= $file["id"] ?>"
                                >


                                <button
                                    type="submit"
                                    class="delete-btn"
                                >
                                    Delete
                                </button>

                            </form>


                        </div>

                    </div>

                <?php endforeach; ?>


            <?php else: ?>


                <div class="no-files">

                    📂

                    <p>
                        No files uploaded yet.
                    </p>

                </div>


            <?php endif; ?>


        </div>


    </div>


</div>


</body>

</html>

