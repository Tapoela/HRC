<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<h1 class="mb-3">Users</h1>

<div>
    
    <a href="/admin/users/create" class="btn btn-primary mb-3">
      <i class="fas fa-plus"></i> Add User
    </a>

</div>
<div>

     <a href="<?= base_url('admin/users/print-cards') ?>"
       class="btn btn-success">

       Print All Player Cards
    </a>

</div>

<div>
    <table class="table table-bordered table-striped">
    <thead>
    <tr>
      <th>Photo</th>
      <th>Name</th>
      <th>Email</th>
      <th>Player Status</th>
      <th>Role</th>
      <th>Status</th>
      <th>Profile</th>
    </tr>
    </thead>

    <tbody>
    <?php foreach($users as $u): ?>
    <tr id="user-row-<?= $u['id'] ?>">
    <td>
    <?php
    $photo = !empty($u['photo_thumb'])
        ? base_url('uploads/' . $u['photo_thumb'])
        : base_url('uploads/defaults/avatar.png');
    ?>

    <img src="<?= $photo ?>"
         width="40"
         height="40"
         class="rounded-circle"
         style="object-fit:cover;">
    </td>
      <td class="u-name"><?= esc($u['name']) ?></td>
      <td class="u-email"><?= esc($u['email']) ?></td>
      <td class="u-role">
          <span class="badge badge-info"><?= esc($u['role']) ?></span>
      </td>
      <td width="180">

        <?php if($u['active'] == 0): ?>
          <a href="/admin/users/approve/<?= encode_id($u['id']) ?>"
             class="btn btn-success btn-sm">
             <i class="fas fa-check"></i>
          </a>
        <?php else: ?>
        <span class="badge badge-success">Active</span>
        <?php endif; ?>

      </td>
      <td class="u-profile">
        <?php if($u['profile_completed'] == 1): ?>
            <span class="badge badge-success">Completed</span>
        <?php else: ?>
            <span class="badge badge-danger">Incomplete</span>
        <?php endif; ?>
      </td>
      <td width="160">

          <a href="<?= site_url('admin/users/print/'.encode_id($u['id'])) ?>"
             target="_blank"
             class="btn btn-info btn-sm">
             <i class="fas fa-print"></i>
          </a>

          <a href="#"
             class="btn btn-warning btn-sm btn-edit-user"
             data-id="<?= encode_id($u['id']) ?>">
             <i class="fas fa-edit"></i>
          </a>

          <a href="<?= base_url('admin/users/card/'.encode_id($u['id'])) ?>"
            class="btn btn-primary btn-sm">

            Generate Card
          </a>

      </td>

    </tr>
    <?php endforeach ?>
    </tbody>
</div>

</table>

