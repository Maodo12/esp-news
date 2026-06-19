<?php
ob_start();
?>
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
    <h2>👥 Gestion des utilisateurs</h2>
    <a href="/users/create" class="btn btn-primary">+ Nouvel utilisateur</a>
</div>

<?php if (isset($_GET['error'])): ?>
    <div class="alert-error"><?= htmlspecialchars($_GET['error']) ?></div>
<?php endif; ?>

<?php if (empty($users)): ?>
    <div class="card">
        <p>Aucun utilisateur.</p>
    </div>
<?php else: ?>
    <div style="overflow-x:auto;">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Login</th>
                    <th>Rôle</th>
                    <th>Date création</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= $user['id'] ?></td>
                        <td><?= htmlspecialchars($user['login']) ?></td>
                        <td>
                            <span class="badge badge-<?= $user['role'] ?>">
                                <?= htmlspecialchars($user['role']) ?>
                            </span>
                        </td>
                        <td><?= $user['dateCreation'] ?></td>
                        <td>
                            <a href="/users/<?= $user['id'] ?>/edit" class="btn btn-secondary" style="padding:4px 10px; font-size:12px;">✏️</a>
                            <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                <a href="/users/<?= $user['id'] ?>/delete" class="btn btn-danger" style="padding:4px 10px; font-size:12px;" onclick="return confirm('Supprimer cet utilisateur ?')">🗑️</a>
                            <?php else: ?>
                                <span class="btn btn-secondary" style="padding:4px 10px; font-size:12px; opacity:0.5; cursor:not-allowed;">🔒</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <p style="margin-top:10px; color:#999; font-size:13px;">Total : <?= count($users) ?> utilisateur(s)</p>
<?php endif; ?>

<style>
    table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    table th {
        background: #1a1a2e;
        color: white;
        padding: 12px;
        text-align: left;
    }
    table td {
        padding: 12px;
        border-bottom: 1px solid #eee;
    }
    table tr:hover {
        background: #f8f9fa;
    }
    .badge-administrateur { background: #dc3545; color: white; padding: 3px 10px; border-radius: 12px; font-size: 12px; }
    .badge-editeur { background: #ffc107; color: #1a1a2e; padding: 3px 10px; border-radius: 12px; font-size: 12px; }
    .badge-visiteur { background: #28a745; color: white; padding: 3px 10px; border-radius: 12px; font-size: 12px; }
</style>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layout.php';
?>