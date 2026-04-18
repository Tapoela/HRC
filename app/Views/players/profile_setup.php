<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="container-fluid">

    <div class="card shadow-lg">
        <div class="card-header bg-dark text-white">
            <h4 class="mb-0">Complete Your Player Registration</h4>
        </div>

        <form method="post" action="<?= site_url('/player/profile/save') ?>" id="profileWizard" novalidate enctype="multipart/form-data">
            <?= csrf_field() ?>

            <div class="card-body">

                <div class="wizard-progress mb-4">
                    <div class="wizard-bar">
                        <div class="wizard-bar-fill"></div>
                    </div>
                </div>

                <div class="autosave-status">
                    <span id="autosaveIndicator">Saved ✓</span>
                </div>

                <!-- STEP 1 -->
                <div class="wizard-step active">

                    <h5 class="text-primary">Personal Details</h5>

                    <div class="row">
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-body">

                                <h5 class="text-primary mb-12">
                                    <i class="bi bi-person-badge me-2"></i> Personal Details
                                </h5>

                                <div class="row g-12">

                                    <!-- ID Number -->
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">ID Number</label>
                                        <input name="idnumber"
                                               id="idnumber"
                                               class="form-control"
                                               required
                                               value="<?= esc($user['idnumber'] ?? '') ?>">

                                        <small id="id-feedback" class="text-muted"></small>
                                    </div>

                                    <!-- Birthdate -->
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Date of Birth</label>
                                        <input type="date"
                                               name="birthdate"
                                               class="form-control"
                                               value="<?= esc($user['birthdate'] ?? '') ?>"
                                               required>
                                    </div>

                                    <!-- Position -->
                                    <div class="col-md-4">

                                        <label class="form-label fw-semibold">Position</label>
                                        <select name="position_id" class="form-control" required>

                                            <option value="">Select Position</option>

                                            <?php foreach($positions as $pos): ?>

                                                <option value="<?= $pos->id ?>"
                                                    <?= (isset($user['position_id']) && $user['position_id'] == $pos->id) ? 'selected' : '' ?>>

                                                    <?= esc($pos->position_name) ?>

                                                </option>

                                            <?php endforeach; ?>

                                        </select>

                                    </div>

                                    <!-- Player Photo -->
                                    <div class="text-center">

                                    <img id="photoPreview"
                                         src="<?= base_url('uploads/defaults/avatar.png') ?>"
                                         class="img-thumbnail mb-2"
                                         style="width:200px;height:200px;border-radius:50%;object-fit:cover;">

                                    <div class="d-flex justify-content-center gap-3">

                                    <button type="button"
                                            class="btn btn-primary btn-sm"
                                            onclick="document.getElementById('photo_camera').click()">
                                    📷 Take Photo
                                    </button>

                                    <button type="button"
                                            class="btn btn-outline-secondary btn-sm"
                                            onclick="document.getElementById('photo_gallery').click()">
                                    Upload
                                    </button>

                                    </div>

                                    <input type="file"
                                           name="photo_camera"
                                           id="photo_camera"
                                           accept="image/*"
                                           capture="environment"
                                           style="display:none">

                                    <input type="file"
                                           name="photo_gallery"
                                           id="photo_gallery"
                                           accept="image/*"
                                           style="display:none">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-end mt-3">
                        <button type="button" class="btn btn-primary nextStep">Next</button>
                    </div>

                </div>

                <!-- STEP 2 -->
                <div class="wizard-step">

                    <h5 class="text-primary">Contact & Medical</h5>

                    <div class="row">
                        <div class="col-md-2">
                            <label>Address</label>
                            <input name="address" class="form-control" value="<?= esc($user['address'] ?? '') ?>" required>
                        </div>

                        <div class="col-md-2">
                            <label>Medical Aid</label>
                            <input name="med_aid" value="<?= esc($user['med_aid'] ?? '') ?>" class="form-control">
                        </div>

                        <div class="col-md-2">
                            <label>Medical Number</label>
                            <input name="med_no" value="<?= esc($user['med_no'] ?? '') ?>" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <label>Cell Number</label>
                            <input name="cell" value="<?= esc($user['cell'] ?? '') ?>" id="cell" class="form-control" required>
                        </div>
                        <div class="col-md-2">
                            <label>Next of kin:</label>
                            <input type="text" value="<?= esc($user['spouse_name'] ?? '') ?>" class="form-control" name="spouse_name" required>
                          </div>
                          <div class="col-md-2">
                            <label>Next of kin Tel:</label>
                            <input type="text" value="<?= esc($user['spouse_tel'] ?? '') ?>" class="form-control" name="spouse_tel" id="spouse_tel" maxlength="10" required>
                          </div>
                    </div>

                    <div class="d-flex justify-content-between mt-3">
                        <button type="button" class="btn btn-secondary prevStep">Back</button>
                        <button type="button" class="btn btn-primary nextStep">Next</button>
                    </div>

                </div>

                <!-- STEP 3 -->
                <div class="wizard-step">

                    <h5 class="text-primary">Rugby Stats</h5>

                    <div class="row">
                        <div class="col-md-6">
                            <label>Height (cm)</label>
                            <input name="height" value="<?= esc($user['height'] ?? '') ?>" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label>Weight (kg)</label>
                            <input name="weight" value="<?= esc($user['weight'] ?? '') ?>" class="form-control">
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-3">
                        <button type="button" class="btn btn-secondary prevStep">Back</button>
                        <button type="button" class="btn btn-primary nextStep">Next</button>
                    </div>

                </div>

                <div class="wizard-step">

                <h4 class="mt-3">Declaration and Indemnity</h4>

                <div class="ultra-declaration">

                <p>If you experience and technical difficulties please contact us on Whatsapp 082 825 6916</p>

                <p>
                    I, the undersigned hereby declare that I am applying for membership of the HEIDELBERG RUGBY CLUB out of free will. I undertake to pay my annual fees in the amount <b>of R<?= esc($fees['amount']) ?> in full on or by 30 April <?= esc($fees['year']) ?> </b> wich will included a club t-shirt registration at the valke and award ceremony at the end of the year, failing this I understand that I will not be eligible to vote at any Annual General Meeting or any Special General Meeting or participate as a player. I declare myself available for the club's first team, and should I be selected to this team, and I then refuse to play in it, I understand that I will not be permitted to play for any of the club's other teams. I hereby indemnify the Valke Union Club, Directors, Officials, Coaches, Players, or Members of any responsibility should I incur any loss or sustain any injury whatsoever. I declare that the Constitution and the Club Code are available to me and deem myself bound by it.
                </p>

                <h4><b>Verklaring en Vrywaring</b></h4>
                <p>As U enige tegniese probleme ervaar kontak ons op Whatsapp 082 825 6916</p>
                    <p>
                      Ek, die ondergetekende bevestig hiermee dat ek myself vrywillig as lid van die HEIDELBERG RUGBY KLUB aansluit. Ek bevestig verder dat ek my jaarfooie, bedrag <b>van R<?= esc($fees['amount']) ?> voor of op 30 April <?= esc($fees['year']) ?> </b> sluit in n klub hemp, regestratsie by die valke en prysuitdeling aan die einde van die jaar en ten volle sal betaal. Ek is ten volle bewus daarvan dat as ek versuim om dit te doen ek nie stemregtig is op enige Algemene Jaarvergardering of Spesiale Jaarvergardering nie of as speler deel te neem nie. Ek verklaar myself ook hiermee bereid om vir die klub se eerste span te speel sou ek daar gekies word en is bewus daarvan dat, nadat ek daar gekies is, weier om vir die eerste span te speel dat ek op die betrokke dag vir geen ander klub span mag speel nie. Ek vrywaar hiermee die Valke Unie Klub, Heidelberg Direksie, Beamptes, Afrigters, van wedstryde, oefeninge, funksies of aanspreeklikheid ten opsigte van enige beserings of skade wat ek mag opdoen. Hiermee verklaar ek dat Heidelberg Rugby Klub se Klub Kode en Konstitusie tot my beskikking is en dat ek myself daaraan sal onderwerp.
                    </p>
                </div>

                <label class="mt-3">
                <input type="checkbox" id="acceptDeclaration" required>
                I accept the declaration and indemnity
                </label>

                <div class="d-flex justify-content-between mt-3">
                    <button type="button" class="btn btn-secondary prevStep">Back</button>
                    <button type="button" class="btn btn-ultra-rugby nextStep">
                        Next
                    </button>
                </div>

                </div>

                <!-- STEP 4 SIGNATURE -->
                <div class="wizard-step">

                    <h5 class="text-primary">Declaration & Signature</h5>

                    <canvas id="signature-pad" style="width:100%;height:150px;border:1px solid #ccc;"></canvas>
                    <input type="hidden" name="signature" id="signature">

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label>Signed on</label>
                            <input type="date" value="<?= esc($user['signed_day'] ?? '') ?>" name="signed_day" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label>Signed at</label>
                            <input type="text" value="<?= esc($user['signed_at'] ?? '') ?>" name="signed_at" class="form-control">
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-3">
                        <button type="button" class="btn btn-secondary prevStep">Back</button>
                        <button type="submit" class="btn btn-ultra-rugby">
                            Complete Profile
                        </button>

                    </div>

                </div>

            </div>
        </form>
        </div>

