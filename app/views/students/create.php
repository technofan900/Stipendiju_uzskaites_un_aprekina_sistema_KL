<?php
require base_path('app/views/partials/head.php');
require base_path('app/views/partials/nav.php');
?>

<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-success text-white">
            Pievienot skolēnu
        </div>

        <div class="card-body">
            <form method="POST" action="/students/create">
                <div class="mb-3">
                    <label class="form-label">Grupa</label>
                    <select name="group_id" class="form-select" required>
                        <option value="" disabled>Izvēlies grupu</option>
                        <?php foreach ($data as $group) : ?>
                            <option value="<?= $group['id'] ?>">
                                <?= $group['group_name'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">
                        Vārds, uzvārds
                    </label>
                    <input type="text" name="name_surname" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">
                        Personas kods
                    </label>
                    <input type="text" name="personal_code" class="form-control" required>
                </div>

                <!-- Buttons -->
                <button type="submit"
                        class="btn btn-success">
                    Saglabāt
                </button>
                <a href="/students"
                   class="btn btn-secondary">
                    Atpakaļ
                </a>
            </form>
        </div>
    </div>
</div>

<?php 
require base_path('app/views/partials/footer.php');
?>