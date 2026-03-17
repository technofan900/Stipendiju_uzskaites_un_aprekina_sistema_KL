<?php
require base_path('app/views/partials/head.php');
require base_path('app/views/partials/nav.php');
?>
<!-- Page Header -->
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Stipendijas ieraksti</h2>
        <a href="/stipend/form" class="btn btn-success">Pievienot stipendijas aprēķinu</a>
    </div>

    <!-- Subjects Table -->
    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-primary">
                <tr>
                    <th>#</th>
                    <th>Skolēns</th>
                    <th>Grupa</th>
                    <th>Periods</th>
                    <th>Vidējā atzīme</th>
                    <th>Nesekmīgo skaits</th>
                    <th>Neierašanās</th>
                    <th>Pamatstipendija</th>
                    <th>Papildsumma</th>
                    <th>pilnā stipendija</th>
                    <th>Izveidots</th>
                    <th>Darbības</th>
                </tr>
            </thead>
            <tbody>   
            <?php foreach ($data as $dd) : ?>
                <tr>
                    <td><?= $dd['id'] ?? '' ?></td>
                    <td><?= $dd['full_name'] ?? '' ?></td>
                    <td><?= $dd['group_name'] ?? '' ?></td>
                    <td><?= $dd['period_group'] ?? '' ?></td>
                    <td><?= $dd['average_grade'] ?? '' ?></td>
                    <td><?= $dd['failed_subjects_count'] ?? '' ?></td>
                    <td><?= $dd['absences'] ?? '' ?></td>
                    <td><?= $dd['base_stipend'] ?? '' ?></td>
                    <td><?= $dd['activity_bonus'] ?? '' ?></td>
                    <td><?= $dd['total_stipend'] ?? '' ?></td>
                    <td><?= $dd['created_at'] ?? '' ?></td>                    
                    <td>
                        <a href="/subjects/edit/<?= $dd['id'] ?>" class="btn btn-sm btn-warning">Labot</a>
                        <a href="/subjects/delete/<?= $dd['id'] ?>" class="btn btn-sm btn-danger">Dzēst</a>
                    </td>
                </tr>

            <?php endforeach; ?>
            </tbody>            
        </table>
    </div>
</div>
<?php 
require base_path('app/views/partials/footer.php');
?>