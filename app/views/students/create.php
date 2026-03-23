<?php
require base_path('app/views/partials/head.php');
require base_path('app/views/partials/nav.php');

$errors = $errors ?? [];
?>

<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-success text-white">
            Pievienot skolēnu
        </div>

        <div class="card-body">
            <form method="POST" action="/students/create">

                <!-- GROUP -->
                <div class="mb-3">
                    <label class="form-label">Grupa</label>
                    <select 
                        name="group_id" 
                        class="form-select <?= isset($errors['group_id']) ? 'is-invalid' : '' ?>" 
                        required
                    >
                        <option value="" disabled <?= empty($_POST['group_id']) ? 'selected' : '' ?>>Izvēlies grupu</option>
                        <?php foreach ($data as $group) : ?>
                            <option value="<?= $group['id'] ?>" <?= (isset($_POST['group_id']) && $_POST['group_id'] == $group['id']) ? 'selected' : '' ?>>
                                <?= $group['group_name'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <?php if (!empty($errors['group_id'])) : ?>
                        <div class="invalid-feedback">
                            <?= $errors['group_id'] ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- NAME -->
                <div class="mb-3">
                    <label class="form-label">Vārds, uzvārds</label>
                    <input 
                        type="text" 
                        name="name_surname" 
                        class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>" 
                        value="<?= $_POST['name_surname'] ?? '' ?>"
                        required
                    >

                    <?php if (!empty($errors['name'])) : ?>
                        <div class="invalid-feedback">
                            <?= $errors['name'] ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- PERSONAL CODE -->
                <div class="mb-3">
                    <label class="form-label">Personas kods</label>
                    <input
                        type="text"
                        name="personal_code"
                        class="form-control <?= isset($errors['p_code']) ? 'is-invalid' : '' ?>"
                        value="<?= $_POST['personal_code'] ?? '' ?>"
                        required
                    >

                    <?php if (!empty($errors['p_code'])) : ?>
                        <div class="invalid-feedback">
                            <?= $errors['p_code'] ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Buttons -->
                <button type="submit" class="btn btn-success">Saglabāt</button>
                <a href="/students" class="btn btn-secondary">Atpakaļ</a>

            </form>
        </div>
    </div>
</div>

<?php 
require base_path('app/views/partials/footer.php');
?>