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

            <!-- ========================= -->
            <!-- GROUP SELECT (GET FORM) -->
            <!-- ========================= -->
            <form method="GET" action="/stipend/form" class="mb-4">
                <label class="form-label">
                    Grupa
                </label>
                <select
                    name="group_id"
                    class="form-select"
                    onchange="this.form.submit()"
                >
                    <option value="">
                        Izvēlies grupu
                    </option>

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

            <!-- ========================= -->
            <!-- MAIN FORM (POST) -->
            <!-- ========================= -->
            <form method="POST" action="/stipend/form/create">
                <!-- keep group -->
                <input
                    type="hidden"
                    name="group_id"
                    value="<?= $groupId ?>"
                >

                <!-- ========================= -->
                <!-- STUDENT -->
                <!-- ========================= -->
                <div class="mb-3">
                    <label class="form-label">
                        Skolēns
                    </label>

                    <select
                        name="student_id"
                        class="form-select"
                        required
                    >
                        <option value="">
                            Izvēlies skolēnu
                        </option>

                        <?php foreach ($students as $student): ?>

                            <option value="<?= $student['id'] ?>">
                                <?= $student['full_name'] ?>
                            </option>

                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- ========================= -->
                <!-- PERIOD -->
                <!-- ========================= -->
                <div class="mb-3">
                    <label class="form-label">
                        Stipendijas periods
                    </label>

                    <select
                        name="period"
                        class="form-select"
                        required
                    >
                        <option value="">
                            Izvēlies periodu
                        </option>

                        <?php foreach ($periods as $period): ?>

                            <option value="<?= $period['id'] ?>">
                                <?= $period['period'] ?>
                            </option>

                        <?php endforeach; ?>
                    </select>
                </div>


                <!-- ========================= -->
                <!-- ABSENCES -->
                <!-- ========================= -->
                <div class="mb-3">

                    <label class="form-label">
                        Kavējumi
                    </label>

                    <input
                        type="number"
                        name="absences"
                        class="form-control"
                        value="0"
                    >

                </div>

                <!-- ========================= -->
                <!-- GRADES -->
                <!-- ========================= -->
                <?php if (!empty($groupId)): ?>

                    <div class="mb-3">

                        <label class="form-label">
                            Atzīmes pa priekšmetiem
                        </label>

                        <?php foreach ($group_subjects as $subject): ?>
                            <div class="mb-2">
                                <label>
                                    <?= $subject['subject_name'] ?>
                                </label>

                                <input
                                    type="number"
                                    name="grades[<?= $subject['id'] ?>][grade]"
                                    class="form-control"
                                    min="1"
                                    max="10"
                                >

                                <input
                                    type="hidden"
                                    name="grades[<?= $subject['id'] ?>][category]"
                                    value="<?= $subject['category_type'] ?>"
                                >

                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <!-- ========================= -->
                <!-- EXTRA -->
                <!-- ========================= -->
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

                <!-- ========================= -->
                <!-- BUTTONS -->
                <!-- ========================= -->
                <button
                    type="submit"
                    class="btn btn-success"
                >
                    Aprēķināt
                </button>

                <a
                    href="/"
                    class="btn btn-secondary"
                >
                    Atpakaļ
                </a>
            </form>
        </div>
    </div>
</div>

<?php
require base_path('app/views/partials/footer.php');
?>