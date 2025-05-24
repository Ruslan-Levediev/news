<article class="news-detail">
    <h1><?= htmlspecialchars($newsItem['title']) ?></h1>

    <?php if (!empty($newsItem['image_path'])): ?>
        <div class="news-image-container">
            <img src="<?= htmlspecialchars($baseUrl . $newsItem['image_path']) ?>" alt="Зображення новини">
        </div>
    <?php endif; ?>

    <div class="news-content">
        <p><?= nl2br(htmlspecialchars($newsItem['content'])) ?></p>
    </div>

    <?php
    
    $authorName = !empty($newsItem['display_name']) ? $newsItem['display_name'] : ($newsItem['author_name'] ?? 'Невідомий автор');

   
    if (!empty($newsItem['publish_date'])) {
        try {
            $utcDate = new DateTime($newsItem['publish_date'], new DateTimeZone('UTC'));
            $utcDate->setTimezone(new DateTimeZone('Europe/Kiev'));
            $formattedDate = $utcDate->format('Y-m-d H:i:s');
        } catch (Exception $e) {
            $formattedDate = $newsItem['publish_date'];
        }
    } else {
        $formattedDate = '';
    }
    ?>
    <small>Автор: <?= htmlspecialchars($authorName) ?></small><br>
    <small>Опубліковано: <?= htmlspecialchars($formattedDate) ?></small>

    <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $newsItem['author_id']): ?>
        <a href="/project/public/news/delete/<?= htmlspecialchars($newsItem['id']) ?>" 
           onclick="return confirm('Ви впевнені, що хочете видалити цю новину?')">
           Видалити новину
        </a>
    <?php endif; ?>

    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
        <?php if (empty($newsItem['is_main']) || !$newsItem['is_main']): ?>
            <form method="post" action="/project/public/news/set_main/<?= htmlspecialchars($newsItem['id']) ?>" style="margin-top: 10px;">
                <button type="submit" onclick="return confirm('Встановити цю новину головною?')">Встановити головною</button>
            </form>
        <?php else: ?>
            <form method="post" action="/project/public/news/unset_main/<?= htmlspecialchars($newsItem['id']) ?>" style="margin-top: 10px;">
                <button type="submit" onclick="return confirm('Зняти статус головної новини?')">Зняти статус головної</button>
            </form>
            <p><strong>Ця новина є головною</strong></p>
        <?php endif; ?>
    <?php endif; ?>

    <hr>

    <h2>Коментарі</h2>

    <?php if (isset($_SESSION['user_id'])): ?>
        <form method="POST" action="/project/public/comment/add">
            <input type="hidden" name="news_id" value="<?= htmlspecialchars($newsItem['id']) ?>">
            <textarea name="comment_text" required placeholder="Ваш коментар..." rows="4" style="width:100%;"></textarea>
            <button type="submit" style="margin-top: 10px;">Надіслати</button>
        </form>
    <?php else: ?>
        <p>Щоб залишати коментарі, будь ласка, <a href="/project/public/user/login">увійдіть</a>.</p>
    <?php endif; ?>

    <?php if (!empty($comments)): ?>
        <?php foreach ($comments as $comment): ?>
            <div class="comment" style="display:flex; align-items:center; margin-bottom:15px;">
                <?php
                $avatarPath = !empty($comment['avatar_path']) ? $baseUrl . $comment['avatar_path'] : $baseUrl . '/images/default-avatar.png';
                $userName = !empty($comment['display_name']) ? $comment['display_name'] : $comment['username'];

                if (!empty($comment['created_at'])) {
                    try {
                        $commentDate = new DateTime($comment['created_at'], new DateTimeZone('UTC'));
                        $commentDate->setTimezone(new DateTimeZone('Europe/Kiev'));
                        $formattedCommentDate = $commentDate->format('Y-m-d H:i:s');
                    } catch (Exception $e) {
                        $formattedCommentDate = $comment['created_at'];
                    }
                } else {
                    $formattedCommentDate = '';
                }
                ?>
                <img src="<?= htmlspecialchars($avatarPath) ?>" alt="Аватар" style="width:40px; height:40px; border-radius:50%; margin-right:10px;">
                <div>
                    <p style="margin:0;">
                        <strong><?= htmlspecialchars($userName) ?></strong>
                        <small><?= htmlspecialchars($formattedCommentDate) ?></small>
                    </p>
                    <p><?= nl2br(htmlspecialchars($comment['comment_text'])) ?></p>
                    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                        <a href="/project/public/comment/delete/<?= (int)$comment['id'] ?>" 
                        onclick="return confirm('Вы уверены, что хотите удалить этот комментарий?')">Удалить</a>
                    <?php endif; ?>
                </div>
            </div>
            <hr>
        <?php endforeach; ?>

    <?php else: ?>
        <p>Поки що немає коментарів. Станьте першим!</p>
    <?php endif; ?>

</article>
