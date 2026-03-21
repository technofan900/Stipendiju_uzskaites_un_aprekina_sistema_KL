<?php
require base_path('app/views/partials/head.php');
require base_path('app/views/partials/nav.php');

$periods = $periods ?? [];   // List of periods
$groups = $groups ?? [];     // List of groups
$years = $years ?? [];       // List of years
$records = $records ?? [];   // Filtered search results
$filters = $_GET ?? [];      // Selected filters
?>

<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-info text-white">
            Meklēšana un filtrēšana
        </div>

        <div class="card-body">
            <!-- ========================= -->
            <!-- FILTER FORM -->
            <!-- ========================= -->
            <form method="GET" action="" class="row g-3 mb-4">

                <!-- Period -->
                <div class="col-md-3">
                    <label class="form-label">Periods</label>
                    <select name="period_id" class="form-select">
                        <option value="">Visi</option>
                        <?php foreach ($periods as $period): ?>
                            <option value="<?= $period['id'] ?>" <?= (isset($filters['period_id']) && $filters['period_id'] == $period['id']) ? 'selected' : '' ?>>
                                <?= $period['period'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Year -->
                <div class="col-md-3">
                    <label class="form-label">Gads</label>
                    <select name="year" class="form-select">
                        <option value="">Visi</option>
                        <?php foreach ($years as $year): ?>
                            <option value="<?= $year ?>" <?= (isset($filters['year']) && $filters['year'] == $year) ? 'selected' : '' ?>>
                                <?= $year ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Group -->
                <div class="col-md-3">
                    <label class="form-label">Grupa</label>
                    <select name="group_id" class="form-select">
                        <option value="">Visas grupas</option>
                        <?php foreach ($groups as $group): ?>
                            <option value="<?= $group['id'] ?>" <?= (isset($filters['group_id']) && $filters['group_id'] == $group['id']) ? 'selected' : '' ?>>
                                <?= $group['group_name'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Student Name -->
                <div class="col-md-3">
                    <label class="form-label">Skolēna vārds / uzvārds</label>
                    <input type="text" name="student_name" class="form-control" value="<?= $filters['student_name'] ?? '' ?>" placeholder="Ievadi vārdu vai uzvārdu">
                </div>

                <!-- Buttons -->
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Meklēt</button>
                    <a href="/search" class="btn btn-secondary">Notīrīt</a>
                </div>
            </form>

            <!-- ========================= -->
            <!-- RESULTS TABLE -->
            <!-- ========================= -->
            <?php if (!empty($records)): ?>
                <div style="overflow-x:auto;">
                    <table class="table table-bordered table-striped table-sm">
                        <thead class="table-light">
                            <tr>
                                <th>Vārds, Uzvārds</th>
                                <th>Grupa</th>
                                <th>Gads</th>
                                <th>Periods</th>
                                <th>Vidējā atzīme</th>
                                <th>Kavējumi</th>
                                <th>Nesekmīgo skaits</th>
                                <th>Pamatstipendija (€)</th>
                                <th>Aktivitāšu piemaksa (€)</th>
                                <th>Gala summa (€)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($records as $rec): ?>
                                <tr>
                                    <td><?= htmlspecialchars($rec['student_name']) ?></td>
                                    <td><?= htmlspecialchars($rec['group_name']) ?></td>
                                    <td><?= $rec['year'] ?></td>
                                    <td><?= htmlspecialchars($rec['period']) ?></td>
                                    <td><?= number_format($rec['average_grade'], 2) ?></td>
                                    <td><?= $rec['absences'] ?></td>
                                    <td><?= $rec['failed_subjects_count'] ?></td>
                                    <td><?= number_format($rec['base_stipend'], 2) ?></td>
                                    <td><?= number_format($rec['activity_bonus'], 2) ?></td>
                                    <td><?= number_format($rec['total_stipend'], 2) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-warning">Nav atrasts neviens ieraksts pēc izvēlētajiem kritērijiem.</div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
require base_path('app/views/partials/footer.php');
?>