</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>

<script src="<?= base_url('assets/public/js/signature_pad.js') ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/face-api.js"></script>

<script>

const LAST_STEP = <?= isset($draft['last_step']) ? (int)$draft['last_step'] : 0 ?>;

</script>

<script>

const USER_ID = <?= session()->get('user_id') ?>;
const LOCAL_KEY = 'profileDraft_' + USER_ID;

</script>

<script>

    /* =========================
       SA ID VALIDATION HELPERS
    ========================= */

    function isValidSAID(id) {

        if (!/^\d{13}$/.test(id)) return false;

        var year = parseInt(id.substr(0, 2), 10);
        var month = parseInt(id.substr(2, 2), 10);
        var day = parseInt(id.substr(4, 2), 10);

        var currentYear = new Date().getFullYear() % 100;
        var fullYear = (year > currentYear ? 1900 : 2000) + year;

        var date = new Date(fullYear, month - 1, day);

        if (
            date.getFullYear() !== fullYear ||
            date.getMonth() !== (month - 1) ||
            date.getDate() !== day
        ) return false;

        // Luhn
        var sum = 0;
        var alternate = false;
        for (var i = id.length - 1; i >= 0; i--) {
            var n = parseInt(id.charAt(i), 10);
            if (alternate) {
                n *= 2;
                if (n > 9) n -= 9;
            }
            sum += n;
            alternate = !alternate;
        }
        return (sum % 10 === 0);
    }

    $('#cell, #Spouse_Tel').on('input', function(){

        const pattern = /^0[6-8][0-9]{8}$/;
        const value = $(this).val().trim();

        $(this).next('.field-error').remove();

        if(value && !pattern.test(value)){
            $(this).after('<small class="text-danger field-error">Invalid SA cell number</small>');
        }
    });


    function extractDOBFromSAID(id){

        if (!/^\d{13}$/.test(id)) return null;

        const year = parseInt(id.substr(0,2),10);
        const month = parseInt(id.substr(2,2),10);
        const day = parseInt(id.substr(4,2),10);

        const currentYear = new Date().getFullYear() % 100;
        const fullYear = (year > currentYear ? 1900 : 2000) + year;

        // validate date
        const dob = new Date(fullYear, month-1, day);

        if (
            dob.getFullYear() !== fullYear ||
            dob.getMonth() !== month-1 ||
            dob.getDate() !== day
        ) return null;

        // ✅ LOCAL DATE FORMAT (no timezone shift)
        const mm = String(month).padStart(2,'0');
        const dd = String(day).padStart(2,'0');

        return `${fullYear}-${mm}-${dd}`;
    }


    $(function(){

    let step = typeof LAST_STEP !== 'undefined' ? LAST_STEP : 0;

    const steps = $('.wizard-step');
    const progress = $('.wizard-bar-fill');

    function show(i){

        window.scrollTo({
            top:0,
            behavior:'smooth'
        });

        steps.hide().removeClass('active');
        const current = steps.eq(i).addClass('active').show();

        const percent = ((i) / (steps.length-1)) * 100;
        progress.css('width', percent + '%');

        // 🔥 Initialise signature when canvas becomes visible
        if(current.find('#signature-pad').length){
            setTimeout(function(){
                initSignaturePad();
            },100);
        }
    }

    show(step);

    /* =========================
       OFFLINE RESTORE (SAFE)
    ========================= */

    try {

        const raw = localStorage.getItem(LOCAL_KEY);

        // nothing saved yet
        if(!raw) {
            console.log('No offline draft found');
        } else {

            const offlineData = JSON.parse(raw);

            if(offlineData && typeof offlineData === 'object'){

                Object.keys(offlineData).forEach(function(name){

                    const field = $('[name="'+name+'"]');

                    if(field.length){
                        field.val(offlineData[name]);
                    }

                });

                console.log('Offline draft restored');
            }
        }

    }catch(e){
        console.warn('Offline restore failed', e);
    }


    /* =========================
       LIVE SA ID VALIDATION + DOB AUTO FILL
    ========================= */

    $('#idnumber').on('keyup', function(){

        const id = $(this).val().trim();
        const feedback = $('#id-feedback');

        feedback.text('');

        if(id.length === 13){

            if(!isValidSAID(id)){
                feedback.text("Invalid SA ID number").css('color','red');
                return;
            }

            // ✅ AUTO FILL DOB
            const dob = extractDOBFromSAID(id);
            if(dob){
                $('input[name="birthdate"]').val(dob);
            }

            feedback.text("Valid SA ID").css('color','green');
        }
    });

    /* =========================
       AGE CALCULATION
    ========================= */
    function calculateAge(date){
        const today = new Date();
        const dob = new Date(date);
        let age = today.getFullYear() - dob.getFullYear();
        const m = today.getMonth() - dob.getMonth();
        if(m < 0 || (m===0 && today.getDate() < dob.getDate())) age--;
        return age;
    }

    /* =========================
       STEP VALIDATION
    ========================= */
    function validateStep(index){

        let valid = true;
        const current = steps.eq(index);

        current.find('.field-error').remove();

        current.find('input[required]').each(function(){

            if(!$(this).val().trim()){
                valid=false;
                $(this).after('<small class="text-danger field-error">Required</small>');
            }
        });

        // SA ID validation
        if(index===0){
            const id = $('#idnumber').val().trim();
            if(!isValidSAID(id)){
                $('#id-feedback').text("Invalid SA ID").css('color','red');
                valid=false;
            }
        }

        // AGE + WEIGHT validation
        if(index===2){

            const weight = parseFloat($('input[name="weight"]').val());
            const dob = $('input[name="birthdate"]').val();

            if(weight && dob){

                const age = calculateAge(dob);

                if(age < 13 && weight > 90){
                    valid=false;
                    $('input[name="weight"]').after('<small class="text-danger field-error">Weight exceeds junior limit</small>');
                }

                if(age >=18 && weight < 45){
                    valid=false;
                    $('input[name="weight"]').after('<small class="text-danger field-error">Weight too low</small>');
                }
            }
        }

        // Declaration
        if(index===3 && !$('#acceptDeclaration').is(':checked')){
            alert('Please accept declaration');
            valid=false;
        }

        // Signature
        if(index===4 && $('#signature').val()===''){
            alert('Signature required');
            valid=false;
        }

        if(valid){
            current.addClass('step-complete');
        }

        return valid;
    }

    /* =========================
       NAVIGATION
    ========================= */
    $('.nextStep').click(function(){

        if(!validateStep(step)) return;

        if(step < steps.length-1){
            step++;
            show(step);
        }
    });

    $('.prevStep').click(function(){
        if(step>0){
            step--;
            show(step);
        }
    });

    /* =========================
       AUTO SAVE WIZARD PROGRESS
    ========================= */

    // Trigger autosave on any change
    $('#profileWizard input, #profileWizard textarea, #profileWizard select')
    .on('keyup change', autosaveDraft);


    /* ===== PRODUCTION SIGNATURE PAD ===== */

    const canvas = document.getElementById('signature-pad');
    const signatureInput = document.getElementById('signature');

    let signaturePad = null;

    function initSignaturePad(){

        if(typeof SignaturePad === "undefined"){
            console.error("SignaturePad library not loaded.");
            return;
        }

        const ratio = Math.max(window.devicePixelRatio || 1, 1);

        canvas.width  = canvas.offsetWidth * ratio;
        canvas.height = canvas.offsetHeight * ratio;

        const ctx = canvas.getContext("2d");
        ctx.setTransform(1,0,0,1,0,0);
        ctx.scale(ratio, ratio);

        if(signaturePad) return; // prevent duplicate init

            signaturePad = new SignaturePad(canvas,{
            minWidth:1,
            maxWidth:2.5
        });
    }

    function updateSignature(){
        if(signaturePad && !signaturePad.isEmpty()){
            signatureInput.value = signaturePad.toDataURL("image/png");
        }else{
            signatureInput.value = "";
        }
    }

    /* ===== SUBMIT HANDLER MUST BE INSIDE ===== */

    $('#profileWizard').on('submit', function(e){

        if(!$('#acceptDeclaration').is(':checked')){
            e.preventDefault();
            alert('Please accept the declaration first.');
            return false;
        }

        updateSignature();

        if(!signatureInput.value){
            e.preventDefault();
            alert('Please provide your signature.');
            return false;
        }

    });

    function autosaveDraft(){

        let data = $('#profileWizard').serializeArray();

        data.push({
            name:'last_step',
            value:step
        });

        setStatus('saving');

        $.ajax({
            url:"<?= site_url('player/profile/autosave') ?>",
            method:"POST",
            data:data,
            success:function(){
                setStatus('saved');
            },
            error:function(){
                setStatus('error');
            }
        });
    }

    /* =========================
       SMART DIRTY AUTOSAVE
    ========================= */

    let autosaveTimer = null;
    let lastSnapshot = null;
    let saving = false;

    const autosaveIndicator = $('#autosaveIndicator');

    function setStatus(state){

        autosaveIndicator.removeClass('saving saved error');

        if(state==='saving'){
            autosaveIndicator.text('Saving...');
            autosaveIndicator.addClass('saving');
        }

        if(state==='saved'){
            autosaveIndicator.text('Saved ✓');
            autosaveIndicator.addClass('saved');
        }

        if(state==='error'){
            autosaveIndicator.text('Connection lost');
            autosaveIndicator.addClass('error');
        }
    }

    function autosaveDraft(){

        if(saving) return;

        let data = $('#profileWizard').serializeArray();

        data.push({
            name:'last_step',
            value:step
        });

        const snapshot = JSON.stringify(data);

        /* ===== SAVE LOCAL BACKUP FIRST ===== */
        localStorage.setItem(LOCAL_KEY, snapshot);

        /* ===== DIRTY CHECK ===== */
        if(snapshot === lastSnapshot){
            return;
        }

        lastSnapshot = snapshot;
        saving = true;

        setStatus('saving');

        $.ajax({
            url:"<?= site_url('player/profile/autosave') ?>",
            method:"POST",
            data:data,
            success:function(){
                setStatus('saved');
                saving = false;

                // ✅ server saved → remove local backup
                localStorage.removeItem(LOCAL_KEY);
            },
            error:function(){
                setStatus('error');
                saving = false;
            }
        });
    }

    /* ===== DEBOUNCE TRIGGER ===== */
    $('#profileWizard').on('keyup change','input,textarea,select',function(){

        clearTimeout(autosaveTimer);

        autosaveTimer = setTimeout(function(){
            autosaveDraft();
        },900); // wait until typing stops
    });

    window.addEventListener('online', function(){

        const offlineData = localStorage.getItem(LOCAL_KEY);

        if(offlineData){
            console.log('Connection restored. Syncing draft...');
            autosaveDraft();
        }
    });

});

