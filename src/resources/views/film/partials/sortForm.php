<div class="sort-container">
    <form action="<?= htmlspecialchars($_SERVER['REQUEST_URI']); ?>" method="GET" class="sort-form">
        <label for="sort">Сортировать по:</label>
        <select name="sort" id="sort">
            <option value="year" <?= ($_GET['sort'] ?? '') === 'year' ? 'selected' : ''; ?>>Год</option>
            <option value="title" <?= ($_GET['sort'] ?? '') === 'title' ? 'selected' : ''; ?>>Алфавиту</option>
        </select>
        <input type="hidden" name="query" value="<?= htmlspecialchars($_GET['query'] ?? ''); ?>">
        <button type="submit" class="sort-btn">Применить</button>
    </form>
</div>
