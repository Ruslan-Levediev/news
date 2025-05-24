<div class="form-container">
    <div id="loginForm" style="<?= !($showRegister ?? false) ? 'display:block;' : 'display:none;' ?>">
        <h2>Вхід</h2>
        <?php if (!empty($error) && empty($showRegister)): ?>
            <p class="error-message"><?= htmlspecialchars($error) ?></p>
        <?php elseif (!empty($success) && empty($showRegister)): ?>
            <p class="success-message"><?= htmlspecialchars($success) ?></p>
        <?php endif; ?>
        <form method="post" action="/project/public/user/login_post">
            <label>Логін:<br><input type="text" name="login" required></label><br><br>
            <label>Пароль:<br><input type="password" name="password" required></label><br><br>
            <button type="submit">Увійти</button>
        </form>
        <div class="form-switch">
            <a href="#" onclick="showForm('registerForm'); return false;" class="form-switch-link">Немає облікового запису? Зареєструватись</a>
        </div>
    </div>

    <div id="registerForm" style="<?= !empty($showRegister) ? 'display:block;' : 'display:none;' ?>">
        <h2>Регистрация</h2>
        <?php if (!empty($error) && !empty($showRegister)): ?>
            <p class="error-message"><?= htmlspecialchars($error) ?></p>
        <?php elseif (!empty($success) && !empty($showRegister)): ?>
            <p class="success-message"><?= htmlspecialchars($success) ?></p>
        <?php endif; ?>
        <form method="post" action="/project/public/user/register_post">
            <label>Логін:<br><input type="text" name="reg_username" required></label><br><br>
            <label>Email:<br><input type="email" name="reg_email" required></label><br><br>
            <label>Пароль:<br><input type="password" name="reg_password" required></label><br><br>
            <label>Підтвердження пароля:<br><input type="password" name="reg_password_confirm" required></label><br><br>
            <button type="submit">Зареєструватись</button>
        </form>
        <div class="form-switch">
            <a href="#" onclick="showForm('loginForm'); return false;" class="form-switch-link">Вже є обліковий запис? Увійти</a>
        </div>
    </div>
</div>

<script>
function showForm(formId) {
    document.getElementById('loginForm').style.display = (formId === 'loginForm') ? 'block' : 'none';
    document.getElementById('registerForm').style.display = (formId === 'registerForm') ? 'block' : 'none';
}
</script>
