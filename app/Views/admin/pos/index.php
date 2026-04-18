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
        <div class="col-12 mb-2">
            <span id="currentLocationDisplay" class="badge bg-secondary me-2">Location: <span id="currentLocationName">-</span></span>
            <button class="btn btn-sm btn-outline-light" onclick="showLocationModal()">Change Location</button>
        </div>
    </div>
    <div class="row">

		<div class="row">

			<div class="col-2">
				<h5>Drink Credits</h5>
				<button class="btn btn-primary w-00 mb-2" onclick="openCreditModal()">Add Credit</button>
				<div id="creditListContainer" style="max-height: 350px; overflow-y: auto;"></div>
			</div>


			<div class="col-2">
				<h5>Categories</h5>
				<div id="categories"></div>
			</div>

			<div class="col-5">
				<!-- Specials Section moved here -->
				<h5>Specials</h5>
				<div id="specials-list" class="mb-3"></div>

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

			<button class="btn btn-info w-100 mb-2"
				onclick="openCardRefModal()">
				PAY CARD <span style="font-size:18px;">💳</span>
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

<!-- Credit Modal -->
<div class="modal fade" id="creditModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Add Drink Credit</h5>
            </div>
            <div class="modal-body">
                <form id="addCreditForm">
                    <input class="form-control mb-2" id="creditName" placeholder="Name" required>
                    <input class="form-control mb-2" id="creditPhone" placeholder="Phone" required>
                    <input class="form-control mb-2" id="creditQty" type="number" min="1" placeholder="Drinks Bought" required>
                    <button class="btn btn-primary w-100" type="submit">Add Credit</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Card Reference Modal -->
<div class="modal fade" id="cardRefModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Card Payment Reference</h5>
            </div>
            <div class="modal-body">
                <input class="form-control mb-2" id="cardRefInput" placeholder="Card Reference / Slip Number" required>
                <button class="btn btn-info w-100" onclick="submitCardPayment()">Submit Payment</button>
            </div>
        </div>
    </div>
</div>

<!-- Pay Tab Modal -->
<div class="modal fade" id="payTabModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Pay Tab</h5>
            </div>
            <div class="modal-body">
                <button class="btn btn-success w-100 mb-2" id="payTabCashBtn">Pay Cash</button>
                <button class="btn btn-info w-100" id="payTabCardBtn">Pay Card <span style="font-size:18px;">💳</span></button>
            </div>
        </div>
    </div>
</div>

<!-- Confirm Pay Tab Modal -->
<div class="modal fade" id="confirmPayTabModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Confirm Payment</h5>
            </div>
            <div class="modal-body">
                <p id="confirmPayTabText">Are you sure you want to pay this tab?</p>
                <button class="btn btn-secondary w-100 mb-2" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-primary w-100" id="confirmPayTabBtn">Yes, Pay</button>
            </div>
        </div>
    </div>
</div>

<!-- Location Select Modal -->
<div class="modal fade" id="locationModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Select Location</h5>
            </div>
            <div class="modal-body">
                <select class="form-select mb-2" id="locationSelect"></select>
                <input class="form-control mb-2" id="newLocationInput" placeholder="Add new location...">
                <button class="btn btn-primary w-100 mb-2" onclick="addLocation()">Add Location</button>
                <button class="btn btn-success w-100" onclick="confirmLocation()">Confirm</button>
            </div>
        </div>
    </div>
</div>

