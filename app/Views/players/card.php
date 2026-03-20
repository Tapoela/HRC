<style>
    .wallet-card{
        width:320px;
        background:linear-gradient(135deg,#111,#333);
        border-radius:20px;
        color:white;
        padding:20px;
        text-align:center;
        box-shadow:0 10px 30px rgba(0,0,0,0.3);
    }

    .player-photo{
        width:90px;
        height:90px;
        border-radius:50%;
        border:3px solid white;
    }

    .qr{
        width:120px;
        margin-top:10px;
    }

    .membership{
        font-weight:bold;
        margin-top:10px;
    }
</style>
<div class="wallet-card">

    <div class="club">
        HRC Rugby Club
    </div>

    <img src="<?= base_url('uploads/profile/'.$user->photo) ?>"
         class="player-photo">

    <h2><?= esc($user->name) ?></h2>

    <p><?= ucfirst($user->role_id) ?></p>

    <div class="membership">
        <?= esc($user->membership_no) ?>
    </div>

    <?php if(isset($qr)): ?>
    <img src="data:image/png;base64,<?= $qr ?>" width="120">
    <?php endif; ?>

</div>