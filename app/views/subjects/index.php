<?php
require base_path('app/views/partials/head.php');
require base_path('app/views/partials/nav.php');
?>

<!-- CONTENT -->
<div class="container mt-4">

    <div class="d-flex justify-content-between mb-3">
        <h2>Priekšmetu saraksts</h2>

        <a href="/subjects/create" class="btn btn-success">
            + Pievienot
        </a>
    </div>

    <div class="card shadow">
        <div class="card-header bg-dark text-white">
            Subjects table
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead class="table-primary">
                    <tr>
                        <th>#</th>
                        <th>Nosaukums</th>
                        <th>Kategorija</th>
                        <th>Izveidots</th>
                        <th>Darbības</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data as $dd) : ?>
                    <tr>
                        <td><?= $dd['id'] ?? '' ?></td>
                        <td><?= $dd['subject_name'] ?? '' ?></td>
                        <td><?= $dd['category_type'] ?? '' ?></td>
                        <td><?= $dd['created_at'] ?? '' ?></td>

                        <td>
                            <a class="btn btn-sm btn-warning">Labot</a>
                            <form method="POST" action="/delete" style="display:inline;">

                                <input type="hidden" name="_method" value="DELETE">

                                <input type="hidden" name="table" value="subjects">

                                <input type="hidden" name="id" value="<?= $dd['id'] ?>">

                                <input type="hidden" name="redirect" value="/subjects">

                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Dzēst ierakstu?')">
                                    Dzēst
                                </button>

                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- CONTENT -->
<div class="container mt-4">

    <div class="d-flex justify-content-between mb-3">
        <h2>Atzīmes priekšmetos</h2>

    </div>

    <div class="card shadow">
        <div class="card-header bg-dark text-white">
            Grades table
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead class="table-primary">
                    <tr>
                        <th>#</th>
                        <th>Vārds, Uzvārds</th>
                        <th>Priekšmets</th>
                        <th>Atzīme</th>
                        <th>Darbības</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($grades as $grade) : ?>
                    <tr>
                        <td><?= $grade['id'] ?? '' ?></td>
                        <td><?= $grade['student_name'] ?? '' ?></td>
                        <td><?= $grade['subject_name'] ?? '' ?></td>
                        <td><?= $grade['grade'] ?? '' ?></td>

                        <td>
                            <a class="btn btn-sm btn-warning">Labot</a>
                            <form method="POST" action="/delete" style="display:inline;">

                                <input type="hidden" name="_method" value="DELETE">

                                <input type="hidden" name="table" value="student_grades">

                                <input type="hidden" name="id" value="<?= $grade['id'] ?>">

                                <input type="hidden" name="redirect" value="/subjects">

                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Dzēst ierakstu?')">
                                    Dzēst
                                </button>

                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php 
require base_path('app/views/partials/footer.php');
?>