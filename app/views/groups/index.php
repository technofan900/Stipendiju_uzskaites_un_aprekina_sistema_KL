<?php
require base_path('app/views/partials/head.php');
require base_path('app/views/partials/nav.php');
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between mb-3">
        <h2>Grupu saraksts</h2>

        <a href="/groups/create"
           class="btn btn-success">
            + Pievienot grupu
        </a>
    </div>

    <div class="card shadow">
        <div class="card-header bg-dark text-white">
            Groups table
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead class="table-primary">
                    <tr>
                        <th>#</th>
                        <th>Grupas nosaukums</th>
                        <th>Izveidots</th>
                        <th>Darbības</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data as $dd) : ?>
                    <tr>
                        <td><?= $dd['id'] ?? '' ?></td>
                        <td><?= $dd['group_name'] ?? '' ?></td>
                        <td><?= $dd['created_at'] ?? '' ?></td>

                        <td>
                            <a class="btn btn-sm btn-warning">Labot</a>
                            <form method="POST" action="/delete" style="display:inline;">

                                <input type="hidden" name="_method" value="DELETE">

                                <input type="hidden" name="table" value="groups">

                                <input type="hidden" name="id" value="<?= $dd['id'] ?>">
                                
                                <input type="hidden" name="redirect" value="/groups">

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

<div class="container mt-4">
    <div class="d-flex justify-content-between mb-3">
        <h2>Grupu priekšmeti</h2>

        <a href="/groups/bindSubject"
           class="btn btn-success">
            + Saistīt priekšmetu
        </a>
    </div>

    <div class="card shadow">
        <div class="card-header bg-dark text-white">
            Priekšmeti
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead class="table-primary">
                    <tr>
                        <th>#</th>
                        <th>Priekšmets</th>
                        <th>Grupa</th>
                        <th>Darbības</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($group_subjects as $g_subject) : ?>
                    <tr>
                        <td><?= $g_subject['id'] ?? '' ?></td>
                        <td><?= $g_subject['subject_name'] ?? '' ?></td>
                        <td><?= $g_subject['group_name'] ?? '' ?></td>


                        <td>
                            <a class="btn btn-sm btn-warning">Labot</a>
                            <form method="POST" action="/delete" style="display:inline;">

                                <input type="hidden" name="_method" value="DELETE">

                                <input type="hidden" name="table" value="group_subjects">

                                <input type="hidden" name="id" value="<?= $g_subject['id'] ?>">

                                <input type="hidden" name="redirect" value="/groups">

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