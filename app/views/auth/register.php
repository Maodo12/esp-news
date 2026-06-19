<?php
ob_start();
?>
<div style="max-width:400px; margin:50px auto; background:white; padding:30px; border-radius:10px; box-shadow:0 2px 10px rgba(0,0,0,0.1);">
    <h2 style="text-align:center;">📝 Inscription</h2>

    <?php if (!empty($error)): ?>
        <div class="alert-error"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" action="/auth/store">
        <label>Login</label>
        <input type="text" name="login" placeholder="Choisissez un login" required>

        <label>Mot de passe</label>
        <input type="password" name="password" placeholder="Choisissez un mot de passe" required>

        <label>Confirmer le mot de passe</label>
        <input type="password" name="confirm_password" placeholder="Confirmez le mot de passe" required>

        <button type="submit" class="btn btn-primary" style="width:100%;">S'inscrire</button>
    </form>

    <p style="text-align:center; margin-top:15px;">
        Déjà un compte ? <a href="/auth/login">Se connecter</a>
    </p>
</div>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layout.php';
?>