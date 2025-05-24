<h1>Головні новини</h1>

<?php if (!empty($mainNewsList)): ?>
    <?php foreach ($mainNewsList as $mainNews): ?>
        <?php
      
        $authorName = !empty($mainNews['display_name']) ? $mainNews['display_name'] : ($mainNews['author_name'] ?? 'Невідомий автор');

        
        try {
            $utcDate = new DateTime($mainNews['publish_date'], new DateTimeZone('UTC'));
            $utcDate->setTimezone(new DateTimeZone('Europe/Kiev'));
            $formattedDate = $utcDate->format('Y-m-d H:i:s');
        } catch (Exception $e) {
            $formattedDate = $mainNews['publish_date'];
        }
        ?>
        <article class="main-news">
            <h2>
                <a href="<?= htmlspecialchars($baseUrl . '/news/' . $mainNews['id']) ?>">
                    <?= htmlspecialchars($mainNews['title']) ?>
                </a>
            </h2>
            <?php if (!empty($mainNews['image_path'])): ?>
                <div class="news-image-container">
                    <img src="<?= htmlspecialchars($baseUrl . $mainNews['image_path']) ?>" alt="<?= htmlspecialchars($mainNews['title']) ?>">
                </div>
            <?php endif; ?>
            <div class="news-content">
                <p class="news-short">
                    <?= mb_substr(nl2br(htmlspecialchars($mainNews['content'])), 0, 200) ?>...
                </p>
                <p class="news-full" style="display:none;">
                    <?= nl2br(htmlspecialchars($mainNews['content'])) ?>
                </p>
                <button class="read-more">Читати далі</button>
            </div>
            <small>Опубліковано: <?= htmlspecialchars($formattedDate) ?></small><br>
            <small>Автор: <?= htmlspecialchars($authorName) ?></small>
        </article>
        <hr>
    <?php endforeach; ?>
<?php else: ?>
    <p>Головних новин немає.</p>
    <hr>
<?php endif; ?>

<h2>Останні новини</h2>

<?php if (!empty($latestNews)): ?>
    <?php foreach ($latestNews as $news): ?>
        <?php
        $authorName = !empty($news['display_name']) ? $news['display_name'] : ($news['author_name'] ?? 'Невідомий автор');
        try {
            $utcDate = new DateTime($news['publish_date'], new DateTimeZone('UTC'));
            $utcDate->setTimezone(new DateTimeZone('Europe/Kiev'));
            $formattedDate = $utcDate->format('Y-m-d H:i:s');
        } catch (Exception $e) {
            $formattedDate = $news['publish_date'];
        }
        ?>
        <article class="news-item">
            <h3>
                <a href="<?= htmlspecialchars($baseUrl . '/news/' . $news['id']) ?>">
                    <?= htmlspecialchars($news['title']) ?>
                </a>
            </h3>
            <?php if (!empty($news['image_path'])): ?>
                <div class="news-image-container">
                    <img src="<?= htmlspecialchars($baseUrl . $news['image_path']) ?>" alt="<?= htmlspecialchars($news['title']) ?>">
                </div>
            <?php endif; ?>
            <div class="news-content">
                <p class="news-short">
                    <?= mb_substr(nl2br(htmlspecialchars($news['content'])), 0, 200) ?>...
                </p>
                <p class="news-full" style="display:none;">
                    <?= nl2br(htmlspecialchars($news['content'])) ?>
                </p>
                <button class="read-more">Читати далі</button>
            </div>
            <small>Опубліковано: <?= htmlspecialchars($formattedDate) ?></small><br>
            <small>Автор: <?= htmlspecialchars($authorName) ?></small>
        </article>
        <hr>
    <?php endforeach; ?>
<?php else: ?>
    <p>Останніх новин немає.</p>
<?php endif; ?>

<a href="<?= htmlspecialchars($baseUrl) ?>/news" class="all-news-link">Всі новини</a>
