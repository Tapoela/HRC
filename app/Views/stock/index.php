<!DOCTYPE html>
<html>

	<head>

		<title>Stock Management</title>

		<link rel="stylesheet"
		href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

	</head>

	<body>

		<div class="container">

			<h3>Receive Stock</h3>

			<table class="table">

				<thead>
					<tr>
					<th>Product</th>
					<th>Qty</th>
					</tr>
				</thead>

				<tbody>

					<?php foreach($products as $p): ?>

					<tr>

					<td><?= $p['name'] ?></td>

					<td>

					<input
					type="number"
					class="form-control"
					id="product_<?= $p['id'] ?>"
					value="0">

					</td>

					</tr>

					<?php endforeach ?>

				</tbody>

			</table>

			<button
			class="btn btn-success"
			onclick="receiveStock()">

			Receive Stock

			</button>

		</div>

		<div class="container mt-5">

			<h3>Transfer Stock</h3>

			<select id="product" class="form-control mb-2">

				<?php foreach($products as $p): ?>

				<option value="<?= $p['id'] ?>">
				<?= $p['name'] ?>
				</option>

				<?php endforeach ?>

			</select>

			<input
			type="number"
			id="qty"
			class="form-control mb-2"
			placeholder="Quantity">

			<button
			class="btn btn-primary"
			onclick="transferStock()">

			Transfer to Field

			</button>

		</div>

	</body>
</html>

<script>

	function receiveStock(){

		let items=[]

		document.querySelectorAll("input").forEach(input=>{

		let qty=parseInt(input.value)

		if(qty>0){

		let productId=input.id.replace("product_","")

		items.push({

		product_id:productId,
		qty:qty

		})

		}

		})

		fetch('/stock/receive',{
		method:'POST',
		headers:{
		'Content-Type':'application/json'
		},
		body:JSON.stringify({

		location_id:1,
		items:items

		})
		})
		.then(res=>res.json())
		.then(data=>{

		alert("Stock received")

		})

	}

	/* Transfer */

	function transferStock(){

		fetch('/stock/transfer',{
		method:'POST',
		headers:{
		'Content-Type':'application/json'
		},
		body:JSON.stringify({

		product_id:document.getElementById("product").value,
		qty:document.getElementById("qty").value,
		from_location:1,
		to_location:2

		})
		})
		.then(res=>res.json())
		.then(data=>{

		alert("Stock transferred")

		})

	}

</script>