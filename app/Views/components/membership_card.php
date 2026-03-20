<!DOCTYPE html>
<html>
    <head>

    <title>Membership Card</title>

    <link rel="stylesheet" href="<?= base_url('assets/adminlte/css/card.css') ?>">

    </head>

    <body>

        <div class="print-area">

            <div class="membership-card">

                <div class="club-header">

                    <img src="<?= base_url('assets/logos/BergeLogo.png') ?>" class="club-logo">

                    <span class="club-name">HRC Rugby Club</span>

                </div>

                <br>

                <div class="player-section">

                    <img src="<?= base_url('uploads/'.$user->photo) ?>" class="player-photo">

                    <div class="player-info">

                        <div class="player-name">
                            <?= esc($user->pname) ?> <?= esc($user->surname) ?>
                        </div>

                        <div class="position-badge">
                            <?= esc($user->position_name) ?>
                        </div>

                        <div class="player-role">
                            Role: <?= esc($user->name) ?>
                        </div>

                        <div class="medical-info">
                            Medical Aid: <?= esc($user->med_aid) ?><br>
                            Medical No: <?= esc($user->med_no) ?><br>
                            Spouse: <?= esc($user->spouse_name) ?>
                        </div>

                    </div>

                </div>

                <div class="member-id">

                    ID: <?= esc($user->membership_no) ?>

                </div>

                <img src="<?= $qr ?>" class="qr">

            </div>

        </div>

        <div class="print-btn">

            <button onclick="window.print()">Print Card</button>

        </div>

    </body>
</html>