<!-- EDIT USER MODAL -->
<div class="modal fade" id="editUserModal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Edit User</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="modal-body">

        <div id="editLoading" class="text-center" style="display:none;">
            <i class="fas fa-spinner fa-spin fa-2x"></i>
            <p>Loading user...</p>
        </div>

        <div id="unlockWarning"
             class="alert alert-danger"
             style="display:none;">
        <i class="fas fa-exclamation-triangle"></i>
        Admin Override Active — Profile is temporarily unlocked.
        </div>

        <div id="unlockInfo" class="alert alert-warning" style="display:none;"></div>

        <button id="unlockProfileBtn"
                type="button"
                class="btn btn-sm btn-danger mb-2">
        <i class="fas fa-unlock"></i> Unlock Profile
        </button>

        <div id="editFormArea">

          <input type="hidden" id="edit_id">
          <input type="hidden" id="edit_role_id">
          <input type="hidden" id="edit_profile_completed">

          <!-- Tabs -->
          <ul class="nav nav-tabs" id="userTabs">
              <li class="nav-item">
                  <a class="nav-link active" data-toggle="tab" href="#tab-personal">
                      Personal
                  </a>
              </li>

              <li class="nav-item">
                  <a class="nav-link" data-toggle="tab" href="#tab-medical">
                      Medical
                  </a>
              </li>

              <li class="nav-item">
                  <a class="nav-link" data-toggle="tab" href="#tab-rugby">
                      Rugby
                  </a>
              </li>
          </ul>

          <div class="tab-content mt-3">

              <!-- PERSONAL -->
              <div class="tab-pane fade show active" id="tab-personal">

                  <div class="form-group">
                      <label>Name</label>
                      <input type="text" data-field="name" class="form-control">
                  </div>

                  <div class="form-group">
                      <label>Surname</label>
                      <input type="text" data-field="surname" class="form-control">
                  </div>

                  <div class="form-group">
                      <label>Email</label>
                      <input type="email" data-field="email" class="form-control">
                  </div>

                  <div class="form-group">
                      <label>Cell</label>
                      <input type="text" data-field="cell" class="form-control">
                  </div>

                  <div class="form-group">
                      <label>Address</label>
                      <input type="text" data-field="address" class="form-control">
                  </div>

              </div>


              <!-- MEDICAL -->
              <div class="tab-pane" id="tab-medical">

                  <div class="form-group">
                      <label>Medical Aid</label>
                      <input type="text" data-field="med_aid" class="form-control">
                  </div>

                  <div class="form-group">
                      <label>Medical Number</label>
                      <input type="text" data-field="med_no" class="form-control">
                  </div>

                  <div class="form-group">
                      <label>Height</label>
                      <input type="text" data-field="height" class="form-control">
                  </div>

                  <div class="form-group">
                      <label>Weight</label>
                      <input type="text" data-field="weight" class="form-control">
                  </div>

              </div>


              <!-- RUGBY -->
              <div class="tab-pane" id="tab-rugby">

                  <div class="form-group">
                      <label>ID Number</label>
                      <input type="text" data-field="idnumber" class="form-control">
                  </div>

                  <div class="form-group">
                      <label>Birthdate</label>
                      <input type="text" data-field="birthdate" class="form-control">
                  </div>

                  <div class="form-group">
                      <label>Spouse Name</label>
                      <input type="text" data-field="spouse_name" class="form-control">
                  </div>

                  <div class="form-group">
                      <label>Spouse Contact</label>
                      <input type="text" data-field="spouse_tel" class="form-control">
                  </div>

                  <div class="form-group">
                        <label>Team</label>

                        <select name="team" data-field="team" id="team" class="form-control">
                            <option value="">Select Team</option>
                            <option value="1ste">1st Team</option>
                            <option value="2nd">2nd Team</option>
                            <option value="Junior">Junior</option>
                        </select>
                    </div>

              </div>

          </div>

        </div>

      </div>


      <div class="modal-footer">

        <button type="button" id="saveUserBtn" class="btn btn-success">
        Save Changes
        </button>

      </div>


    </div>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>

