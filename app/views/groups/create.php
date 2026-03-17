<?php
require base_path('app/views/partials/head.php');
require base_path('app/views/partials/nav.php');
?>

<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-success text-white">
            Pievienot priekšmetu
        </div>
        <div class="card-body">
            <form method="POST" action="/groups/create">
                <div class="mb-3">
                    <label class="form-label">
                        Priekšmeta nosaukums
                    </label>
                    <input type="text" name="group_name" class="form-control" required>
                </div>

                <!-- Buttons -->
                <button type="submit"
                        class="btn btn-success">
                    Saglabāt
                </button>
                <a href="/groups"
                   class="btn btn-secondary">
                    Atpakaļ
                </a>
            </form>
        </div>
    </div>
</div>

<?php 
require base_path('app/views/partials/footer.php');
?>