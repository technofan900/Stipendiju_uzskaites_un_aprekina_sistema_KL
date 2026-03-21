<?php
require base_path('app/views/partials/head.php');
require base_path('app/views/partials/nav.php');

$errors = $errors ?? [];
?>

<div class="container mt-4">
    <div class="card shadow">

        <div class="card-header bg-success text-white">
            Pievienot stipendijas periodu
        </div>

        <div class="card-body">

            <form method="POST" action="/period/create">

                <!-- YEAR -->
                <div class="mb-3">
                    <label class="form-label">Gads</label>

                    <input
                        type="number"
                        name="year"
                        class="form-control <?= isset($errors['year']) ? 'is-invalid' : '' ?>"
                        required
                    >

                    <?php if (!empty($errors['year'])): ?>
                        <div class="invalid-feedback">
                            <?= $errors['year'] ?>
                        </div>
                    <?php endif; ?>
                </div>


                <!-- PERIOD -->
                <div class="mb-3">
                    <label class="form-label">Periods</label>

                    <input
                        type="text"
                        name="period"
                        class="form-control <?= isset($errors['period']) ? 'is-invalid' : '' ?>"
                        placeholder="piem. 1. semestris"
                        required
                    >

                    <?php if (!empty($errors['period'])): ?>
                        <div class="invalid-feedback">
                            <?= $errors['period'] ?>
                        </div>
                    <?php endif; ?>
                </div>


                <!-- PERIOD GROUP -->
                <div class="mb-3">
                    <label class="form-label">Perioda grupa</label>

                    <input
                        type="text"
                        name="period_group"
                        class="form-control <?= isset($errors['period_group']) ? 'is-invalid' : '' ?>"
                        placeholder="piem. 6 mēneši"
                    >

                    <?php if (!empty($errors['period_group'])): ?>
                        <div class="invalid-feedback">
                            <?= $errors['period_group'] ?>
                        </div>
                    <?php endif; ?>
                </div>


                <!-- BUTTONS -->
                <button type="submit" class="btn btn-success">
                    Saglabāt
                </button>

                <a href="/period" class="btn btn-secondary">
                    Atpakaļ
                </a>

            </form>

        </div>
    </div>
</div>

<?php
require base_path('app/views/partials/footer.php');
?>