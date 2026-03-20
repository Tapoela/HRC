
	<head>

		<style>

			body{
			font-family: DejaVu Sans;
			font-size:12px;
			color:#333;
			}

			.header{
			width:100%;
			margin-bottom:20px;
			}

			.logo{
			width:110px;
			}

			.club{
			font-size:18px;
			font-weight:bold;
			}

			.address{
			font-size:12px;
			}

			.po-info{
			text-align:right;
			}

			table{
			border-collapse:collapse;
			width:100%;
			margin-top:15px;
			}

			table th{
			background:#f2f2f2;
			border:1px solid #ccc;
			padding:8px;
			text-align:left;
			}

			table td{
			border:1px solid #ccc;
			padding:8px;
			}

			.total{
			text-align:right;
			font-size:14px;
			font-weight:bold;
			}

			.footer{
			margin-top:60px;
			}

			.signature{
			margin-top:40px;
			}

			.footer{
			margin-top:80px;
			}

		</style>

	</head>

	<body>

	<table class="header">

		<tr>

			<td width="20%">

				<img src="<?= $logo ?>" class="logo">

			</td>

			<td width="50%">

				<div class="club">
					Heidelberg Rugby Club
				</div>

				<div class="address">
					Address: 1 Marshall St<br>
					Industrial, Heidelberg - GP<br>
					1441
				</div>

			</td>

			<td width="30%" class="po-info">

				<strong>PURCHASE ORDER</strong><br><br>

				<strong>PO Number:</strong><br>
				<?= $po['po_number'] ?><br><br>

				<strong>Date:</strong><br>
				<?= $po['order_date'] ?>

			</td>

		</tr>

	</table>

	<hr>

	<h4>Supplier</h4>

	<p>
	<?= $supplier['name'] ?? 'Supplier not specified' ?>
	</p>

	---

	<h4>Order Items</h4>

		<table>

			<thead>

				<tr>
					<th width="50%">Product</th>
					<th width="15%">Qty</th>
					<th width="15%">Cost</th>
					<th width="20%">Total</th>
				</tr>

			</thead>

			<tbody>

				<?php
				$grand = 0;
				foreach($items as $item):

				$line = $item['qty_ordered'] * $item['cost_price'];
				$grand += $line;
				?>

					<tr>

						<td><?= $item['name'] ?></td>

						<td><?= $item['qty_ordered'] ?></td>

						<td>R <?= number_format($item['cost_price'],2) ?></td>

						<td>R <?= number_format($line,2) ?></td>

					</tr>

				<?php endforeach ?>

			</tbody>

			<tfoot>

				<tr>

					<td colspan="3" class="total">
					Total
					</td>

					<td class="total">
					R <?= number_format($grand,2) ?>
					</td>

				</tr>

			</tfoot>

			<h4 style="margin-top:60px;">Approvals</h4>

			<table width="100%" style="text-align:center; margin-top:20px; border:none;">

			<tr>

			<?php foreach($approvals as $a): ?>

			<td style="border:none; width:33%;">

			<?php if(!empty($a['signature'])): ?>

			<img src="<?= $a['signature'] ?>" width="120">

			<?php else: ?>

			<br><br><br>

			<?php endif ?>

			<br>

			_________________________

			<br>

			<strong><?= $a['role_name'] ?></strong>

			<br>

			<?php if($a['status']=='approved'): ?>

			<small>
			Approved <?= date('d M Y',strtotime($a['approved_at'])) ?>
			</small>

			<?php else: ?>

			<small>Pending Approval</small>

			<?php endif ?>

			</td>

			<?php endforeach ?>

			</tr>

			</table>

		</table>

	</body>