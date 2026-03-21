<?php
require base_path('app/views/partials/head.php');
require base_path('app/views/partials/nav.php');

$periods = $periods ?? [];
?>

<div class="container mt-4">
    <div class="card shadow">

        <div class="card-header  bg-dark text-white d-flex justify-content-between align-items-center">
            <span>Stipendiju periodi</span>

            <!-- CREATE BUTTON -->
            <a href="/period/create" class="btn btn-success btn-sm">
                + Pievienot periodu
            </a>
        </div>

        <div class="card-body">

            <?php if (!empty($periods)): ?>
                <div style="overflow-x:auto;">
                    <table class="table table-striped table-bordered table-sm">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Gads</th>
                                <th>Periods</th>
                                <th>Perioda grupa</th>
                                <th>Izveidots</th>
                                <th>Parādīts</th>
                                <th>darbības</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($periods as $p): ?>
                                <tr>
                                    <td><?= htmlspecialchars($p['id']) ?></td>
                                    <td><?= htmlspecialchars($p['year']) ?></td>
                                    <td><?= htmlspecialchars($p['period']) ?></td>
                                    <td><?= htmlspecialchars($p['period_group']) ?></td>
                                    <td><?= htmlspecialchars($p['created_at']) ?></td>
                                    <td>
                                        <?= htmlspecialchars($p['period'] . ' (' . $p['year'] . ')') ?>
                                    </td>
                                    <td>
                                        <a href="/period/edit?id=<?= $p['id'] ?>" class="btn btn-sm btn-warning">Labot</a>
                                        <!-- DELETE (universal) -->
                                        <form method="POST" action="/delete" style="display:inline;">

                                            <input type="hidden" name="_method" value="DELETE">

                                            <input type="hidden" name="table" value="stipend_periods">

                                            <input type="hidden" name="id" value="<?= $p['id'] ?>">

                                            <input type="hidden" name="redirect" value="/period">

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
            <?php else: ?>
                <div class="alert alert-warning">
                    Nav atrastu periodu.
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<?php
require base_path('app/views/partials/footer.php');
?>