/* =========================
   PLAYER PHOTO EDITOR
========================= */

document.addEventListener("DOMContentLoaded", function () {
    // Use either camera or gallery input
    const photoCamera = document.getElementById('photo_camera');
    const photoGallery = document.getElementById('photo_gallery');
    const preview = document.getElementById('photoPreview');

    function handlePhotoInput(input) {
        input.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (!file) return;
            if (!file.type.startsWith("image/")) {
                alert("Please select a valid image file.");
                input.value = "";
                return;
            }
            if (file.size > 5 * 1024 * 1024) {
                alert("Image must be smaller than 5MB.");
                input.value = "";
                return;
            }
            const reader = new FileReader();
            reader.onload = function (event) {
                preview.src = event.target.result;
            };
            reader.readAsDataURL(file);
        });
    }
    handlePhotoInput(photoCamera);
    handlePhotoInput(photoGallery);

    // On form submit, copy whichever file is selected to a hidden main input for server upload
    const form = document.getElementById('profileWizard');
    form.addEventListener('submit', function(e) {
        // Remove any previous hidden input
        let existing = document.getElementById('photo_main');
        if(existing) existing.remove();
        let fileInput = photoCamera.files[0] ? photoCamera : photoGallery;
        if(fileInput.files.length) {
            const mainInput = document.createElement('input');
            mainInput.type = 'file';
            mainInput.name = 'photo';
            mainInput.id = 'photo_main';
            mainInput.style.display = 'none';
            form.appendChild(mainInput);
            // Copy file to main input using DataTransfer
            const dt = new DataTransfer();
            dt.items.add(fileInput.files[0]);
            mainInput.files = dt.files;
        }
    });
});

