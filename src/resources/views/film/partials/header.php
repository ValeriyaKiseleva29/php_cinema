<nav>
    <ul>
        <li class="menu-left">
            <a href="/">Главная</a>
        </li>
        <div class="logo">
            <a href="/">Movie in Che</a>
        </div>
        <li>
            <form class="search-form" action="/films" method="GET">
                <div class="search-wrapper">
                    <input type="text" name="query" id="search-query" value="<?= htmlspecialchars($query ?? '') ?>" placeholder="Поиск фильмов..." class="search-input">
                    <span class="clear-input" id="clear-search">✖</span>
                </div>
                <button type="submit" class="search-btn">Найти</button>
            </form>

        </li>

        <?php if (isset($_SESSION['user_id'])): ?>
            <li><a href="/favorites">Избранное</a></li>
            <li><a href="/profile">Мой профиль</a></li>
            <li><a href="/logout_confirmation">Выйти</a></li>
        <?php else: ?>
            <li class="menu-right"><a href="/login">Войти</a></li>
            <li class="menu-right"><a href="/auth">Регистрация</a></li>
        <?php endif; ?>
    </ul>
</nav>
<script>
    document.getElementById('clear-search').addEventListener('click', function() {
        document.getElementById('search-query').value = '';
        this.style.display = 'none';
    });
    document.getElementById('search-query').addEventListener('input', function() {
        if (this.value.length > 0) {
            document.getElementById('clear-search').style.display = 'inline';
        } else {
            document.getElementById('clear-search').style.display = 'none';
        }
    });
</script>
