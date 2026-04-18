<?= $this->extend('public/layouts/main') ?>
<?= $this->section('content') ?>

<section class="ultra-register-hero">

	<div class="container">

		<div class="wizard-card">

			<?php if(session()->getFlashdata('success')): ?>

			    <div class="register-success text-center">

			        <h2 class="success-title">🏉 Registration Submitted</h2>

			        <p class="mt-3">
			            Thank you for joining Heidelberg Rugby Club.<br>
			            Your application has been received and is pending approval.
			        </p>

			        <p class="mt-2">
			            You will receive an email confirmation shortly.
			        </p>

			        <a href="/" class="btn btn-ultra-rugby mt-4">
			            Back to Home
			        </a>

			    </div>

			<?php else: ?>


			<h2 class="wizard-title text-center">Join Heidelberg Rugby Club</h2>

			<div class="wizard-progress">
			    <div class="wizard-progress-bar"></div>
			</div>

			<div class="wizard-steps-indicator">
			    <span class="step-dot active">1</span>
			    <span class="step-dot">2</span>
			    <span class="step-dot">3</span>
			</div>

			<form method="post" action="<?= site_url('register/store') ?>" id="registerForm">
				<?= csrf_field() ?>

				<!-- STEP 1 -->
				<div class="wizard-step active">

					<div class="row g-3">

						<div class="col-md-6">
							<label>First Name</label>
							<input name="fname" class="form-control ultra-input" required>
						</div>

						<div class="col-md-6">
							<label>Surname</label>
							<input name="surname" class="form-control ultra-input" required>
						</div>

						<div class="col-md-12 text-end mt-3">
							<button type="button" class="btn btn-ultra-rugby nextStep">Next</button>
						</div>

					</div>

				</div>

				<!-- STEP 2 -->
				<div class="wizard-step">

					<div class="row g-3">

					<div class="col-md-12">
						<label>Email</label>
						<input id="email" name="email" class="form-control ultra-input" required>
						<small id="emailFeedback"></small>
					</div>

					<div class="col-md-12">
						<label>Password</label>
						<input type="password" id="password" name="password" class="form-control ultra-input" required>
						<small id="passwordStrength"></small>
					</div>

					<div class="d-flex justify-content-between mt-3">
						<button type="button" class="btn btn-secondary prevStep">Back</button>
						<button type="button" class="btn btn-ultra-rugby nextStep">Next</button>
					</div>

					</div>

				</div>

				<!-- STEP 3 -->
				<div class="wizard-step">

				<div class="row g-3">

				<div class="col-md-12">

					<div class="form-group">
					    <label>Register As</label>

					    <?php if (!empty($roles) && is_array($roles)): ?>
					    <select name="role_id" class="form-control ultra-input" required>

					        <option value="">Select...</option>

					        <?php foreach($roles as $role): ?>

					            <option value="<?= isset($role->id) ? $role->id : '' ?>">
					                <?= isset($role->name) ? esc($role->name) : '' ?>
					            </option>

					        <?php endforeach; ?>

					    </select>
					    <?php else: ?>
					        <div class="alert alert-warning">No roles available.</div>
					    <?php endif; ?>

					</div>

					<div class="form-group">
					    <label>Player Category</label>

					    <select name="division_id" class="form-control ultra-input" required>

					        <option value="">Select...</option>

					        <?php foreach($divisions as $division): ?>

					            <option value="<?= $division['id'] ?>">
					                <?= esc($division['name']) ?>
					            </option>

					        <?php endforeach; ?>

					    </select>

					</div>

				<label>Human Check: What sport does Heidelberg Rugby Club play?</label>
				<input type="text" name="human_check" class="form-control ultra-input" required>
				</div>

				<div class="d-flex justify-content-between mt-3">
				<button type="button" class="btn btn-ultra-rugby-outline prevStep">Back</button>
				<button id="registerBtn" class="btn btn-ultra-rugby" type="submit">Register Player</button>
				</div>

				</div>

				</div>

			</form>

		</div>

		<?php endif; ?>

	</div>
</section>


<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>

document.addEventListener('DOMContentLoaded', function() {
  // Force the first wizard step to be active
  document.querySelectorAll('.wizard-step').forEach((el, i) => {
    el.classList.toggle('active', i === 0);
  });

  let stepIndex = 0;
  const steps = document.querySelectorAll('.wizard-step');
  const dots  = document.querySelectorAll('.step-dot');
  const bar   = document.querySelector('.wizard-progress-bar');

  function showStep(i){
      steps.forEach(s => s.classList.remove('active'));
      dots.forEach(d => d.classList.remove('active'));
      if(steps[i]) steps[i].classList.add('active');
      if(dots[i])  dots[i].classList.add('active');
      let percent = ((i+1) / steps.length) * 100;
      bar.style.width = percent + '%';
      document.querySelector('.wizard-card')
          .scrollIntoView({behavior:'smooth', block:'start'});
  }

  document.querySelectorAll('.nextStep').forEach(btn=>{
      btn.addEventListener('click',function(){
          if(stepIndex < steps.length-1){
              stepIndex++;
              showStep(stepIndex);
          }
      });
  });

  document.querySelectorAll('.prevStep').forEach(btn=>{
      btn.addEventListener('click',function(){
          if(stepIndex > 0){
              stepIndex--;
              showStep(stepIndex);
          }
      });
  });

  document.querySelector('[name="role_id"]').addEventListener('change',function(){
      const division = document.querySelector('[name="division_id"]');
      if(this.options[this.selectedIndex].text.toLowerCase() === 'coach'){
          division.disabled = true;
      }else{
          division.disabled = false;
      }
  });

  document.getElementById('email')?.addEventListener('keyup', function(){
    if(stepIndex === 1){
        const fb = document.getElementById('emailFeedback');
        if(fb.innerText.includes('already')){
            return;
        }
    }
    let emailTimer;
    document.getElementById('email')?.addEventListener('keyup', function(){
      clearTimeout(emailTimer);
      emailTimer = setTimeout(()=>{
        let email = this.value;
        if(email.length < 5) return;
        fetch('/register/check-email',{
            method:'POST',
            headers:{'Content-Type':'application/x-www-form-urlencoded'},
            body:'email='+encodeURIComponent(email)
        })
        .then(r=>r.json())
        .then(res=>{
            const fb = document.getElementById('emailFeedback');
            const registerBtn = document.getElementById('registerBtn');
            if(res.exists){
                fb.innerHTML='❌ Email already registered';
                fb.style.color='red';
                registerBtn.disabled=true;
            }else{
                fb.innerHTML='✔ Email available';
                fb.style.color='limegreen';
                registerBtn.disabled=false;
            }
        });
      },500);
    });
  });

  document.getElementById('password')?.addEventListener('keyup',function(){
    let val=this.value;
    let strength="Weak",color="red";
    if(val.length>7 && /[A-Z]/.test(val) && /\d/.test(val)){
        strength="Strong";color="limegreen";
    }else if(val.length>5){
        strength="Medium";color="orange";
    }
    let s=document.getElementById('passwordStrength');
    if(s){
        s.innerText="Strength: "+strength;
        s.style.color=color;
    }
  });

});
</script>

<?= $this->endSection() ?>