document.addEventListener("DOMContentLoaded", function(){

    const input   = document.getElementById("photo");
    const preview = document.getElementById("photoPreview");

    if(!input) return;

    input.addEventListener("change", function(){

        const file = this.files[0];
        if(!file) return;

        if(!file.type.startsWith("image/")){
            alert("Please select a valid image.");
            this.value="";
            return;
        }

        const reader = new FileReader();

        reader.onload = function(e){

            const img = new Image();

            img.onload = function(){

                const canvas = document.createElement("canvas");
                const ctx = canvas.getContext("2d");

                const MAX = 800;

                let width = img.width;
                let height = img.height;

                if(width > height){
                    if(width > MAX){
                        height *= MAX / width;
                        width = MAX;
                    }
                } else {
                    if(height > MAX){
                        width *= MAX / height;
                        height = MAX;
                    }
                }

                canvas.width = width;
                canvas.height = height;

                ctx.drawImage(img,0,0,width,height);

                canvas.toBlob(function(blob){

                    const compressedFile = new File([blob], file.name, {
                        type:"image/jpeg",
                        lastModified:Date.now()
                    });

                    const container = new DataTransfer();
                    container.items.add(compressedFile);
                    input.files = container.files;

                    preview.src = URL.createObjectURL(blob);

                }, "image/jpeg", 0.75);

            };

            img.src = e.target.result;
        };

        reader.readAsDataURL(file);
    });

});

async function autoCropFace(image){

const detections = await faceapi.detectSingleFace(image);

if(!detections) return;

const box = detections.box;

const canvas = document.createElement("canvas");
const ctx = canvas.getContext("2d");

canvas.width = box.width;
canvas.height = box.height;

ctx.drawImage(
image,
box.x, box.y,
box.width, box.height,
0,0,
box.width,box.height
);

return canvas.toDataURL("image/jpeg",0.8);

}

</script>



<?= $this->endSection() ?>
