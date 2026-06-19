<?php
ob_start();
?>
<div style="max-width:400px; margin:50px auto; background:white; padding:30px; border-radius:10px; box-shadow:0 2px 10px rgba(0,0,0,0.1);">
    <h2 style="text-align:center;">🔐 Connexion</h2>

    <?php if (!empty($error)): ?>
        <div class="alert-error"><?= $error ?></div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div style="background:#d4edda; color:#155724; padding:10px; border-radius:5px; margin-bottom:15px;">
            <?= $success ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="/auth/authenticate">
        <label>Login</label>
        <input type="text" name="login" placeholder="Votre login" required>

        <label>Mot de passe</label>
        <input type="password" name="password" placeholder="Votre mot de passe" required>

        <button type="submit" class="btn btn-primary" style="width:100%;">Se connecter</button>
    </form>

    <p style="text-align:center; margin-top:15px;">
        Pas encore de compte ? <a href="/auth/register">S'inscrire</a>
    </p>

    <p style="text-align:center; margin-top:10px; font-size:12px; color:#999;">
        Comptes de test : admin/admin123 | editeur1/edit123 | visiteur1/visit123
    </p>
</div>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layout.php';
?>