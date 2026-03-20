<!DOCTYPE html>
<html>
	<head>

	<title>Rugby Club POS</title>

	<link rel="stylesheet"
	href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

		<style>

		body{
		background:#1b1b1b;
		color:white;
		}

		.category-btn{
		height:80px;
		font-size:18px;
		margin-bottom:10px;
		}

		.product-btn{
		height:100px;
		font-size:18px;
		margin:5px;
		}

		.sale-item{
		font-size:18px;
		border-bottom:1px solid #444;
		padding:5px;
		}

		</style>

	</head>

<body>

<div class="container-fluid">

	<div class="row">

		<div class="col-3">

			<h5>Drink Credits</h5>

			<div id="credits-list"></div>

		</div>

		<!-- Categories -->

		<div class="col-2">

			<h5>Categories</h5>

			<div id="categories"></div>

		</div>


		<!-- Products -->

		<div class="col-7">

			<h5>Products</h5>

			<div class="row" id="products"></div>

		</div>


		<!-- Sale Panel -->

		<div class="col-3">

			<h5>Current Sale</h5>

				<div id="sale-items"></div>

			<hr>

			<h3>Total: R<span id="sale-total">0</span></h3>

				<button class="btn btn-success w-100 mb-2"
				onclick="completeSale(1)">
				PAY CASH
				</button>

				<button class="btn btn-warning w-100 mb-2"
				onclick="openTabModal()">
				ADD TO TAB
				</button>

				<button class="btn btn-danger w-100"
				onclick="clearSale()">
				CLEAR
				</button>

		</div>

	</div>

</div>

<div class="modal fade" id="tabModal">

	<div class="modal-dialog">

		<div class="modal-content">

			<div class="modal-header">

			<h5>Open Tab</h5>

			</div>

			<div class="modal-body">

				<input id="tabName"
				class="form-control mb-2"
				placeholder="Name">

				<input id="tabPhone"
				class="form-control mb-2"
				placeholder="Phone">

			</div>

			<div class="modal-footer">

				<button class="btn btn-primary"
				onclick="createTab()">

				Open Tab

				</button>

			</div>

		</div>

	</div>

</div>

<script>

	setInterval(loadCredits,5000)

	let products = []
	let sale = []

	// load POS data
	function initPOS(){

		fetch('/pos/init')
		.then(res => res.json())
		.then(data =>{

		products = data.products

		renderProducts()

		})

	}

	/* Product Rendering */

	function renderProducts(){

		let container = document.getElementById("products")

		container.innerHTML = ""

		products.forEach(p=>{

		let col = document.createElement("div")
		col.className = "col-3"

		col.innerHTML = `
		<button class="btn btn-primary product-btn w-100"
		onclick="addProduct(${p.id},'${p.name}',${p.sell_price})">

		${p.name}
		<br>
		R${p.sell_price}

		</button>
		`

		container.appendChild(col)

		})

	}

	/* Adding items to Sale */

	function addProduct(id,name,price){

		let item = sale.find(i=>i.product_id==id)

		if(item){

		item.qty++

		}
		else{

		sale.push({
		product_id:id,
		name:name,
		price:price,
		qty:1
		})

		}

		renderSale()

	}

	/* Render Current Sale*/

	function renderSale(){

		let container = document.getElementById("sale-items")

		container.innerHTML=""

		let total = 0

		sale.forEach(item=>{

		total += item.price * item.qty

		let div = document.createElement("div")

		div.className="sale-item"

		div.innerHTML = `
		${item.name}
		x${item.qty}
		R${item.price * item.qty}
		`

		container.appendChild(div)

		})

		document.getElementById("sale-total").innerText = total

	}

	/* CLearing Sale */

	function clearSale(){

		sale=[]

		renderSale()

	}

	/* Completing Sale */

	function completeSale(paymentType){

		let total = document.getElementById("sale-total").innerText

		let payload = {

		sale:{
		location_id:1,
		payment_type_id:paymentType,
		user_id:1,
		total:total
		},

		items:sale.map(i=>({
		product_id:i.product_id,
		qty:i.qty,
		price:i.price
		}))

		}

		fetch('/pos/sale',{
		method:'POST',
		headers:{
		'Content-Type':'application/json'
		},
		body:JSON.stringify(payload)
		})
		.then(res=>res.json())
		.then(data=>{

		alert("Sale Completed")

		clearSale()

		})

	}

/* Opening Tab */

	function createTab(){

		let payload = {

		name:document.getElementById("tabName").value,
		phone:document.getElementById("tabPhone").value,
		location_id:1,
		opened_by:1

		}

		fetch('/pos/open-tab',{
		method:'POST',
		headers:{
		'Content-Type':'application/json'
		},
		body:JSON.stringify(payload)
		})
		.then(res=>res.json())
		.then(data=>{

		alert("Tab Created")

		})

	}


	/* Start POS */

	initPOS()

	/* load Credit */

	function loadCredits(){

		fetch('/pos/credits')
		.then(res=>res.json())
		.then(data=>{

		renderCredits(data)

		})

	}

	/* render credit */

	function renderCredits(credits){

		let container = document.getElementById("credits-list")

		container.innerHTML=""

		credits.forEach(c=>{

		let div = document.createElement("div")

		div.className="card mb-2"

		div.innerHTML = `
		<div class="card-body">

		<b>Special #${c.special_id}</b>

		<br>

		Remaining: ${c.remaining_drinks}

		<br><br>

		<button class="btn btn-success btn-sm"
		onclick="redeemDrink(${c.id},1)">
		Redeem 1
		</button>

		<button class="btn btn-warning btn-sm"
		onclick="redeemDrink(${c.id},2)">
		Redeem 2
		</button>

		</div>
		`

		container.appendChild(div)

		})

	}

	/* Redeem Drink */

	function redeemDrink(creditId,qty){

		fetch('/pos/redeem-credit',{
		method:'POST',
		headers:{
		'Content-Type':'application/json'
		},
		body:JSON.stringify({

		credit_id:creditId,
		qty:qty

		})
		})
		.then(res=>res.json())
		.then(data=>{

		alert("Drink redeemed")

		loadCredits()

		})

	}

</script>