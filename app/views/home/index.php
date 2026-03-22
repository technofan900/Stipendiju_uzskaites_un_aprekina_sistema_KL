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
                    <td><?= htmlspecialchars($dd['id']) ?? '' ?></td>
                    <td><?= htmlspecialchars($dd['full_name']) ?? '' ?></td>
                    <td><?= htmlspecialchars($dd['group_name']) ?? '' ?></td>
                    <td><?= htmlspecialchars($dd['period_group']) ?? '' ?></td>
                    <td><?= htmlspecialchars($dd['average_grade']) ?? '' ?></td>
                    <td><?= htmlspecialchars($dd['failed_subjects_count'])  ?? '' ?></td>
                    <td><?= htmlspecialchars($dd['absences']) ?? '' ?></td>
                    <td><?= htmlspecialchars($dd['base_stipend']) ?? '' ?></td>
                    <td><?= htmlspecialchars($dd['activity_bonus']) ?? '' ?></td>
                    <td><?= htmlspecialchars($dd['total_stipend']) ?? '' ?></td>
                    <td><?= htmlspecialchars($dd['created_at']) ?? '' ?></td>                    
                    <td>
                        <!-- DELETE (universal) -->
                        <form method="POST" action="/delete" style="display:inline;">

                            <input type="hidden" name="_method" value="DELETE">

                            <input type="hidden" name="table" value="student_stipend_records">

                            <input type="hidden" name="id" value="<?= $dd['id'] ?>">

                            <input type="hidden" name="redirect" value="/">

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
<?php 
require base_path('app/views/partials/footer.php');
?>