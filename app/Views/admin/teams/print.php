<?php
// Admin Team Selection Print View (Custom ScoreSheet Table with Referee Table, A4)
$positions = require APPPATH . 'Config/RugbyPositions.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Score Sheet - <?= esc($team['team_name']) ?></title>
    <style>
        @page { size: A4 portrait; margin: 4mm 2mm 4mm 2mm; }
        body { font-family: Arial, sans-serif; margin: 0; }
        .sheet-header { text-align: center; margin-top: 2px; margin-bottom: 2px; }
        .sheet-title { font-size: 1.7em; font-weight: bold; margin-bottom: 0; }
        .sheet-union { font-size: 1em; font-weight: bold; margin-bottom: 0; }
        .sheet-place { font-size: 0.95em; margin-bottom: 2px; }
        .sheet-meta { font-size: 0.92em; margin-bottom: 0; text-align: left; }
        .sheet-meta strong { min-width: 60px; display: inline-block; }
        .sheet-meta-wrap { display: flex; justify-content: space-between; margin-bottom: 2px; }
        .sheet-meta-left { max-width: 60%; }
        .sheet-meta-right { text-align: right; font-size: 0.95em; }
        .team-table { width: 100%; border-collapse: collapse; margin-top: 4px; }
        .team-table th, .team-table td { border: 1px solid #222; padding: 1px 0; font-size: 0.85em; text-align: center; }
        .team-table th { background: #f0f0f0; }
        .team-table .name, .team-table .oppo { min-width: 50px; max-width: 70px; font-size: 0.85em; }
        .team-table .dcol, .team-table .pcol, .team-table .ccol, .team-table .tcol { min-width: 18px; font-size: 0.9em; }
        .team-table .subcol { min-width: 14px; font-size: 0.85em; line-height: 1.1; }
        .team-table .subcol .s { font-weight: bold; font-size: 0.95em; display: block; }
        .team-table .subcol .ub { font-size: 0.8em; display: block; margin-top: -2px; }
        .reserves-title { margin-top: 4px; font-weight: bold; font-size: 0.95em; }
        .ref-table { width: 100%; border-collapse: collapse; margin-top: 4px; margin-bottom: 0; }
        .ref-table th, .ref-table td { border: 1px solid #222; font-size: 0.95em; text-align: center; }
        .ref-table th { background: #f0f0f0; padding: 2px 0; }
        .ref-table td { height: 10px; padding: 0; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>
    <div class="sheet-header">
        <div class="sheet-title">SCORE SHEET</div>
        <div class="sheet-union">VALKE RUGBY UNION</div>
    </div>
    <div class="sheet-meta-wrap">
        <div class="sheet-meta-left">
            <div class="sheet-meta"><strong>Fixture:</strong> <?= esc($team['fixture_name']) ?></div>
            <div class="sheet-meta"><strong>Team:</strong> <?= esc($team['team_name']) ?></div>
            <div class="sheet-meta"><strong>Date/Time:</strong> <?= date('Y-m-d H:i', strtotime($team['created_at'])) ?></div>
            <div class="sheet-meta"><strong>Coaches:</strong> <?= esc($team['coach1_name']) ?><?= $team['coach2_name'] ? ' &amp; ' . esc($team['coach2_name']) : '' ?></div>
            <?php if (!empty($team['manager_name'])): ?>
            <div class="sheet-meta"><strong>Team Manager:</strong> <?= esc($team['manager_name']) ?></div>
            <?php endif; ?>
        </div>
        <div class="sheet-meta-right"></div>
    </div>
    <table class="ref-table">
        <thead>
            <tr><th>Referee</th></tr>
        </thead>
        <tbody>
            <tr><td style="height:14px;"></td></tr>
        </tbody>
    </table>
    <table class="team-table">
        <thead>
            <tr>
                <th class="subcol"><span class="s">S</span><span class="ub">U<br>B</span></th>
                <th class="dcol">D</th>
                <th class="pcol">P</th>
                <th class="ccol">C</th>
                <th class="tcol">T</th>
                <th class="name">Player Name</th>
                <th>Pos</th>
                <th class="oppo">Player Name</th>
                <th class="tcol">T</th>
                <th class="ccol">C</th>
                <th class="pcol">P</th>
                <th class="dcol">D</th>
                <th class="subcol"><span class="s">S</span><span class="ub">U<br>B</span></th>
            </tr>
        </thead>
        <tbody>
            <?php for ($i = 0; $i < 15; $i++): ?>
            <tr>
                <td class="subcol"></td>
                <td class="dcol"></td>
                <td class="pcol"></td>
                <td class="ccol"></td>
                <td class="tcol"></td>
                <td class="name"><?= isset($team['players'][$i]) ? esc($team['players'][$i]) : '' ?></td>
                <td><?= $i + 1 ?></td>
                <td class="oppo"></td>
                <td class="tcol"></td>
                <td class="ccol"></td>
                <td class="pcol"></td>
                <td class="dcol"></td>
                <td class="subcol"></td>
            </tr>
            <?php endfor; ?>
        </tbody>
    </table>
    <div class="reserves-title">Replacements</div>
    <table class="team-table">
        <thead>
            <tr>
                <th class="subcol"><span class="s">S</span><span class="ub">U<br>B</span></th>
                <th class="dcol">D</th>
                <th class="pcol">P</th>
                <th class="ccol">C</th>
                <th class="tcol">T</th>
                <th class="name">Player Name</th>
                <th>Pos</th>
                <th class="oppo">Player Name</th>
                <th class="tcol">T</th>
                <th class="ccol">C</th>
                <th class="pcol">P</th>
                <th class="dcol">D</th>
                <th class="subcol"><span class="s">S</span><span class="ub">U<br>B</span></th>
            </tr>
        </thead>
        <tbody>
            <?php for ($i = 15; $i < count($positions); $i++): ?>
            <tr>
                <td class="subcol"></td>
                <td class="dcol"></td>
                <td class="pcol"></td>
                <td class="ccol"></td>
                <td class="tcol"></td>
                <td class="name"><?= isset($team['players'][$i]) ? esc($team['players'][$i]) : '' ?></td>
                <td><?= $i + 1 ?></td>
                <td class="oppo"></td>
                <td class="tcol"></td>
                <td class="ccol"></td>
                <td class="pcol"></td>
                <td class="dcol"></td>
                <td class="subcol"></td>
            </tr>
            <?php endfor; ?>
        </tbody>
    </table>
    <!-- Signature row as table for consistent print layout, with ample space for signing -->
    <table style="width:100%; margin-top:32px; table-layout:fixed; border-collapse:collapse; page-break-inside:avoid;">
        <tr>
            <td style="width:33.33%; border-top:1.2px solid #222; text-align:center; padding-top:36px; font-size:0.9em; white-space:nowrap; height:56px;">Coach Signature</td>
            <td style="width:33.33%; border-top:1.2px solid #222; text-align:center; padding-top:36px; font-size:0.9em; white-space:nowrap; height:56px;">Team Manager Signature</td>
            <td style="width:33.33%; border-top:1.2px solid #222; text-align:center; padding-top:36px; font-size:0.9em; white-space:nowrap; height:56px;">Referee Signature</td>
        </tr>
    </table>
</body>
</html>
