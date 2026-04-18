<?php
$this->extend('layouts/admin');
$this->section('content');

$positions = require APPPATH . 'Config/RugbyPositions.php';
$sponsorFiles = glob(FCPATH . 'assets/Sponsers/*.{png,jpg,jpeg,webp,svg}', GLOB_BRACE);
?>

<style>
.rugby-lineup-bg {
    position: relative;
    width: 100%;
    max-width: 1000px;
    min-height: 1200px;
    margin: 0 auto;
    padding: 60px 40px 40px 40px; /* reduce top/bottom padding to bring field up */
    color: #fff;
    overflow: hidden;
    background:
        linear-gradient(rgba(0,0,0,0.12), rgba(0,0,0,0.12)),
        url('<?= base_url('assets/field-final.jpg') ?>') center center / cover no-repeat;
}

/* sponsor strips */
.sponsor-top,
.sponsor-bottom {
    position: absolute;
    left: 0;
    width: 100%;
    display: flex;
    justify-content: center;
    gap: 18px;
    flex-wrap: wrap;
    z-index: 10;
}

.sponsor-top { top: 10px; }
.sponsor-bottom { bottom: 10px; }

.sponsor-top img,
.sponsor-bottom img {
    max-height: 55px;
    max-width: 100px;
    object-fit: contain;
    background: rgba(255,255,255,0.9);
    padding: 4px;
    border-radius: 6px;
}

/* title */
.rugby-lineup-title {
    text-align: left;
    font-size: 1.8em;
    font-weight: 900;
    letter-spacing: 3px;
    margin-bottom: 8px;
    text-shadow: 0 3px 8px rgba(0,0,0,0.8);
    position: absolute;
    left: 60px;
    top: 280px; /* move title further down */
    z-index: 15;
}

.rugby-lineup-fixture {
    text-align: left;
    font-size: 1.1em;
    margin-bottom: 30px;
    text-shadow: 0 2px 6px rgba(0,0,0,0.8);
    position: absolute;
    left: 70px;
    top: 320px; /* move fixture info below the title */
    z-index: 15;
}

/* field layout */
.rugby-field-layout {
    position: relative;
    width: 100%;
    height: 850px;
    margin: 0 auto;
    padding-right: 180px;
    /* remove extra margin at the bottom */
}

.field-player {
    position: absolute;
    left: 0;
    top: 0;
    text-align: center;
    width: 130px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: flex-start;
    min-height: 180px; /* ensures enough space for image + text */
    transform: translate(-50%, 0); /* Only center horizontally */
}

.field-player img {
    width: 110px;
    height: 110px;
    border-radius: 50%;
    border: 4px solid #ffe082;
    object-fit: cover;
    background: #fff;
    margin-bottom: 6px;
}

.field-player .jersey {
    font-size: 1.3em;
    font-weight: 900;
    color: #fff;
    margin-top: 0;
    text-shadow: 0 2px 6px rgba(0,0,0,0.7);
}

.field-player .name {
    font-size: 1.1em;
    font-weight: 700;
    color: #fff;
    text-shadow: 0 2px 6px rgba(0,0,0,0.9);
    margin-top: 2px;
    word-break: break-word;
    max-width: 120px;
    overflow-wrap: break-word;
}

/* reserves as floating right column */
.rugby-lineup-reserves {
    position: absolute;
    top: 40%; /* move up to 74% from the top of the container */
    right: 50px;
    width: 210px;
    margin-top: 0;
    text-align: left;
    z-index: 20;
    background: rgba(0,0,0,0.18);
    padding: 12px 10px 12px 10px;
    border-radius: 10px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.18);
}

.rugby-lineup-reserves h4 {
    margin-bottom: 10px;
    text-align: left;
    font-size: 1.1em;
    font-weight: 700;
    color: #ffe082;
}

.rugby-lineup-reserves ul {
    display: none;
}

.rugby-lineup-reserves-table {
    width: 100%;
    border-collapse: collapse;
}

.rugby-lineup-reserves-table td {
    padding: 5px 4px;
    font-size: 0.98em;
    color: #fff;
    border-bottom: 1px solid rgba(255,255,255,0.08);
    white-space: nowrap;
}

.rugby-lineup-reserves-table tr:last-child td {
    border-bottom: none;
}

.rugby-lineup-reserves-table .res-num {
    font-weight: 700;
    color: #ffe082;
    width: 32px;
    text-align: right;
}

/* player positions */
.pos-1 { top: 98%; left: 20%; }
.pos-2 { top: 98%; left: 36%; }
.pos-3 { top: 98%; left: 52%; }

.pos-4 { top: 74%; left: 28%; }
.pos-5 { top: 74%; left: 45%; }

.pos-6 { top: 74%; left: 12%; }
.pos-7 { top: 74%; left: 60%; }
.pos-8 { top: 50%; left: 36%; }

.pos-9 { top: 40%; left: 48%; }
.pos-10 { top: 34%; left: 60%; }

.pos-11 { top: 0%; left: 12%; }
.pos-12 { top: 27%; left: 71%; }
.pos-13 { top: 22%; left: 83%; }
.pos-14 { top: 0%; left: 90%; }

.pos-15 { top: 0%; left: 50%; }

/* reserves */
.rugby-lineup-reserves {
    margin-top: 120px; /* was 48px */
    text-align: center;
}

