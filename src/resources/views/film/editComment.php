<form action="/comments/update" method="POST">
    <input type="hidden" name="comment_id" value="<?= $comment['id']; ?>">
    <textarea name="comment" rows="4" cols="50"><?= htmlspecialchars($comment['comment']); ?></textarea>
    <button type="submit">Сохранить</button>
</form>

