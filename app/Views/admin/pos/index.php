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
		height:120px;
		font-size:20px;
		font-weight:bold;
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

		<div class="row">

			<div class="col-2">
				<h5>Drink Credits</h5>
				<div id="credits-list"></div>
			</div>

			<div class="col-2">
				<h5>Categories</h5>
				<div id="categories"></div>
			</div>

			<div class="col-5">
				<h5>Products</h5>

				<input type="text"
				id="productSearch"
				class="form-control mb-2"
				placeholder="Search product..."
				onkeyup="searchProducts()">

				<div class="row" id="products"></div>
			</div>

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

			    <!-- ✅ EXISTING OPEN TABS -->
			    <h6>Open Tabs</h6>
			    <div id="tabList" class="mb-3"></div>

			    <hr>

			    <!-- ✅ CREATE NEW TAB -->
			    <h6>Create New Tab</h6>

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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>

	const BASE_URL = "<?= rtrim(base_url(), '/') ?>/";

	function openTabModal(){

		let modal = new bootstrap.Modal(
		document.getElementById('tabModal')
		)

		modal.show()

	}

	let categories = []

		function loadCredits(){

	    fetch(BASE_URL + "pos/credits")
	    .then(res=>res.json())
	    .then(data=>{
	        renderCredits(data)
	    })

	}

	let products = []
	let sale = []

	// load POS data
	function initPOS(){

		document.getElementById("productSearch").focus()

		fetch(BASE_URL + "pos/init")
		.then(res => res.json())
		.then(data =>{

		products = data.products
		categories = data.categories
		tabs = data.tabs

		renderCategories()
		renderProducts()
		renderTabs()

		})

	}

	document.getElementById("productSearch").addEventListener("keypress", function(e){

		if(e.key === "Enter"){

		let term = this.value.toLowerCase()

			let filtered = products.filter(p =>
			p.name.toLowerCase().includes(term)
			)

			if(filtered.length > 0){

				addProduct(filtered[0].id, filtered[0].name, filtered[0].sell_price)

				this.value=""

				renderProducts()

			}

		}

	})

	/*Search Function*/

	function searchProducts(){

		let term = document.getElementById("productSearch").value.toLowerCase()

		let filtered = products.filter(p =>
		p.name.toLowerCase().includes(term)
		)

		renderProducts(filtered)

	}


	/* reneder catergory buttons */

	function renderCategories(){

	    let container = document.getElementById("categories")

	    container.innerHTML=""

	    // ✅ Add "All" button FIRST
	    let allBtn = document.createElement("button")
	    allBtn.className = "btn btn-dark w-100 category-btn"
	    allBtn.innerText = "All"
	    allBtn.onclick = function(){
	        renderProducts()
	    }
	    container.appendChild(allBtn)

	    // ✅ Then add category buttons
	    categories.forEach(c => {

	        let btn = document.createElement("button")
	        btn.className = "btn btn-secondary w-100 mb-1 text-start"
	        btn.innerText = c.name

	        btn.onclick = function(){
	            filterProducts(c.id)
	        }

	        container.appendChild(btn)

	    })
	}

	/* filter products */

	function filterProducts(categoryId){

		let filtered = products.filter(p => p.category_id == categoryId)

		renderProducts(filtered)

	}

	function renderProducts(productList = products){

		let container = document.getElementById("products")

		container.innerHTML=""

		productList.forEach(p=>{

		let col = document.createElement("div")
		col.className = "col-3"

		col.innerHTML = `
		<button class="btn btn-primary product-btn w-100"
		onclick="addProduct(${p.id}, ${JSON.stringify(p.name)}, ${p.sell_price})">

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

		if(!id || !price){
	        console.error("Invalid product detected", {id, name, price})
	        return
	    }

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

	    sale.forEach((item,index)=>{

	        total += item.price * item.qty

	        let div = document.createElement("div")
	        div.className="sale-item d-flex justify-content-between align-items-center"

	        div.innerHTML = `
			    <div>
			        ${item.name}<br>
			        x${item.qty} - R${item.price * item.qty}
			    </div>

			    <div>
			        <button class="btn btn-sm btn-success me-1"
			            onclick="increaseQty(${index})">+</button>

			        <button class="btn btn-sm btn-warning me-1"
			            onclick="decreaseQty(${index})">-</button>

			        <button class="btn btn-sm btn-danger"
			            onclick="removeItem(${index})">X</button>
			    </div>
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

		if(sale.length === 0){
			alert("No items selected")
			return
		}

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

		fetch(BASE_URL + "pos/sale",{
		method:'POST',
		headers:{
		'Content-Type':'application/json'
		},
		body:JSON.stringify(payload)
		})
		.then(res => res.json())
		.then(data => {

		    if(data.status === 'error'){
		        alert(data.message)
		        return
		    }

		    alert("Sale Completed")

		    clearSale()
		    loadCredits()

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

	    fetch(BASE_URL + "pos/open-tab",{
	        method:'POST',
	        headers:{ 'Content-Type':'application/json' },
	        body:JSON.stringify(payload)
	    })
	    .then(res=>res.json())
	    .then(data=>{

	        // ✅ Now send sale linked to tab
	        completeSaleOnTab(data.tab_id)
	        loadTabs()

	    })

	}


	/* Start POS */

	initPOS()

	/* load Credit */

	function loadCredits(){
	    fetch(BASE_URL + "pos/credits")
	    .then(res=>res.json())
	    .then(data=>{
	        renderCredits(data)
	    })
	}

	// run every 5 seconds
	setInterval(loadCredits, 5000)
	setInterval(renderTabs, 5000)

	/* render credit */

	function renderCredits(credits){

		let container = document.getElementById("credits-list")

		container.innerHTML=""

		if(!credits) return

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

		fetch(BASE_URL + "pos/redeem-credit",{
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

	function removeItem(index){
	    sale.splice(index,1)
	    renderSale()
	}

	function decreaseQty(index){

	    if(sale[index].qty > 1){
	        sale[index].qty--
	    } else {
	        // remove if qty becomes 0
	        sale.splice(index,1)
	    }

	    renderSale()
	}

	function increaseQty(index){
	    sale[index].qty++
	    renderSale()
	}

	function completeSaleOnTab(tabId){

	    if(sale.length === 0){
	        alert("No items selected")
	        return
	    }

	    let total = document.getElementById("sale-total").innerText

	    let payload = {
	        sale:{
	            location_id:1,
	            payment_type_id:null,
	            user_id:1,
	            total:total,
	            tab_id:tabId,
	            status:'tab'
	        },
	        items:sale.map(i=>({
	            product_id:i.product_id,
	            qty:i.qty,
	            price:i.price
	        }))
	    }

	    fetch(BASE_URL + "pos/sale",{
	        method:'POST',
	        headers:{ 'Content-Type':'application/json' },
	        body:JSON.stringify(payload)
	    })
	    .then(res => res.json())
	    .then(data => {

	        if(data.status === 'error'){
	            alert(data.message)
	            return
	        }

	        alert("Added to Tab")

	        clearSale()
	        loadCredits()
	        loadTabs()

	    })
	}

	function loadTabs(){

	    Promise.all([
	        fetch(BASE_URL + "pos/init").then(res=>res.json()),
	        fetch(BASE_URL + "pos/tab-totals").then(res=>res.json())
	    ])
	    .then(([initData, totals]) => {

	        tabs = initData.tabs

	        let map = {}
	        totals.forEach(t => map[String(t.tab_id)] = Number(t.total))

	        renderTabsWithTotals(map)

	    })
	}

	let tabs = []

	function renderTabs(){

	    fetch(BASE_URL + "pos/tab-totals")
	    .then(res=>res.json())
	    .then(totals=>{

	        if(!Array.isArray(totals)){
	            console.error("Invalid totals response", totals)
	            return
	        }

	        let map = {}

	        // ✅ FIX: normalize keys + values
	        totals.forEach(t => map[String(t.tab_id)] = Number(t.total))

	        let container = document.getElementById("tabList")
	        container.innerHTML = ""

	        tabs.forEach(t => {

	            let total = map[String(t.id)] || 0

	            let div = document.createElement("div")
	            div.className = "d-flex justify-content-between mb-1"

	            div.innerHTML = `
	                <button class="btn flex-grow-1 me-1 tab-btn text-start"
	                    onclick="completeSaleOnTab(${t.id})">
	                    ${t.name} - R${total}
	                </button>

	                <button class="btn btn-success btn-sm"
	                    onclick="payTab(${t.id})">
	                    Pay
	                </button>
	            `

	            container.appendChild(div)

	        })

	        	console.log("TABS:", tabs)
				console.log("TOTALS:", totals)
				console.log("MAP:", map)

	    })
	}

	function payTab(tabId){

	    fetch(BASE_URL + "pos/pay-tab",{
	        method:'POST',
	        headers:{ 'Content-Type':'application/json' },
	        body:JSON.stringify({
	            tab_id:tabId,
	            payment_type_id:1
	        })
	    })
	    .then(res=>res.json())
	    .then(data=>{
	        alert("Tab Paid")
	        loadTabs()
	    })

	}



</script>