<!-- Specials Modal (dynamic, used in production) -->
<div class="modal fade" id="specialsModal" tabindex="-1" aria-labelledby="specialsModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="specialsModalLabel">Sell Special</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="specials-modal-body">
        <!-- Dynamic content will be loaded here -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-success" id="sell-special-btn">Sell Special</button>
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

	function openCreditModal() {
	    let modal = new bootstrap.Modal(document.getElementById('creditModal'));
	    modal.show();
	}

	function openCardRefModal(isTab) {
	    window._payingTab = !!isTab;
	    let modal = new bootstrap.Modal(document.getElementById('cardRefModal'));
	    document.getElementById('cardRefInput').value = '';
	    modal.show();
	}

	function openPayTabModal(tabId) {
	    window._payTabId = tabId;
	    let modal = new bootstrap.Modal(document.getElementById('payTabModal'));
	    modal.show();
	}

	let categories = []

	let products = []
	let sale = []
	let tabs = []

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
		.catch(err => console.error("Error initializing POS:", err))

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


	/* render category buttons */

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
	    container.innerHTML = ""

	    productList.forEach(p => {

	        let col = document.createElement("div")
	        col.className = "col-3"

	        let btn = document.createElement("button")
	        btn.className = "btn btn-primary product-btn w-100"

	        btn.innerHTML = `
	            ${p.name}
	            <br>
	            R${p.sell_price}
	        `

	        // ✅ p is valid HERE
	        btn.addEventListener("click", function(){
	            addProduct(p.id, p.name, p.sell_price)
	        })

	        col.appendChild(btn)
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

	        let details = '';
	        if(item.special_id && item.selections){
	            details = '<ul style="font-size:14px;">';
	            item.selections.forEach((sel, i) => {
	                // Show qty for each slot, and support both alcohol/mix and generic slot systems
	                if(sel.alcohol || sel.mix){
	                    details += `<li>${sel.qty ? 'x'+sel.qty+' ' : ''}Alcohol: ${sel.alcohol ? sel.alcohol.name : '-'} + Mix: ${sel.mix ? sel.mix.name : '-'}</li>`;
	                } else {
	                    details += `<li>${sel.qty ? 'x'+sel.qty+' ' : ''}${sel.slot_category ? sel.slot_category+':' : ''} ${sel.product ? sel.product.name : '-'}</li>`;
	                }
	            });
	            details += '</ul>';
	        }
	        div.innerHTML = `
	            <div>
	                ${item.name}<br>
	                x${item.qty} - R${item.price * item.qty}
	                ${details}
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

	/* Clearing Sale */

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

		let total = parseFloat(document.getElementById("sale-total").innerText)

		// Build cleanItems: flatten specials into product/qty/price
		let cleanItems = [];
		sale.forEach(item => {
			if(item.special_id && item.selections){
				// For each selection in the special, add as a separate item
				item.selections.forEach(sel => {
					// Alcohol/mix pair
					if(sel.alcohol){
						cleanItems.push({
							product_id: sel.alcohol.id,
							qty: sel.qty || 1,
							price: 0 // price is handled at special level
						});
					}
					if(sel.mix){
						cleanItems.push({
							product_id: sel.mix.id,
							qty: sel.qty || 1,
							price: 0
						});
					}
					// Generic slot system
					if(sel.product){
						cleanItems.push({
							product_id: sel.product.id,
							qty: sel.qty || 1,
							price: 0
						});
					}
				});
			} else if(item.product_id && item.qty > 0 && item.price > 0){
				cleanItems.push({
					product_id: item.product_id,
					qty: item.qty,
					price: item.price
				});
			}
		});
		let payload = {
			sale:{
				location_id:POS_LOCATION ? parseInt(POS_LOCATION) : 1,
				payment_type_id:paymentType,
				user_id:1,
				total: total
			},
			items: cleanItems
		}

		if(paymentType === 2 && window._cardRef){
			payload.sale.card_reference = window._cardRef;
			window._cardRef = undefined;
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
		    loadCreditsList() // <-- fixed function name

		})
		.catch(err => console.error("Error completing sale:", err))

	}

	/* Opening Tab */

	function createTab(){

	    let name = document.getElementById("tabName").value.trim()
	    let phone = document.getElementById("tabPhone").value.trim()

	    if(!name || !phone){
	        alert("Name and phone are required")
	        return
	    }

	    let payload = {
	        name: name,
	        phone: phone,
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

	        if(data.status === 'error'){
	            alert(data.message)
	            return
	        }

	        // ✅ Close modal
	        let modal = bootstrap.Modal.getInstance(document.getElementById('tabModal'))
	        modal.hide()

	        // ✅ Clear form
	        document.getElementById("tabName").value = ""
	        document.getElementById("tabPhone").value = ""

	        // ✅ Now send sale linked to tab
	        completeSaleOnTab(data.tab_id)
	        loadTabs()

	    })
	    .catch(err => console.error("Error creating tab:", err))

	}

	/* Start POS */

	initPOS()

	// run every 5 seconds
	setInterval(renderTabs, 5000)

	function removeItem(index){
	    sale.splice(index,1)
	    renderSale()
	}

	function decreaseQty(index){

	    if(sale[index].qty > 1){
	        sale[index].qty--
	    } else {
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

	    let total = parseFloat(document.getElementById("sale-total").innerText)

	    // ✅ define FIRST
	    let cleanItems = sale
	        .filter(i => i.product_id && i.qty > 0 && i.price > 0)
	        .map(i => ({
	            product_id: i.product_id,
	            qty: i.qty,
	            price: i.price
	        }))

	    // ✅ THEN build payload
	    let payload = {
	        sale:{
	            location_id:POS_LOCATION ? parseInt(POS_LOCATION) : 1,
	            payment_type_id: null,
	            user_id:1,
	            total: total,
	            tab_id: tabId // <-- Add tab_id here
	        },
	        items: cleanItems
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
	        loadCreditsList() // <-- fixed function name
	        loadTabs()

	    })
	    .catch(err => console.error("Error adding to tab:", err))
	}

	function loadTabs(){

	    Promise.all([
	        fetch(BASE_URL + "pos/init").then(res=>res.json()),
	        fetch(BASE_URL + "pos/tab-totals").then(res=>res.json())
	    ])
	    .then(([initData, totals]) => {

	        tabs = initData.tabs

	        let map = {}
	        if(Array.isArray(totals)){
	            totals.forEach(t => map[String(t.tab_id)] = Number(t.total))
	        }

	        renderTabsWithTotals(map)

	    })
	    .catch(err => console.error("Error loading tabs:", err))
	}

	function renderTabsWithTotals(map){
	    renderTabs()
	}

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
	                    onclick="openPayTabModal(${t.id})">
	                    Pay
	                </button>
	            `

	            container.appendChild(div)

	        })

	    })
	    .catch(err => console.error("Error rendering tabs:", err))
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
	        if(data.status === 'error'){
	            alert(data.message)
	            return
	        }
	        alert("Tab Paid")
	        loadTabs()
	    })
	    .catch(err => console.error("Error paying tab:", err))

	}

	function loadCreditsList() {
    fetch(BASE_URL + "credits/list")
    .then(res => res.json())
    .then(data => {
        let container = document.getElementById("creditListContainer");
        // Only show credits with remaining > 0
        let filtered = (data || []).filter(c => c.remaining > 0);
        if (filtered.length === 0) {
            container.style.display = 'none';
            container.innerHTML = '';
            return;
        }
        let html = filtered.map(c => {
            let redeemBtns = '';
            redeemBtns = `
                <button class=\"btn btn-success btn-sm\" onclick=\"redeemCredit(${c.id},1)\">Redeem 1</button>
                <button class=\"btn btn-warning btn-sm\" onclick=\"redeemCredit(${c.id},2)\">Redeem 2</button>
            `;
            return `<div class=\"card mb-2\">
                <div class=\"card-body\">
                    <b>${c.name} (${c.phone})</b><br>
                    Remaining: ${c.remaining}
                    <br>
                    ${redeemBtns}
                </div>
            </div>`
        }).join('');
        container.innerHTML = html;
        container.style.display = '';
    });
}
function redeemCredit(id, qty) {
    fetch(BASE_URL + "credits/redeem", {
        method: "POST",
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({id, qty})
    })
    .then(res => res.json())
    .then(data => {
        if(data.status === 'success') {
            alert("Redeemed!");
            loadCreditsList();
        } else {
            alert(data.message);
        }
    });
}
document.getElementById("addCreditForm").onsubmit = function(e) {
    e.preventDefault();
    fetch(BASE_URL + "credits/add", {
        method: "POST",
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({
            name: document.getElementById("creditName").value,
            phone: document.getElementById("creditPhone").value,
            qty: document.getElementById("creditQty").value
        })
    })
    .then(res => res.json())
    .then(data => {
        if(data.status === 'success') {
            alert("Credit added!");
            loadCreditsList();
            document.getElementById("addCreditForm").reset();
            let modal = bootstrap.Modal.getInstance(document.getElementById('creditModal'));
            modal.hide();
        } else {
            alert(data.message);
        }
    });
};
loadCreditsList();

