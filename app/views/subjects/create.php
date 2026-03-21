<?php
require base_path('app/views/partials/head.php');
require base_path('app/views/partials/nav.php');

// ensure $errors exists
$errors = $errors ?? [];
?>

<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-success text-white">
            Pievienot priekšmetu
        </div>

        <div class="card-body">
            <form method="POST" action="/subjects/create">

                <!-- Subject name -->
                <div class="mb-3">
                    <label class="form-label">Priekšmeta nosaukums</label>

                    <input
                        type="text"
                        name="subject_name"
                        class="form-control <?= isset($errors['subject']) || isset($errors['subject_name']) ? 'is-invalid' : '' ?>"
                        value="<?= $_POST['subject_name'] ?? '' ?>"
                        required
                    >

                    <?php if (!empty($errors['subject'])) : ?>
                        <div class="invalid-feedback">
                            <?= $errors['subject'] ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($errors['subject_name'])) : ?>
                        <div class="invalid-feedback">
                            <?= $errors['subject_name'] ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Category -->
                <div class="mb-3">
                    <label class="form-label">Kategorija</label>

                    <select
                        name="category_type"
                        class="form-select <?= isset($errors['type']) ? 'is-invalid' : '' ?>"
                        required
                    >
                        <option value="">Izvēlies</option>
                        <option value="P" <?= (isset($_POST['category_type']) && $_POST['category_type'] === 'P') ? 'selected' : '' ?>>Profesionālais (P)</option>
                        <option value="V" <?= (isset($_POST['category_type']) && $_POST['category_type'] === 'V') ? 'selected' : '' ?>>Vispārizglītojošais (V)</option>
                    </select>

                    <?php if (!empty($errors['type'])) : ?>
                        <div class="invalid-feedback">
                            <?= $errors['type'] ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Buttons -->
                <button type="submit" class="btn btn-success">Saglabāt</button>
                <a href="/subjects" class="btn btn-secondary">Atpakaļ</a>

            </form>
        </div>
    </div>
</div>

<?php
require base_path('app/views/partials/footer.php');
?>