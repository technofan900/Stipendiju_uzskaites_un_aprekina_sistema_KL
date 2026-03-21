<?php
require base_path('app/views/partials/head.php');
require base_path('app/views/partials/nav.php');

$groupId = $groupId ?? '';
$students = $students ?? [];
$groups = $groups ?? [];
$periods = $periods ?? [];
$group_subjects = $group_subjects ?? [];
?>

<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            Stipendijas aprēķins
        </div>

        <div class="card-body">

            <!-- GROUP SELECT -->
            <form method="GET" action="/stipend/form" class="mb-4">
                <label class="form-label">Grupa</label>
                <select name="group_id" class="form-select" onchange="this.form.submit()">
                    <option value="">Izvēlies grupu</option>
                    <?php foreach ($groups as $group): ?>
                        <option value="<?= $group['id'] ?>" <?= $groupId == $group['id'] ? 'selected' : '' ?>>
                            <?= $group['group_name'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>

            <?php if (!empty($groupId) && !empty($students)): ?>

                <form method="POST" action="/stipend/form/create">

                    <!-- PERIOD -->
                    <div class="mb-3">
                        <label class="form-label">Stipendijas periods</label>
                        <select name="period" class="form-select" required>
                            <option value="">Izvēlies periodu</option>
                            <?php foreach ($periods as $period): ?>
                                <option value="<?= $period['id'] ?>"><?= $period['period'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- STUDENTS TABLE -->
                    <div style="overflow-x:auto;">
                        <table class="table table-bordered table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th style="position: sticky; left: 0; background:#f8f9fa; z-index:2;">Skolēns</th>
                                    <th>Kavējumi</th>
                                    <?php foreach ($group_subjects as $subject): ?>
                                        <th><?= $subject['subject_name'] ?></th>
                                    <?php endforeach; ?>
                                    <th>Papildsumma</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($students as $student): ?>
                                    <tr>
                                        <td style="position: sticky; left: 0; background:white; z-index:1;">
                                            <?= $student['full_name'] ?>
                                            <input type="hidden" name="students[<?= $student['id'] ?>][student_id]" value="<?= $student['id'] ?>">
                                            <input type="hidden" name="students[<?= $student['id'] ?>][group_id]" value="<?= $groupId ?>">
                                        </td>

                                        <!-- Absences -->
                                        <td>
                                            <input type="number" name="students[<?= $student['id'] ?>][absences]" class="form-control form-control-sm" min="0" value="0">
                                        </td>

                                        <!-- Grades -->
                                        <?php foreach ($group_subjects as $subject): ?>
                                            <td>
                                                <input type="number" 
                                                       name="students[<?= $student['id'] ?>][grades][<?= $subject['id'] ?>][grade]" 
                                                       class="form-control form-control-sm" 
                                                       min="0" max="10" value="0">
                                                <input type="hidden" 
                                                       name="students[<?= $student['id'] ?>][grades][<?= $subject['id'] ?>][category]" 
                                                       value="<?= $subject['category_type'] ?>">
                                            </td>
                                        <?php endforeach; ?>

                                        <!-- Extra -->
                                        <td>
                                            <input type="number" name="students[<?= $student['id'] ?>][extra_amount]" class="form-control form-control-sm" step="0.01" value="0">
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- BUTTONS -->
                    <button type="submit" class="btn btn-success">Aprēķināt un Saglabāt</button>
                    <a href="/" class="btn btn-secondary">Atpakaļ</a>

                </form>

            <?php elseif (!empty($groupId)): ?>
                <div class="alert alert-warning">Šajā grupā nav skolēnu.</div>
            <?php endif; ?>

        </div>
    </div>
</div>

<?php
require base_path('app/views/partials/footer.php');
?>