document.getElementById('payTabCashBtn').onclick = function() {
    showConfirmPayTabModal(window._payTabId, 1, null);
    let modal = bootstrap.Modal.getInstance(document.getElementById('payTabModal'));
    modal.hide();
};

document.getElementById('payTabCardBtn').onclick = function() {
    let modal = bootstrap.Modal.getInstance(document.getElementById('payTabModal'));
    modal.hide();
    openCardRefModal(true); // true = paying tab
};

function submitCardPayment() {
    let ref = document.getElementById('cardRefInput').value.trim();
    if (!ref) {
        alert('Please enter a card reference or slip number.');
        return;
    }
    window._cardRef = ref;
    let modal = bootstrap.Modal.getInstance(document.getElementById('cardRefModal'));
    modal.hide();
    if (window._payingTab) {
        showConfirmPayTabModal(window._payTabId, 2, ref);
        window._payingTab = false;
    } else {
        completeSale(2);
    }
}

let _pendingPayTab = { tabId: null, paymentType: null, cardRef: null };

function showConfirmPayTabModal(tabId, paymentType, cardRef) {
    _pendingPayTab = { tabId, paymentType, cardRef };
    let text = 'Are you sure you want to pay this tab by ' + (paymentType === 2 ? 'CARD' : 'CASH') + '?';
    document.getElementById('confirmPayTabText').innerText = text;
    let modal = new bootstrap.Modal(document.getElementById('confirmPayTabModal'));
    modal.show();
}

