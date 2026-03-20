<div class="card-sheet">

<?php foreach($users as $user): ?>

<?= view('components/membership_card', [
    'user'=>$user,
    'qr'=>$user->qr
]) ?>

<?php endforeach; ?>

</div>