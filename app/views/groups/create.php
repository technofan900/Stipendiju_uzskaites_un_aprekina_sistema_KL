<?php
require base_path('app/views/partials/head.php');
require base_path('app/views/partials/nav.php');

$errors = $errors ?? [];
?>

<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-success text-white">
            Pievienot grupu
        </div>

        <div class="card-body">
            <form method="POST" action="/groups/create">

                <div class="mb-3">
                    <label class="form-label">
                        Grupas nosaukums
                    </label>

                    <input
                        type="text"
                        name="group_name"
                        class="form-control <?= isset($errors['group_name']) ? 'is-invalid' : '' ?>"
                        required
                    >

                    <?php if (!empty($errors['group_name'])) : ?>
                        <div class="invalid-feedback">
                            <?= $errors['group_name'] ?>
                        </div>
                    <?php endif; ?>

                </div>

                <!-- Buttons -->
                <button type="submit" class="btn btn-success">
                    Saglabāt
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