(function($){

const CURRENT_ADMIN_ID = <?= json_encode(session()->get('user_id') ?? 0) ?>;
let profileUnlocked = false;

function lockProfileFields(lock){
    $('[data-field]').each(function(){

        if($(this).is('select')){
            $(this).prop('disabled', lock);
        }else{
            $(this).prop('readonly', lock);
        }

    });

    if(lock){
        $('#lockBadge').show();
        $('#unlockWarning').hide();
        profileUnlocked = false;
        $('#unlockProfileBtn').show();
        $('#saveUserBtn').prop('disabled', true);
    }else{
        $('#lockBadge').hide();
        $('#unlockWarning').show();
        profileUnlocked = true;
        $('#unlockProfileBtn').hide();
        $('#saveUserBtn').prop('disabled', false);
    }
}

$(function(){   // ✅ SINGLE SAFE READY BLOCK

// ================= OPEN MODAL =================
$(document).on('click','.btn-edit-user', function(e){
    e.preventDefault();

    let id = $(this).data('id');

    $('#editFormArea').hide();
    $('#editLoading').show();

    $('#editUserModal').modal('show');

    $.get("<?= site_url('admin/users/get') ?>/"+id,function(user){

        // 🔥 ROLE BASED TAB VISIBILITY
        if(user.role_id == 3){
            $('a[href="#tab-rugby"]').closest('li').show();
        }else{
            $('#rugby-tab').closest('li').hide();
        }

        if(user.editing_by && user.editing_by != CURRENT_ADMIN_ID){
            alert('This profile is currently being edited by another admin.');
            $('#editUserModal').modal('hide');
            return;
        }

        $('#editLoading').hide();
        $('#editFormArea').show();

        $('#userTabs a[href="#tab-personal"]').tab('show');        

        $('#edit_id').val(user.id);
        $('#edit_role_id').val(user.role_id);
        $('#edit_profile_completed').val(user.profile_completed);

        // 🔥 AUTO FIELD BIND
        $('[data-field]').each(function(){

            let field = $(this).data('field');

            if(user[field] !== undefined){
                $(this).val(user[field]);
            }

        });

        if(user.profile_completed == 1){
            $('#profileBadge')
                .removeClass()
                .addClass('badge badge-success')
                .text('Profile Completed');
        }else{
            $('#profileBadge')
                .removeClass()
                .addClass('badge badge-warning')
                .text('Profile Incomplete');
        }

        if(user.role_id == 3){
            $('#playerFields').show();
        }else{
            $('#playerFields').hide();
        }

        lockProfileFields(user.profile_completed == 1);

    },'json');
});


// ================= UNLOCK =================
$('#unlockProfileBtn').on('click', function(){

    let id = $('#edit_id').val();

    $.post("<?= site_url('admin/users/unlock') ?>/"+id,function(res){

        if(!res.status){
            $('#saveAck')
                .removeClass('alert-success')
                .addClass('alert-danger')
                .html(res.message)
                .show();
            return;
        }

        $('#saveAck')
            .removeClass('alert-danger')
            .addClass('alert-success')
            .html(res.message)
            .fadeIn();

        lockProfileFields(false);

        $('#unlockInfo')
            .show()
            .html('<b>Unlocked by '+res.admin+'</b> at '+res.time);

        $('#user-row-'+id)
            .addClass('table-warning')
            .find('.u-profile')
            .append(' <span class="badge badge-warning">Editing...</span>');

    },'json');

});

// ================= SAVE =================
$('#saveUserBtn').on('click', function(){

    let id = $('#edit_id').val();

    // 🔥 STEP 3 — AUTO BUILD PAYLOAD FROM data-field
    let payload = {};

    $('[data-field]').each(function(){
        payload[$(this).data('field')] = $(this).val();
    });

    $.post("<?= site_url('admin/users/update') ?>/"+id, payload, function(res){

        // 🔥 STEP 4 — ACKNOWLEDGEMENT + VALIDATION MESSAGE
        if(!res.status){
            $('#saveAck')
                .removeClass('alert-success')
                .addClass('alert-danger')
                .html(res.message)
                .show();
            return;
        }

        $('#saveAck')
            .removeClass('alert-danger')
            .addClass('alert-success')
            .html(res.message)
            .fadeIn();

        lockProfileFields(true);

        let row = $('#user-row-'+id);

        row.find('.u-name').text($('#edit_name').val());
        row.find('.u-email').text($('#edit_email').val());

        if(res.profile_completed == 1){
            row.find('.u-profile').html('<span class="badge badge-success">Completed</span>');
        }else{
            row.find('.u-profile').html('<span class="badge badge-danger">Incomplete</span>');
        }

        row.removeClass('table-warning');
        row.find('.badge-warning').remove();

        $('#unlockInfo').hide();

    },'json');

});

}); // ✅ CLOSE READY BLOCK

})(jQuery); // ✅ CLOSE IIFE


</script>

<?= $this->endSection() ?>
