<?php
require base_path('app/views/partials/head.php');
require base_path('app/views/partials/nav.php');

$errors = $errors ?? [];
?>

<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            Rediģēt grupu
        </div>

        <div class="card-body">
            <!-- Make sure the action includes the group ID -->
            <form method="POST" action="/group/edit?id=<?= $group['id'] ?>">

                <!-- Hidden field for the group ID (optional but safer) -->
                <input type="hidden" name="id" value="<?= htmlspecialchars($group['id']) ?>">

                <div class="mb-3">
                    <label class="form-label">
                        Grupas nosaukums
                    </label>

                    <input
                        type="text"
                        name="group_name"
                        class="form-control <?= isset($errors['group_name']) ? 'is-invalid' : '' ?>"
                        value="<?= htmlspecialchars($group['group_name'] ?? '') ?>"
                        required
                    >

                    <?php if (!empty($errors['group_name'])) : ?>
                        <div class="invalid-feedback">
                            <?= $errors['group_name'] ?>
                        </div>
                    <?php endif; ?>
                </div>

                <button type="submit" class="btn btn-primary">
                    Saglabāt izmaiņas
                </button>

                <a href="/groups" class="btn btn-secondary">
                    Atpakaļ
                </a>

            </form>
        </div>
    </div>
</div>

<?php
require base_path('app/views/partials/footer.php');
?>