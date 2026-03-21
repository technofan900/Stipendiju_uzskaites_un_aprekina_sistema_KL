<?php
require base_path('app/views/partials/head.php');
require base_path('app/views/partials/nav.php');

$errors = $errors ?? [];
// $student should be provided by the controller with existing data
// $groups should be provided for the select dropdown
?>

<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            Rediģēt skolēnu
        </div>

        <div class="card-body">
            <form method="POST" action="/student/edit?id=<?= $student['id'] ?>">

                <!-- Hidden field for the student ID -->
                <input type="hidden" name="id" value="<?= htmlspecialchars($student['id']) ?>">

                <!-- GROUP -->
                <div class="mb-3">
                    <label class="form-label">Grupa</label>
                    <select 
                        name="group_id" 
                        class="form-select <?= isset($errors['group_id']) ? 'is-invalid' : '' ?>" 
                        required
                    >
                        <option value="" disabled <?= empty($student['group_id']) ? 'selected' : '' ?>>Izvēlies grupu</option>
                        <?php foreach ($groups as $group) : ?>
                            <option value="<?= $group['id'] ?>" <?= ($student['group_id'] == $group['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($group['group_name']) ?>
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
                        value="<?= htmlspecialchars($student['full_name'] ?? '') ?>"
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
                        value="<?= htmlspecialchars($student['personal_code'])  ?? '' ?>"
                        required
                    >

                    <?php if (!empty($errors['p_code'])) : ?>
                        <div class="invalid-feedback">
                            <?= $errors['p_code'] ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Buttons -->
                <button type="submit" class="btn btn-primary">Saglabāt izmaiņas</button>
                <a href="/students" class="btn btn-secondary">Atpakaļ</a>

            </form>
        </div>
    </div>
</div>

<?php 
require base_path('app/views/partials/footer.php');
?>