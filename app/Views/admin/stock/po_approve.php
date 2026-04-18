<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<style>

.approvalBar{
display:flex;
gap:20px;
margin-top:20px;
}

.approvalStep{
flex:1;
border:2px solid #ccc;
border-radius:6px;
padding:12px;
text-align:center;
}

.approvalStep.approved{
background:#16a34a;
color:white;
border-color:#16a34a;
}

.stepRole{
font-weight:600;
margin-bottom:5px;
}

.stepStatus{
font-size:13px;
}

</style>

<div class="container-fluid">

<div class="card">

<div class="card-header d-flex justify-content-between">

<h5>Purchase Order Approval</h5>

<a href="<?= site_url('admin/purchaseorders') ?>" class="btn btn-secondary btn-sm">
Back to PO List
</a>

</div>

<div class="card-body">

<!-- PO Info -->

<div class="row mb-4">

<div class="col-md-3">

<strong>PO Number</strong><br>
<?= $po['po_number'] ?>

</div>

<div class="col-md-3">

<strong>Date</strong><br>
<?= $po['order_date'] ?>

</div>

<div class="col-md-3">

<strong>Status</strong><br>

<?php if($po['approval_status']=='approved'): ?>

<span class="badge bg-success">Approved</span>

<?php else: ?>

<span class="badge bg-warning text-dark">Pending</span>

<?php endif ?>

</div>

</div>

<hr>

<!-- Approval Progress -->

<h6>Approval Workflow</h6>

<div class="approvalBar">

<?php foreach($approvals as $a): ?>

<div class="approvalStep <?= $a['status']=='approved'?'approved':'' ?>">

<div class="stepRole">

<?= $a['role_name'] ?>

</div>

<div class="stepStatus">

<?php if($a['status']=='approved'): ?>

Approved<br>
<small><?= $a['approved_at'] ?></small>

<?php else: ?>

Pending

<?php endif ?>

</div>

</div>

<?php endforeach ?>

</div>

<hr>

<!-- Signature Section -->

<h6>Signature</h6>

<canvas id="signature-pad"
width="400"
height="200"
style="border:1px solid #ccc"></canvas>

<br><br>

<?php
$userRoleId = session('role_id');

$nextApproval = null;
foreach($approvals as $a){
    if($a['status'] == 'pending'){
        $nextApproval = $a;
        break;
    }
}
?>

<?php if(
    $po['approval_status'] != 'approved' &&
    $nextApproval &&
    $nextApproval['approver_role_id'] == $userRoleId
): ?>

<button onclick="approvePO()" class="btn btn-success">
Approve & Sign
</button>

<button onclick="clearSignature()" class="btn btn-secondary">
Clear
</button>

<?php else: ?>

<?php if($po['approval_status'] == 'approved'): ?>

<span class="badge bg-success">
Fully Approved
</span>

<?php else: ?>

<span class="badge bg-secondary">
Waiting for <?= $nextApproval['approver_role'] ?? 'approval' ?>
</span>

<?php endif; ?>

<?php endif ?>

</div>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>

<script>

var canvas = document.getElementById("signature-pad");

var signaturePad = new SignaturePad(canvas);

function clearSignature(){

signaturePad.clear();

}

function approvePO(){

if(signaturePad.isEmpty()){

alert("Please provide a signature");

return;

}

showLoader();

fetch("<?= site_url('admin/purchaseorders/saveApproval') ?>",{

method:"POST",

headers:{
'Content-Type':'application/json'
},

body:JSON.stringify({

po_id:"<?= encode_id($po['id']) ?>",
signature:signaturePad.toDataURL()

})

})

.then(res => res.json())
.then(data => {

hideLoader();

console.log(data); // DEBUG

if(data.status === 'completed' || data.status === 'success'){

    window.location.href="<?= site_url('admin/purchaseorders') ?>";
    
} else {

alert(data.message || "Approval failed");

}

})
.catch(err => {

hideLoader();
console.log(err);
alert("Server error during approval");

});
}

</script>

<?= $this->endSection() ?>