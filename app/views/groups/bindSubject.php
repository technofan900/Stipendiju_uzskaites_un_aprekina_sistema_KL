<?php
require base_path('app/views/partials/head.php');
require base_path('app/views/partials/nav.php');

$errors = $errors ?? [];
?>

<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-success text-white">
            Saistīt priekšmetu ar grupām
        </div>

        <div class="card-body">
            <form method="POST" action="/groups/bindSubject">
                <!-- SUBJECT -->
                <div class="mb-3">
                    <label class="form-label">
                        Priekšmets
                    </label>

                    <select
                        name="subject_id" class="form-select" required>
                        <option value="">
                            Izvēlies priekšmetu
                        </option>

                        <?php foreach ($subjects as $subject): ?>
                            <option value="<?= $subject['id'] ?>">
                                <?= $subject['subject_name'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (!empty($errors['subject'])): ?>
                        <div class="text-danger">
                            <?= $errors['subject'] ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- GROUP CHECKBOXES -->
                <div class="mb-3">

                    <label class="form-label">
                        Izvēlies grupas
                    </label>

                    <?php foreach ($groups as $group): ?>
                        <div class="form-check">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="groups[]"
                                value="<?= $group['id'] ?>"
                                id="group<?= $group['id'] ?>"
                            >

                            <label
                                class="form-check-label" for="group<?= $group['id'] ?>">
                                <?= $group['group_name'] ?>
                            </label>
                        </div>
                    <?php endforeach; ?>

                    <?php if (!empty($errors['groups'])): ?>
                        <div class="text-danger">
                            <?= $errors['groups'] ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($errors['duplicate'])): ?>
                        <div class="text-danger">
                            <?= $errors['duplicate'] ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- BUTTONS -->
                <button
                    type="submit" class="btn btn-success">
                    Saglabāt
                </button>

                <a
                    href="/groups" class="btn btn-secondary">
                    Atpakaļ
                </a>
            </form>
        </div>
    </div>
</div>
<?php
require base_path('app/views/partials/footer.php');
?>