<?php
require base_path('app/views/partials/head.php');
require base_path('app/views/partials/nav.php');
?>

<div class="container mt-4">
    <div class="card shadow">

        <div class="card-header bg-primary text-white">
            Stipendijas aprēķins
        </div>

        <div class="card-body">
            <form method="POST" action="/stipend/form/create">
                <!-- 1. Grupa -->
                <div class="mb-3">
                    <label class="form-label">Grupa</label>
                    <select name="group_id" class="form-select" required>
                        <option value="">Izvēlies grupu</option>
                        <?php foreach ($groups as $group): ?>
                            <option value="<?= $group['id'] ?>">
                                <?= $group['group_name'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <!-- 2. Skolēns -->
                <div class="mb-3">
                    <label class="form-label">Skolēns</label>
                    <select name="student_id" class="form-select" required>
                        <option value="">Izvēlies skolēnu</option>

                        <?php foreach ($students as $student): ?>
                            <option value="<?= $student['id'] ?>">
                                <?= $student['full_name'] ?>
                            </option>
                        <?php endforeach; ?>

                    </select>
                </div>
                <!-- 3. Periods -->
                <div class="mb-3">
                    <label class="form-label">
                        Stipendijas periods
                    </label>
                    <select name="period" class="form-select">
                        <option value="">Izvēlies skolēnu</option>
                        <?php foreach ($periods as $period): ?>
                            <option value="<?= $period['id'] ?>">
                                <?= $period['period'] ?>
                            </option>
                        <?php endforeach; ?>
                        
                    </select>
                </div>

                <!-- 4. Kavējumi -->
                <div class="mb-3">
                    <label class="form-label">
                        Kavējumi
                    </label>
                    <input type="number"
                           name="absences"
                           class="form-control"
                           value="0">
                </div>

                <!-- 5. Atzīmes -->
                <div class="mb-3">

                    <label class="form-label">
                        Atzīmes pa priekšmetiem
                    </label>

                    <?php foreach ($subjects as $subject): ?>
                        <div class="mb-2">
                            <label>
                                <?= $subject['subject_name'] ?>
                            </label>
                            <input
                                type="number"
                                name="grades[<?= $subject['id'] ?>]"
                                class="form-control"
                                min="1"
                                max="10"
                            >

                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- 6. Papildsumma -->
                <div class="mb-3">
                    <label class="form-label">
                        Papildsumma
                    </label>
                    <input
                        type="number"
                        name="extra_amount"
                        class="form-control"
                        step="0.01"
                        value="0"
                    >
                </div>

                <!-- Buttons -->
                <button
                    type="submit"
                    class="btn btn-success">

                    Aprēķināt
                </button>
                <a
                    href="/"
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