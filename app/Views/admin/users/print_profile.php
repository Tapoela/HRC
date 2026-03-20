<?php
function get_image_file($jsonField){
    if(!empty($jsonField)){
        $arr = json_decode($jsonField,true);
        if(is_array($arr) && count($arr)>0){
            return $arr[0];
        }
    }
    return '';
}

$imageFile = get_image_file($user['image'] ?? '');
if(empty($imageFile)){
    $imageFile = get_image_file($user['files'] ?? '');
}

$namePart = str_replace(' ','_',$user['name'] ?? '');
$surnamePart = !empty($user['surname']) ? '_'.str_replace(' ','_',$user['surname']) : '';
$folder = ($user['idnumber'] ?? '').'_'.$namePart.$surnamePart;

$photoPath = '';

if(!empty($user['idnumber']) && !empty($imageFile)){
    $photoPath = base_url('uploads/Users/'.$folder.'/'.$imageFile);
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Player Details</title>
  <link rel="stylesheet" href="<?= base_url('assets/adminlte/plugins/bootstrap/css/bootstrap.min.css') ?>">
	 <style>
		/* ================= GENERAL ================= */

		body{
		    background:#fff;
		    color:#000;
		    font-size:13px;
		}

		.print-btn {
		    position: fixed;
		    top: 10px;
		    right: 20px;
		    z-index:9999;
		}

		.print-area {
		    max-width: 900px;
		    margin: 30px auto;
		    padding: 12mm;
		    border: 1px solid #ccc;
		    background: #fff;
		    position: relative;
		}

		/* ================= TABLE ================= */

		table {
		    border-collapse: collapse;
		    width: 100%;
		    font-size:13px;
		}

		table th,
		table td {
		    border: 1px solid #000 !important;
		    padding: 6px 8px;
		    vertical-align: middle;
		}

		table th{
		    background:#f6f6f6;
		}

		/* ================= PHOTO ================= */

		.player-photo{
		    width:90px;
		    height:110px;
		    object-fit:cover;
		    border:2px solid #ddd;
		    border-radius:6px;
		    box-shadow:0 2px 6px #ccc;
		}

		/* ================= AVOID PAGE BREAKS ================= */

		.avoid-break{
		    page-break-inside:avoid;
		    break-inside:avoid;
		}

		/* ================= PRINT MODE ================= */

		@media print {

		    @page {
		        size: A4 portrait;
		        margin: 8mm;
		    }

		    body{
		        margin:0 !important;
		        padding:0 !important;
		        background:#fff !important;
		    }

		    .print-btn{
		        display:none !important;
		    }

		    .print-area{
		        margin:0 !important;
		        padding:5mm 4mm 6mm 4mm !important;
		        border:none !important;
		        box-shadow:none !important;
		    }

		    h2{
		        margin:4px 0;
		    }

		    p{
		        text-align:justify;
		    }

		    img{
		        -webkit-print-color-adjust: exact;
		        print-color-adjust: exact;
		    }
		}

	</style>

</head>
<body>
  <div class="print-btn">
    <button onclick="window.print()" class="btn btn-success">🖨️ Print</button>
  </div>
  <div class="print-area">
    <div class="text-center" style="margin-bottom: 20px;">
      <div style="display: flex; justify-content: space-between; max-width: 800px; margin: 0 auto;">
        <img src="<?= base_url('assets/Logos/ValkeLogo.jpg') ?>" alt="Left Logo" style="max-height: 100px;">
        <img src="<?= base_url('assets/Logos/BergeLogo.png') ?>" alt="Right Logo" style="max-height: 100px;">
      </div>
    </div>
    <h2 align="center">Heidelberg Rugby Club</h2>
    <h2 align="center">Player Registration Form</h2>
    <table class="table table-bordered">
      <tr>
        <th colspan="4" align="center"><h3>Player Information</h3></th>
        <!-- Add a header for the photo -->
        <?php
			echo '<!-- ID: '.($user['idnumber'] ?? '').' 
			| Name: '.($user['name'] ?? '').' 
			| Surname: '.($user['surname'] ?? '').' 
			| Image: '.($user['image'] ?? '').' 
			| Decoded imageFile: '.$imageFile.' 
			| photoPath: '.$photoPath.' -->';
		?>

        <th rowspan="8" style="vertical-align: middle; text-align: center; width: 110px;">
          <?php if (!empty($photoPath)): ?>
            <img src="<?= esc($photoPath) ?>" class="player-photo" alt="Player Photo">

          <?php else: ?>
            <div style="width:90px; height:110px; background:#f3f3f3; display:flex; align-items:center; justify-content:center; color:#bbb; border:2px solid #ddd; border-radius:6px; font-size:12px;">
              No image<br>uploaded
            </div>
          <?php endif; ?>
        </th>
      </tr>
      <tr>
        <th>Name & Surname :</th>
        <td><?= $user['name'] . ' ' .$user['surname'] ?></td>
        <th>Valke Number:</th>
        <td></td>
      </tr>
      <tr>
        <th>Telephone Number:</th>
        <td><?= $user['cell'] ?></td>
        <th>Christian Name :</th>
        <td></td>
      </tr>
      <tr>
        <th>ID Number :</th>
        <td><?= $user['idnumber'] ?></td>
        <th>Birthdate :</th>
        <td><?= $user['birthdate'] ?></td>
      </tr>
      <tr>
        <th>Address :</th>
        <td><?= $user['address'] ?></td>
        <th>Email :</th>
        <td><?= $user['email'] ?></td>
      </tr>
      <tr>
        <th>Height :</th>
        <td><?= esc($user['height']) ?></td>
        <th>Weight :</th>
        <td><?= $user['weight'] ?> KG</td>
      </tr>
      <tr>
        <th>Medical Aid :</th>
        <td><?= $user['med_aid'] ?></td>
        <th>Medical Aid No. :</th>
        <td><?= $user['med_no'] ?></td>
      </tr>
      <tr>
        <th>Next Of Kin Name & Surname :</th>
        <td><?= esc($user['spouse_name']) ?></td>
        <th>Next Of Kin Telephone Number :</th>
        <td><?= $user['spouse_tel'] ?></td>
      </tr>
    </table>
    <h4><b>Declaration and Indemnity</b></h4>
    <p>
      I, the undersigned hereby declare that I am applying for membership of the HEIDELBERG RUGBY CLUB out of free will. I undertake to pay my annual fees in the amount <b>of R 350.00 in full on or by 30 April 2025</b>, failing this I understand that I will not be eligible to vote at any Annual General Meeting or any Special General Meeting or participate as a player. I declare myself available for the club's first team, and should I be selected to this team, and I then refuse to play in it, I understand that I will not be permitted to play for any of the club's other teams. I hereby indemnify the Valke Union Club, Directors, Officials, Coaches, Players, or Members of any responsibility should I incur any loss or sustain any injury whatsoever. I declare that the Constitution and the Club Code are available to me and deem myself bound by it.
    </p>
    <h4><b>Verklaring en Vrywaring</b></h4>
    <p>
      Ek, die ondergetekende bevestig hiermee dat ek myself vrywillig as lid van die HEIDELBERG RUGBY KLUB aansluit. Ek bevestig verder dat ek my jaarfooie, bedrag <b>van R 350.00 voor of op 30 April 2025</b>, ten volle sal betaal en is ten volle bewus daarvan dat as ek versuim om dit te doen ek nie stemregtig is op enige Algemene Jaarvergardering of Spesiale Jaarvergardering nie of as speler deel te neem nie. Ek verklaar myself ook hiermee bereid om vir die klub se eerste span te speel sou ek daar gekies word en is bewus daarvan dat, nadat ek daar gekies is, weier om vir die eerste span te speel dat ek op die betrokke dag vir geen ander klub span mag speel nie. Ek vrywaar hiermee die Valke Unie Klub, Heidelberg Direksie, Beamptes, Afrigters, van wedstryde, oefeninge, funksies of aanspreeklikheid ten opsigte van enige beserings of skade wat ek mag opdoen. Hiermee verklaar ek dat Heidelberg Rugby Klub se Klub Kode en Konstitusie tot my beskikking is en dat ek myself daaraan sal onderwerp.
    </p>
    <div class="avoid-break" style="display: flex; justify-content: space-between; align-items: center; margin-top: 30px;">
    	<div style="text-align: right;">
    		<label style="margin-left: 15px;">Signed on:<?= date('d M Y', strtotime($user['signed_day'])) ?></label>
	        <em></em>
	        <label>At:</label> <em>Heidelberg</em>
	    </div>

		<div style="display:flex; align-items:center;">
		    <label style="margin-right:10px;">Signature:</label>

		        <?php if (!empty($user['signature'])): ?>
		            <img src="<?= esc($user['signature']) ?>"
		                 style="height:50px; object-fit:contain;">
		        <?php else: ?>
		            <em>No signature captured</em>
		        <?php endif; ?>
		</div>
    </div>
  </div>
</body>
</html>

