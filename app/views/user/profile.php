<?php
$baseUrl = $baseUrl ?? '/project/public';
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Профіль</title>
    <link rel="stylesheet" href="<?= $baseUrl ?>/css/style.css">
    <script src="<?= $baseUrl ?>/js/profile.js" defer></script>
</head>
<body>

<main>
    <h1>Ласкаво просимо, <?= htmlspecialchars($userName) ?></h1>
    <a href="/project/public/user/logout" class="read-more">Вийти</a>

    <?php if ($userRole === 'admin'): ?>
        <p><a href="/project/public/news/add" class="read-more">Додати новину</a></p>
    <?php endif; ?>

    <div id="profile-view">
        <h2>Ваш профіль</h2>
        <p><strong>Відображуване ім'я:</strong> <?= htmlspecialchars($userDisplayName ?? $userName) ?></p>
        <?php
        $avatarPath = !empty($userAvatarPath) ? $baseUrl . $userAvatarPath : $baseUrl . '/images/default-avatar.png';
        ?>
        <img src="<?= htmlspecialchars($avatarPath) ?>" alt="Аватар" style="width:100px; height:100px; border-radius:50%; object-fit: cover; margin-bottom: 10px; display:block;">
        <button onclick="showEditProfile()" class="read-more">Редагувати профіль</button>
    </div>

    <div id="profile-edit" style="display:none;">
        <h2>Редагування профілю</h2>
        <?php if (!empty($_SESSION['error'])): ?>
            <p class="error-message"><?= htmlspecialchars($_SESSION['error']) ?></p>
            <?php unset($_SESSION['error']); ?>
        <?php elseif (!empty($_SESSION['success'])): ?>
            <p class="success-message"><?= htmlspecialchars($_SESSION['success']) ?></p>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
        <form method="POST" action="/project/public/user/profile/update" enctype="multipart/form-data" class="form-container">
            <label>Відображуване ім'я:<br>
                <input type="text" name="display_name" value="<?= htmlspecialchars($userDisplayName ?? '') ?>" required>
            </label><br><br>
            <label>Завантажити аватар:<br>
                <input type="file" name="avatar" accept="image/*">
            </label><br><br>
            <img src="<?= htmlspecialchars($avatarPath) ?>" alt="Аватар" style="width:100px; height:100px; border-radius:50%; object-fit: cover; margin-bottom: 10px; display:block;">
            <button type="submit" class="read-more">Зберегти</button>
            <button type="button" class="read-more" onclick="hideEditProfile()">Скасувати</button>
        </form>
    </div>

    <h2>Ваші повідомлення</h2>

    <?php if (!empty($notifications)): ?>
        <ul class="notifications-list">
            <?php foreach ($notifications as $notification): ?>
                <li style="margin-bottom: 10px; opacity: <?= $notification['is_read'] ? '0.5' : '1' ?>; font-weight: <?= $notification['is_read'] ? 'normal' : 'bold' ?>;">
                    <?= htmlspecialchars($notification['message']) ?><br>
                    <small><?= htmlspecialchars($notification['created_at']) ?></small><br>
                    <a href="/project/public/user/profile/mark_notification_read?id=<?= (int)$notification['id'] ?>" 
                       onclick="return confirm('Відмітити як прочитане? / Отметить как прочитанное?')">[Відмітити як прочитане]</a> |
                    <a href="/project/public/user/profile/delete_notification?id=<?= (int)$notification['id'] ?>" 
                       onclick="return confirm('Ви впевнені? / Вы уверены?')">[Видалити]</a>
                </li>
            <?php endforeach; ?>
        </ul>
        <p>
            <a href="/project/public/user/profile/clear_notifications" 
               onclick="return confirm('Ви впевнені, що хочете відмітити всі повідомлення як прочитані? / Вы уверены, что хотите отметить все уведомления как прочитанные?')">
               Відмітити всі як прочитані 
            </a>
        </p>
    <?php else: ?>
        <p>У вас немає повідомлень.</p>
    <?php endif; ?>
</main>

</body>
</html>