document.getElementById('confirmPayTabBtn').onclick = function() {
    let modal = bootstrap.Modal.getInstance(document.getElementById('confirmPayTabModal'));
    modal.hide();
    payTabWithType(_pendingPayTab.tabId, _pendingPayTab.paymentType, _pendingPayTab.cardRef);
};

function payTabWithType(tabId, paymentType, cardRef) {
    let payload = {
        tab_id: tabId,
        payment_type_id: paymentType
    };
    if (paymentType === 2 && cardRef) {
        payload.card_reference = cardRef;
    }
    fetch(BASE_URL + "pos/pay-tab",{
        method:'POST',
        headers:{ 'Content-Type':'application/json' },
        body:JSON.stringify(payload)
    })
    .then(res=>res.json())
    .then(data=>{
        if(data.status === 'error'){
            alert(data.message)
            return
        }
        alert("Tab Paid")
        loadTabs()
    })
    .catch(err => console.error("Error paying tab:", err));
}

let POS_LOCATION = null;
let POS_LOCATION_NAME = null;

function updateLocationDisplay() {
    const locId = localStorage.getItem('POS_LOCATION');
    if (!locId) {
        document.getElementById('currentLocationName').textContent = '-';
        return;
    }
    fetch(BASE_URL + 'pos/locations')
    .then(res => res.json())
    .then(locations => {
        const found = locations.find(l => String(l.id) === String(locId));
        document.getElementById('currentLocationName').textContent = found ? found.name : '-';
    });
}

function showLocationModal() {
    fetch(BASE_URL + 'pos/locations')
    .then(res => res.json())
    .then(locations => {
        let select = document.getElementById('locationSelect');
        select.innerHTML = '';
        locations.forEach(loc => {
            let opt = document.createElement('option');
            opt.value = loc.id;
            opt.textContent = loc.name;
            select.appendChild(opt);
        });
        let modal = new bootstrap.Modal(document.getElementById('locationModal'));
        modal.show();
    });
}

