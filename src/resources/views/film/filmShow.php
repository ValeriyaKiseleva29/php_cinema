<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($film['title']); ?></title>
    <link rel="stylesheet" href="/styles.css">
</head>
<body>
<?php include 'partials/header.php'; ?>

<div class="movie-detail">
    <div class="movie-info">
        <h1><?= htmlspecialchars($film['title']); ?></h1>
        <p><strong>Год:</strong> <?= htmlspecialchars($film['year']); ?></p>

        <?php if (!empty($film['iframe_src'])): ?>
            <iframe src="<?= htmlspecialchars($film['iframe_src']); ?>" allowfullscreen></iframe>
        <?php else: ?>
            <p>Видео недоступно</p>
            <input type="hidden" name="film" value="<?= htmlspecialchars($film['id']); ?>">
        <?php endif; ?>

        <!-- Если фильм в избранном, показываем кнопку "Удалить из избранного" -->
        <?php if (isset($_SESSION['user_id'])): ?>
            <?php if ($is_favorite): ?>
                <form action="/favorites/remove" method="POST" class="favorite-form">
                    <input type="hidden" name="film_id" value="<?= htmlspecialchars($film['id']); ?>">
                    <button type="submit" class="favorite-btn">Удалить из избранного</button>
                </form>
            <?php else: ?>
                <form action="/favorites/add" method="POST" class="favorite-form">
                    <input type="hidden" name="film_id" value="<?= htmlspecialchars($film['id']); ?>">
                    <button type="submit" class="favorite-btn">Добавить в избранное</button>
                </form>
            <?php endif; ?>
        <?php endif; ?>

        <!-- Кнопка "Оставить комментарий" для неавторизованных пользователей -->
        <?php if (!isset($_SESSION['user_id'])): ?>
            <a href="/auth" id="leave-comment" class="favorite-btn">Оставить комментарий</a>
        <?php else: ?>
            <!-- Форма для добавления комментария для авторизованных пользователей -->
            <form action="/comments/add" method="POST" class="comment-form">
                <input type="hidden" name="film_id" value="<?= htmlspecialchars($film['id']); ?>">
                <input type="hidden" name="user_id" value="<?= htmlspecialchars($_SESSION['user_id']); ?>">

                <div>
                    <label for="comment">Ваш комментарий:</label>
                    <textarea id="comment" name="comment" rows="4" cols="50" required></textarea>
                </div>

                <button type="submit" class="favorite-btn">Отправить комментарий</button>
            </form>
        <?php endif; ?>

        <!-- Секция для отображения комментариев -->
        <div class="comments-section">
            <h2>Комментарии</h2>

            <?php if (!empty($comments)): ?>
                <?php foreach ($comments as $comment): ?>
                    <div class="comment-block" id="comment-block-<?= $comment['id']; ?>">
                        <div class="comment-header">
                            <img src="/avatars/<?= htmlspecialchars($comment['avatar']); ?>" alt="Avatar" class="avatar">

                            <div class="comment-meta">
                                <span class="username"><?= htmlspecialchars($comment['username']); ?></span>
                            </div>

                            <small class="comment-time">
                                <?= !empty($comment['created_at']) ? date('d M Y, H:i', strtotime($comment['created_at'])) : 'Дата неизвестна'; ?>
                            </small>

                            <?php if (isset($_SESSION['user_id']) && isset($comment['user_id']) && $comment['user_id'] == $_SESSION['user_id']): ?>
                                <span class="delete-comment" onclick="deleteComment(<?= $comment['id']; ?>)">✖</span>
                            <?php endif; ?>

                        </div>

                        <div class="comment-body">
                            <p><?= htmlspecialchars($comment['comment']); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Пока нет комментариев. Будьте первым, кто оставит комментарий!</p>
            <?php endif; ?>
        </div>

    </div>
</div>

<?php include 'partials/footer.php'; ?>

<!-- Добавляем JavaScript для удаления комментария -->
<script>
    function deleteComment(commentId) {
        if (confirm('Вы уверены, что хотите удалить этот комментарий?')) {
            // Отправляем POST-запрос на сервер для удаления комментария
            fetch('/comments/delete', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `comment_id=${commentId}`
            })
                .then(response => {
                    if (response.ok) {
                        // Удаляем комментарий из DOM после успешного ответа от сервера
                        document.getElementById('comment-block-' + commentId).remove();
                    } else {
                        alert('Произошла ошибка при удалении комментария.');
                    }
                });
        }
    }
</script>

</body>
</html>
