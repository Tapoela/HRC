<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="container-fluid">

    <div class="card">

        <div class="card-header">

            <h3 class="card-title">Product Categories</h3>

            <a href="<?= site_url('admin/categories/create') ?>" class="btn btn-primary float-right">
            Add Category
            </a>

        </div>

        <div class="card-body">

            <table class="table table-bordered">

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                    </tr>
                </thead>

                <tbody>

                <?php foreach($categories as $c): ?>

                <tr>
                    <td><?= $c['id'] ?></td>
                    <td><?= esc($c['name']) ?></td>
                </tr>

                <?php endforeach ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<?= $this->endSection() ?>