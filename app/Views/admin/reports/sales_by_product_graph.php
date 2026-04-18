<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<div class="container-fluid">
    <h3>Sales by Product (Graph)</h3>
    <?php if (!in_array((int)session('role_id'), [1,4,5,6])): ?>
        <div class="alert alert-danger mt-4">You do not have permission to view this report.</div>
    <?php return; endif; ?>
    <form method="get" class="row mb-3">
        <div class="col-md-3">
            <label>From Date</label>
            <input type="date" name="from" value="<?= esc($from ?? '') ?>" class="form-control">
        </div>
        <div class="col-md-3">
            <label>To Date</label>
            <input type="date" name="to" value="<?= esc($to ?? '') ?>" class="form-control">
        </div>
        <div class="col-md-3 align-self-end">
            <button class="btn btn-primary">Show Graph</button>
        </div>
    </form>
    <canvas id="productChart" height="100"></canvas>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const chartData = <?= json_encode($chartData) ?>;
const ctx = document.getElementById('productChart').getContext('2d');
const productChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: chartData.labels,
        datasets: [{
            label: 'Total Sold',
            data: chartData.totals,
            backgroundColor: 'rgba(255, 193, 7, 0.7)',
            borderColor: 'rgba(255, 193, 7, 1)',
            borderWidth: 1
        }]
    },
    options: {
        indexAxis: 'y',
        scales: {
            x: { beginAtZero: true }
        }
    }
});
</script>
<?= $this->endSection() ?>
