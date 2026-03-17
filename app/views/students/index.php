<?php
require base_path('app/views/partials/head.php');
require base_path('app/views/partials/nav.php');
?>

<!-- CONTENT -->
<div class="container mt-4">

    <div class="d-flex justify-content-between mb-3">
        <h2>Skolenu saraksts</h2>

        <a href="/students/create" class="btn btn-success">
            + Pievienot
        </a>
    </div>

    <div class="card shadow">
        <div class="card-header bg-dark text-white">
            Students table
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead class="table-primary">
                    <tr>
                        <th>#</th>
                        <th>Nosaukums</th>
                        <th>Grupas nosaukums</th>
                        <th>Vārds, uzvārds</th>
                        <th>Personas kods</th>
                        <th>Darbības</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data as $dd) : ?>
                    <tr>
                        <td><?= $dd['id'] ?? '' ?></td>
                        <td><?= $dd['group_name'] ?? '' ?></td>
                        <td><?= $dd['full_name'] ?? '' ?></td>
                        <td><?= $dd['personal_code'] ?? '' ?></td>
                        <td><?= $dd['created_at'] ?? '' ?></td>

                        <td>
                            <a class="btn btn-sm btn-warning">Labot</a>
                            <a class="btn btn-sm btn-danger">Dzēst</a>
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