function addLocation() {
    let name = document.getElementById('newLocationInput').value.trim();
    if (!name) return;
    fetch(BASE_URL + 'pos/add-location', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ name })
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            document.getElementById('newLocationInput').value = '';
            showLocationModal();
        } else {
            alert(data.message);
        }
    });
}

function confirmLocation() {
    let select = document.getElementById('locationSelect');
    POS_LOCATION = select.value;
    localStorage.setItem('POS_LOCATION', POS_LOCATION);
    updateLocationDisplay();
    let modal = bootstrap.Modal.getInstance(document.getElementById('locationModal'));
    modal.hide();
}

function clearPosLocation() {
    localStorage.removeItem('POS_LOCATION');
    updateLocationDisplay();
}

window.addEventListener('pageshow', function() {
    POS_LOCATION = localStorage.getItem('POS_LOCATION');
    if (!POS_LOCATION) {
        showLocationModal();
    }
    updateLocationDisplay();
});

window.addEventListener('beforeunload', function() {
    clearPosLocation();
});

// Fetch and display specials in the POS UI
fetch('/api/specials')
  .then(res => res.json())
  .then(data => {
    const specialsList = document.getElementById('specials-list');
    specialsList.innerHTML = '';
    if (!Array.isArray(data) || data.length === 0) {
      specialsList.innerHTML = '<div class="alert alert-warning">No specials available.</div>';
      return;
    }
    data.forEach(special => {
      const btn = document.createElement('button');
      btn.className = 'btn btn-outline-warning w-100 mb-2';
      btn.innerText = special.name + ' (R' + special.price + ')';
      btn.onclick = () => openSpecialsModal(special);
      specialsList.appendChild(btn);
    });
  });

function openSpecialsModal(special) {
  // Fetch special items and products for selection
  fetch('/api/specials/' + special.id + '/items')
    .then(res => res.json())
    .then(items => {
      let html = '';
      if (!Array.isArray(items) || items.length === 0) {
        html = '<div class="alert alert-warning">No products available for this special.</div>';
        document.getElementById('specials-modal-body').innerHTML = html;
        const modal = new bootstrap.Modal(document.getElementById('specialsModal'));
        modal.show();
        return;
      }
      let allSlotsOutOfStock = true;
      items.forEach(item => {
        if (item.products.some(product => product.stock > 0)) allSlotsOutOfStock = false;
      });
      html = '';
      if (allSlotsOutOfStock) {
        html += '<div class="alert alert-danger">All products for this special are out of stock.</div>';
      }
      html += '<form id="special-sale-form">';
      // Render a dropdown for every slot (alcohol, mix, etc.)
      items.forEach((item, idx) => {
        html += `<div class="mb-3">
          <label class="form-label">${item.category_name} (x${item.quantity})</label>
          <select class="form-select" name="slot_${idx}" required>`;
        item.products.forEach(product => {
          html += `<option value="${product.id}"${product.stock <= 0 ? ' disabled' : ''}>${product.name} (Stock: ${product.stock})</option>`;
        });
        html += '</select></div>';
      });
      html += '</form>';
      document.getElementById('specials-modal-body').innerHTML = html;
      const modal = new bootstrap.Modal(document.getElementById('specialsModal'));
      modal.show();
      document.getElementById('sell-special-btn').innerText = 'Add to Cart';
      document.getElementById('sell-special-btn').onclick = function() {
        // Gather selected products and add to cart
        const form = document.getElementById('special-sale-form');
        const formData = new FormData(form);
        let selections = [];
        items.forEach((item, idx) => {
          selections.push({
            slot_category: item.category_name,
            qty: item.quantity,
            product: item.products.find(p => p.id == formData.get('slot_' + idx))
          });
        });
        // Add special to sale array
        sale.push({
          special_id: special.id,
          name: special.name,
          price: special.price,
          qty: 1,
          selections: selections
        });
        renderSale();
        modal.hide();
      };
    });
}

</script>
</body>
</html>