.rugby-lineup-reserves h4 {
    margin-bottom: 15px;
}

.rugby-lineup-reserves ul {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-wrap: nowrap; /* force all reserves in one line */
    gap: 8px; /* reduce gap for compactness */
    justify-content: center;
    overflow-x: auto;
}

.rugby-lineup-reserves li {
    background: rgba(0,0,0,0.7);
    padding: 6px 8px; /* smaller, but more horizontal space */
    border-radius: 6px;
    width: auto; /* allow to shrink/grow with content */
    min-width: 70px;
    border: 1px solid rgba(255,255,255,0.18);
    font-size: 0.95em; /* slightly smaller, but readable */
    color: #fff;
    font-weight: 600;
    white-space: nowrap;
    text-align: center;
    box-shadow: 0 2px 6px rgba(0,0,0,0.12);
}

.rugby-lineup-bg,
.rugby-lineup-bg * {
    color: #ffe082 !important;
    text-shadow: 0 2px 6px rgba(0,0,0,0.7);
}

.rugby-lineup-reserves-table td {
    color: #ffe082 !important;
}
.rugby-lineup-reserves-table .res-num {
    color: #ffe082 !important;
}

.rugby-lineup-title,
.rugby-lineup-fixture,
.field-player .jersey,
.field-player .name,
.rugby-lineup-reserves h4 {
    color: #ffe082 !important;
}
</style>

<div class="rugby-lineup-bg">

    <!-- Sponsors Top -->
    <div class="sponsor-top">
        <?php foreach (array_slice($sponsorFiles, 0, ceil(count($sponsorFiles)/2)) as $file): ?>
            <img src="<?= base_url('assets/Sponsers/' . basename($file)) ?>" alt="Sponsor">
        <?php endforeach; ?>
    </div>

    <!-- Title -->
    <div class="rugby-lineup-title">
        <?= esc($team['team_name']) ?>
    </div>
    <div class="rugby-lineup-fixture">
        <?= esc($team['fixture_name']) ?>
        <br>
        Coaches: <?= esc($team['coach1_name']) ?><?= $team['coach2_name'] ? ' &amp; ' . esc($team['coach2_name']) : '' ?>
        <?php if (!empty($team['manager_name'])): ?>
        <br>Manager: <?= esc($team['manager_name']) ?>
        <?php endif; ?>
        <br>
        <span style="font-size:0.95em;"><?= date('Y-m-d H:i', strtotime($team['created_at'])) ?></span>
    </div>

    <!-- Field Players -->
    <div class="rugby-field-layout">
        <?php for (
            $i = 0; $i < 15; $i++):
            $player = $team['players'][$i] ?? ['name'=>'','id'=>'','photo'=>''];
            $photo = base_url('uploads/defaults/avatar.png');
            if (!empty($player['id'])) {
                $imgBase = FCPATH . 'uploads/players/' . $player['id'] . '/avatar';
                $imgUrlBase = 'uploads/players/' . $player['id'] . '/avatar';
                if (file_exists($imgBase . '.jpg')) {
                    $photo = base_url($imgUrlBase . '.jpg');
                } elseif (file_exists($imgBase . '.jpeg')) {
                    $photo = base_url($imgUrlBase . '.jpeg');
                } elseif (file_exists($imgBase . '.png')) {
                    $photo = base_url($imgUrlBase . '.png');
                }
            }
        ?>
            <div class="field-player pos-<?= $i + 1 ?>">
                <img src="<?= esc($photo) ?>" alt="Player">
                <div class="jersey">#<?= $i + 1 ?></div>
                <div class="name"><?= esc($player['name']) ?></div>
            </div>
        <?php endfor; ?>
    </div>

    <!-- Reserves -->
    <div class="rugby-lineup-reserves">
        <div id="download-btn-container" style="text-align:left; margin-bottom:10px;">
            <button id="download-jpeg" class="btn btn-success">
                Download as Image
            </button>
        </div>
        <h4>Reserves</h4>
        <table class="rugby-lineup-reserves-table">
            <?php for (
                $i = 15; 
                $i < count($positions); 
                $i++):
                $player = $team['players'][$i] ?? ['name'=>'','id'=>'','photo'=>''];
            ?>
            <tr>
                <td class="res-num">#<?= $i + 1 ?></td>
                <td><?= esc($player['name']) ?></td>
            </tr>
            <?php endfor; ?>
        </table>
    </div>

    <!-- Sponsors Bottom -->
    <div class="sponsor-bottom">
        <?php foreach (array_slice($sponsorFiles, ceil(count($sponsorFiles)/2)) as $file): ?>
            <img src="<?= base_url('assets/Sponsers/' . basename($file)) ?>" alt="Sponsor">
        <?php endforeach; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
<script>
document.getElementById('download-jpeg').addEventListener('click', function() {
    var btn = document.getElementById('download-btn-container');
    btn.style.display = 'none';
    const lineup = document.querySelector('.rugby-lineup-bg');
    html2canvas(lineup, {
        backgroundColor: null,
        useCORS: true,
        scale: 2
    }).then(function(canvas) {
        const link = document.createElement('a');
        link.download = 'team-lineup.jpg';
        link.href = canvas.toDataURL('image/jpeg', 0.95);
        link.click();
        btn.style.display = '';
    });
});
</script>

<?php $this->endSection(); ?>