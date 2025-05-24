<h2>Всі новини</h2>


<div style="margin-bottom: 15px;">
    Сортувати за датою: 
    <a href="?sort=asc" <?= ($currentSort === 'asc') ? 'style="font-weight:bold;"' : '' ?>>Старіші</a> |
    <a href="?sort=desc" <?= ($currentSort === 'desc') ? 'style="font-weight:bold;"' : '' ?>>Новіші</a>
</div>

<ul class="news-list">
<?php if (!empty($newsList)): ?>
    <?php foreach ($newsList as $news): ?>
        <?php
        
        $authorName = !empty($news['display_name']) ? $news['display_name'] : ($news['author_name'] ?? 'Невідомий автор');

        // Конвертация даты из UTC в Europe/Kiev
        if (!empty($news['publish_date'])) {
            try {
                $utcDate = new DateTime($news['publish_date'], new DateTimeZone('UTC'));
                $utcDate->setTimezone(new DateTimeZone('Europe/Kiev'));
                $formattedDate = $utcDate->format('Y-m-d H:i:s');
            } catch (Exception $e) {
                $formattedDate = $news['publish_date'];
            }
        } else {
            $formattedDate = '';
        }
        ?>
        <li class="news-item">
            <h3>
                <a href="<?= htmlspecialchars($baseUrl . '/news/' . $news['id']) ?>">
                    <?= htmlspecialchars($news['title']) ?>
                </a>
            </h3>
            <?php if (!empty($news['image_path'])): ?>
                <div class="news-image-container">
                    <img src="<?= htmlspecialchars($baseUrl . $news['image_path']) ?>" alt="Зображення новини">
                </div>
            <?php endif; ?>
            <div class="news-content">
                <p><?= mb_substr(nl2br(htmlspecialchars($news['content'])), 0, 200) ?>...</p>
            </div>
            <small>
                Автор: <?= htmlspecialchars($authorName) ?> | Опубліковано: <?= htmlspecialchars($formattedDate) ?>
            </small>
            <hr>
        </li>
    <?php endforeach; ?>
<?php else: ?>
    <li>Новин немає.</li>
<?php endif; ?>
</ul>
