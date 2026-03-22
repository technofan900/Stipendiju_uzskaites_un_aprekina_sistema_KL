<?php
require base_path('app/views/partials/head.php');
require base_path('app/views/partials/nav.php');

$groupId = $groupId ?? '';
$students = $students ?? [];
$groups = $groups ?? [];
$periods = $periods ?? [];
$group_subjects = $group_subjects ?? [];
$errors = $errors ?? [];
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

                <select name="group_id"
                        class="form-select"
                        onchange="this.form.submit()">

                    <option value="">Izvēlies grupu</option>

                    <?php foreach ($groups as $group): ?>
                        <option
                            value="<?= $group['id'] ?>"
                            <?= $groupId == $group['id'] ? 'selected' : '' ?>
                        >
                            <?= $group['group_name'] ?>
                        </option>
                    <?php endforeach; ?>

                </select>

            </form>



            <?php if (!empty($groupId) && !empty($students)): ?>


                <?php if (!empty($errors['students'])): ?>
                    <div class="alert alert-danger">
                        <?= $errors['students'] ?>
                    </div>
                <?php endif; ?>


                <form method="POST" action="/stipend/form/create">


                    <!-- PERIOD -->

                    <div class="mb-3">

                        <label class="form-label">
                            Stipendijas periods
                        </label>

                        <select name="period"
                                class="form-select <?= isset($errors['period']) ? 'is-invalid' : '' ?>"
                                required>

                            <option value="">
                                Izvēlies periodu
                            </option>

                            <?php foreach ($periods as $period): ?>

                                <option value="<?= $period['id'] ?>">
                                    <?= $period['period'] ?>
                                </option>

                            <?php endforeach; ?>

                        </select>

                        <?php if (!empty($errors['period'])): ?>
                            <div class="invalid-feedback">
                                <?= $errors['period'] ?>
                            </div>
                        <?php endif; ?>

                    </div>



                    <!-- TABLE -->

                    <div style="overflow-x:auto;">

                        <table class="table table-bordered table-sm">

                            <thead class="table-light">

                                <tr>

                                    <th style="position: sticky; left:0; background:#f8f9fa;">
                                        Skolēns
                                    </th>

                                    <th>Kavējumi</th>

                                    <?php foreach ($group_subjects as $subject): ?>
                                        <th>
                                            <?= $subject['subject_name'] ?>
                                        </th>
                                    <?php endforeach; ?>

                                    <th>Papildsumma</th>

                                </tr>

                            </thead>


                            <tbody>


                            <?php foreach ($students as $student): ?>


                                <tr>

                                    <!-- NAME -->

                                    <td style="position: sticky; left:0; background:white;">

                                        <?= $student['full_name'] ?>

                                        <input type="hidden"
                                            name="students[<?= $student['id'] ?>][student_id]"
                                            value="<?= $student['id'] ?>">

                                        <input type="hidden"
                                            name="students[<?= $student['id'] ?>][group_id]"
                                            value="<?= $groupId ?>">

                                    </td>



                                    <!-- ABSENCES -->

                                    <td>

                                        <?php
                                        $absErr = "absences_" . $student['id'];
                                        ?>

                                        <input type="number"

                                            name="students[<?= $student['id'] ?>][absences]"

                                            class="form-control form-control-sm <?= isset($errors[$absErr]) ? 'is-invalid' : '' ?>"

                                            min="0"
                                            value="0"
                                        >

                                        <?php if (!empty($errors[$absErr])): ?>
                                            <div class="invalid-feedback">
                                                <?= $errors[$absErr] ?>
                                            </div>
                                        <?php endif; ?>

                                    </td>



                                    <!-- GRADES -->

                                    <?php foreach ($group_subjects as $subject): ?>

                                        <td>

                                            <?php
                                            $errKey =
                                                "grade_" .
                                                $student['id'] .
                                                "_" .
                                                $subject['id'];
                                            ?>

                                            <input type="number"

                                                name="students[<?= $student['id'] ?>][grades][<?= $subject['id'] ?>][grade]"

                                                class="form-control form-control-sm <?= isset($errors[$errKey]) ? 'is-invalid' : '' ?>"

                                                min="0"
                                                max="10"
                                                value="0"
                                            >


                                            <input type="hidden"

                                                name="students[<?= $student['id'] ?>][grades][<?= $subject['id'] ?>][category]"

                                                value="<?= $subject['category_type'] ?>"
                                            >


                                            <?php if (!empty($errors[$errKey])): ?>
                                                <div class="invalid-feedback">
                                                    <?= $errors[$errKey] ?>
                                                </div>
                                            <?php endif; ?>

                                        </td>

                                    <?php endforeach; ?>



                                    <!-- EXTRA -->

                                    <td>

                                        <?php
                                        $extraErr =
                                            "extra_" .
                                            $student['id'];
                                        ?>

                                        <input type="number"

                                            name="students[<?= $student['id'] ?>][extra_amount]"

                                            class="form-control form-control-sm <?= isset($errors[$extraErr]) ? 'is-invalid' : '' ?>"

                                            step="0.01"
                                            value="0"
                                        >

                                        <?php if (!empty($errors[$extraErr])): ?>
                                            <div class="invalid-feedback">
                                                <?= $errors[$extraErr] ?>
                                            </div>
                                        <?php endif; ?>

                                    </td>


                                </tr>


                            <?php endforeach; ?>


                            </tbody>

                        </table>

                    </div>



                    <!-- BUTTONS -->

                    <button type="submit"
                            class="btn btn-success">

                        Aprēķināt un Saglabāt

                    </button>

                    <a href="/"
                       class="btn btn-secondary">

                        Atpakaļ

                    </a>


                </form>


            <?php elseif (!empty($groupId)): ?>

                <div class="alert alert-warning">
                    Šajā grupā nav skolēnu.
                </div>

            <?php endif; ?>


        </div>
    </div>
</div>


<?php
require base_path('app/views/partials/footer.php');
?>