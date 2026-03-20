<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="container-fluid">

<div class="card">

<div class="card-header d-flex justify-content-between">

<h5>Create Purchase Order</h5>

<a href="<?= site_url('admin/purchaseorders') ?>" class="btn btn-secondary btn-sm">
Back to PO List
</a>

</div>

<div class="card-body">

<div class="row mb-3">

<div class="col-md-4">

<label>Supplier</label>

<select id="supplier" class="form-control">

<option value="">Select Supplier</option>

<?php foreach($suppliers as $s): ?>

<option value="<?= $s['id'] ?>">
<?= esc($s['name']) ?>
</option>

<?php endforeach ?>

</select>

</div>

</div>

<hr>

<table class="table table-bordered" id="poTable">

<thead>

<tr>
<th width="40%">Product</th>
<th width="15%">Qty</th>
<th width="20%">Cost Price</th>
<th width="20%">Total</th>
<th width="5%"></th>
</tr>

</thead>

<tbody></tbody>

</table>

<button class="btn btn-primary mb-3" onclick="addRow()">
Add Product
</button>

<h5 class="text-end">
Total: <span id="grandTotal">0.00</span>
</h5>

<button class="btn btn-success" onclick="savePO()">
Save Purchase Order
</button>

</div>

</div>

</div>

<script>

let products = <?= json_encode($products ?? []) ?>;

function addRow(){

let row = document.createElement("tr");

let productOptions = '<option value="">Select Product</option>';

products.forEach(p=>{
productOptions += `<option value="${p.id}">${p.name}</option>`;
});

row.innerHTML = `

<td>
<select class="form-control product">
${productOptions}
</select>
</td>

<td>
<input type="number" class="form-control qty" value="1" min="1">
</td>

<td>
<input type="number" class="form-control price" value="0" step="0.01">
</td>

<td class="rowTotal">0.00</td>

<td>
<button class="btn btn-danger btn-sm" onclick="removeRow(this)">X</button>
</td>

`;

document.querySelector("#poTable tbody").appendChild(row);

attachEvents(row);

}

function removeRow(btn){

btn.closest("tr").remove();

calculateTotals();

}

function attachEvents(row){

row.querySelector(".qty").addEventListener("input",calculateTotals);
row.querySelector(".price").addEventListener("input",calculateTotals);

}

function calculateTotals(){

let grandTotal = 0;

document.querySelectorAll("#poTable tbody tr").forEach(row=>{

let qty = parseFloat(row.querySelector(".qty").value) || 0;
let price = parseFloat(row.querySelector(".price").value) || 0;

let total = qty * price;

row.querySelector(".rowTotal").innerText = total.toFixed(2);

grandTotal += total;

});

document.getElementById("grandTotal").innerText = grandTotal.toFixed(2);

}

function savePO(){

let supplier = document.getElementById("supplier").value;

if(!supplier){

alert("Select a supplier");

return;

}

let items = [];

document.querySelectorAll("#poTable tbody tr").forEach(row=>{

let product = row.querySelector(".product").value;
let qty = row.querySelector(".qty").value;
let price = row.querySelector(".price").value;

if(product){

items.push({
product_id: product,
qty: qty,
price: price
});

}

});

if(items.length === 0){

alert("Add at least one product");

return;

}

showLoader();

fetch("<?= site_url('admin/purchaseorders/store') ?>",{
method:"POST",
headers:{ 'Content-Type':'application/json' },
body:JSON.stringify({
supplier_id:supplier,
items:items
})
})
.then(res=>{
if(!res.ok) throw new Error("Server error");
return res.json();
})
.then(data=>{
if(data.status === 'success'){
alert("PO Created: " + data.po_number);
window.location.href="<?= site_url('admin/purchaseorders') ?>";
}else{
alert(data.message);
}
})
.catch(err=>{
console.error(err);
alert("Server error while creating PO");
hideLoader();
});
}

document.addEventListener("DOMContentLoaded", function(){
    addRow();
});

</script>

<?= $this->endSection() ?>