<nav>
    <ul>
        <li class="menu-left">
            <a href="/">Главная</a>
        </li>
        <li class="logo">
            <a href="/">Movie in Che</a>
        </li>

        <!-- Строка поиска всегда доступна -->
        <li>
            <form class="search-form" action="/search" method="GET">
                <input type="text" name="query" placeholder="Поиск фильмов..." class="search-input">
                <button type="submit" class="search-btn">Найти</button>
            </form>
        </li>

        <?php if (isset($_SESSION['user_id'])): ?>
            <li><a href="/favorites">Избранное</a></li>
            <li><a href="/logout_confirmation">Выйти</a></li>
        <?php else: ?>
            <li class="menu-right"><a href="/login">Войти</a></li>
            <li class="menu-right"><a href="/auth">Регистрация</a></li>
        <?php endif; ?>
    </ul>
</nav>
