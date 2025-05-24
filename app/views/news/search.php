<h1>Пошук новин</h1>

<form method="get" action="/project/public/news/search">
    <input type="text" name="q" value="<?= htmlspecialchars($searchTerm) ?>" placeholder="Пошук новин..." />
    <input type="submit" value="Шукати" />
</form>

<?php if ($searchTerm !== ''): ?>
    <?php if (!empty($newsList)): ?>
        <h2>Результати пошуку за: «<?= htmlspecialchars($searchTerm) ?>»</h2>
        <ul>
            <?php foreach ($newsList as $news): ?>
                <li>
                    <a href="/project/public/news/<?= htmlspecialchars($news['id']) ?>">
                        <?= htmlspecialchars($news['title']) ?>
                    </a><br>
                    <small>
                        Автор: <?= htmlspecialchars($news['author_name'] ?? 'Невідомий автор') ?> | Опубліковано: <?= htmlspecialchars($news['publish_date']) ?>
                    </small>
                    <p><?= htmlspecialchars(mb_substr($news['content'], 0, 150)) ?>...</p>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Нічого не знайдено за запитом «<?= htmlspecialchars($searchTerm) ?>».</p>
    <?php endif; ?>
<?php endif; ?>
