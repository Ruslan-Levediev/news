<!DOCTYPE html>
<html lang="ua">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($title ?? 'SMGNews') ?></title>
    <link rel="stylesheet" href="/project/public/css/style.css">
    <script src="/project/public/js/script.js" defer></script>
</head>
<body>
<header>
    <h1>SMGNews</h1>
    <nav>
        <a href="/project/public/">Головна</a> |
        <a href="/project/public/news">Новини</a> |
        <a href="/project/public/user/profile">Профіль</a> |
        <a href="/project/public/news/search">Пошук</a> |
        <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
            <a href="/project/public/news/add">Добавить новость</a> |
        <?php endif; ?>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="/project/public/user/logout">Вийти</a>
        <?php else: ?>
            <a href="/project/public/user/login">Вхід</a>
        <?php endif; ?>
    </nav>
</header>


<hr>
<main>
    <?= $content ?? '' ?>
</main>
<hr>
<footer>© 2025 SMGNews. Всі права захищені.</footer>
</body>
</html>
