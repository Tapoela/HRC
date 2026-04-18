<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<div class="container-fluid">
    <h3>Sales Graph</h3>
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
    <canvas id="salesChart" height="100"></canvas>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const chartData = <?= json_encode($chartData) ?>;
const ctx = document.getElementById('salesChart').getContext('2d');
const salesChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: chartData.labels,
        datasets: [{
            label: 'Total Sales (R)',
            data: chartData.totals,
            backgroundColor: 'rgba(54, 162, 235, 0.5)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: { beginAtZero: true }
        }
    }
});
</script>
<?= $this->endSection() ?><?php if (!in_array((int)session('role_id'), [1,4,5,6])): ?>
    <div class="alert alert-danger mt-4">You do not have permission to view this report.</div>
    <?php return; endif; ?>
