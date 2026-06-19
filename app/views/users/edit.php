<?php
ob_start();
?>
<div style="margin-bottom:15px;">
    <a href="/users" class="btn btn-secondary">← Retour</a>
</div>

<div class="card" style="max-width:500px; margin:0 auto;">
    <h2>✏️ Modifier l'utilisateur</h2>

    <?php if (!empty($error)): ?>
        <div class="alert-error"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" action="/users/<?= $user['id'] ?>/update">
        <label>Login</label>
        <input type="text" name="login" value="<?= htmlspecialchars($user['login']) ?>" required>

        <label>Rôle</label>
        <select name="role">
            <option value="visiteur" <?= $user['role'] === 'visiteur' ? 'selected' : '' ?>>Visiteur</option>
            <option value="editeur" <?= $user['role'] === 'editeur' ? 'selected' : '' ?>>Éditeur</option>
            <option value="administrateur" <?= $user['role'] === 'administrateur' ? 'selected' : '' ?>>Administrateur</option>
        </select>

        <button type="submit" class="btn btn-primary" style="width:100%;">💾 Enregistrer</button>
    </form>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layout.php';
?>