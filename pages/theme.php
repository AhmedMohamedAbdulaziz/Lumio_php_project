<?php

function getUserTheme($pdo, $userId)
{
    $sql = "SELECT theme
            FROM user_settings
            WHERE user_id = ?";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$userId]);

    $theme = $stmt->fetchColumn();

    if ($theme !== "light" && $theme !== "dark") {
        $theme = "dark";
    }

    return $theme;
}