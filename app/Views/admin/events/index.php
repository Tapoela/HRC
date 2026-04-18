<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<div class="container-fluid">
    <h2>Event Calendar</h2>
    <div id="calendar"></div>
    <button class="btn btn-primary mt-3" id="addEventBtn">Add Event</button>

    <!-- Event Modal -->
    <div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <form id="eventForm">
            <div class="modal-header flex-column align-items-start">
              <label for="eventType" class="form-label fw-bold mb-2">Event Type <span class="text-danger">*</span></label>
              <select class="form-select border-primary mb-2" id="eventType" name="type" required>
                <option value="" disabled selected>Select type</option>
                <option value="General Event">General Event</option>
                <option value="Match Day">Match Day</option>
              </select>
              <h5 class="modal-title mt-2" id="eventModalLabel">Event</h5>
              <button type="button" class="btn-close position-absolute end-0 top-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <input type="hidden" name="id" id="eventId">
              <div class="mb-3">
                <label for="eventTitle" class="form-label fw-bold">Title <span class="text-danger">*</span></label>
                <input type="text" class="form-control border-primary" id="eventTitle" name="title" required>
              </div>
              <div class="mb-3">
                <label for="eventStart" class="form-label fw-bold">Start <span class="text-danger">*</span></label>
                <input type="datetime-local" class="form-control border-primary" id="eventStart" name="start" required>
              </div>
              <div class="mb-3">
                <label for="eventEnd" class="form-label fw-bold">End <span class="text-danger">*</span></label>
                <input type="datetime-local" class="form-control border-primary" id="eventEnd" name="end" required>
              </div>
              <div class="mb-3">
                <label for="eventLocation" class="form-label fw-bold">Location <span class="text-danger">*</span></label>
                <input type="text" class="form-control border-primary" id="eventLocation" name="location" required>
              </div>
              <div class="mb-3">
                <label for="eventDescription" class="form-label">Description</label>
                <textarea class="form-control" id="eventDescription" name="description"></textarea>
              </div>
              <!-- Match Day Fields -->
              <div id="matchFields" style="display:none;">
                <div class="mb-3">
                  <label for="matchTeam" class="form-label fw-bold">Team <span class="text-danger">*</span></label>
                  <input type="text" class="form-control border-primary" id="matchTeam" name="team">
                </div>
                <div class="mb-3">
                  <label for="matchOpponent" class="form-label">Opponent</label>
                  <input type="text" class="form-control border-primary" id="matchOpponent" name="opponent">
                </div>
                <div class="mb-3">
                  <label for="matchDate" class="form-label fw-bold">Match Date <span class="text-danger">*</span></label>
                  <input type="date" class="form-control border-primary" id="matchDate" name="match_date">
                </div>
                <div class="mb-3">
                  <label for="matchTime" class="form-label fw-bold">Match Time <span class="text-danger">*</span></label>
                  <input type="time" class="form-control border-primary" id="matchTime" name="match_time">
                </div>
                <div class="mb-3">
                  <label for="matchVenue" class="form-label fw-bold">Venue <span class="text-danger">*</span></label>
                  <input type="text" class="form-control border-primary" id="matchVenue" name="venue">
                </div>
                <div class="mb-3">
                  <label for="matchVenueName" class="form-label fw-bold">Venue Name <span class="text-danger">*</span></label>
                  <input type="text" class="form-control border-primary" id="matchVenueName" name="venue_name">
                </div>
              </div>
              <div id="eventFormErrors" class="text-danger"></div>
              <div class="progress mb-2" id="eventLoadingBar" style="display:none;">
                <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" style="width:100%"></div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" id="cancelEventBtn">Cancel</button>
              <button type="submit" class="btn btn-primary" id="saveEventBtn">Save</button>
              <button type="button" class="btn btn-danger" id="deleteEventBtn" style="display:none;">Delete</button>
            </div>
          </form>
        </div>
      </div>
    </div>
</div>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
let calendar, eventModal, eventForm, deleteEventBtn, saveEventBtn, eventFormErrors;
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    eventModal = new bootstrap.Modal(document.getElementById('eventModal'));
    eventForm = document.getElementById('eventForm');
    deleteEventBtn = document.getElementById('deleteEventBtn');
    saveEventBtn = document.getElementById('saveEventBtn');
    eventFormErrors = document.getElementById('eventFormErrors');
    
    calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        events: '<?= site_url('admin/events/list') ?>',
        selectable: true,
        editable: true,
        eventClick: function(info) {
            showEventModal('edit', info.event);
        },
        dateClick: function(info) {
            showEventModal('create', {start: info.dateStr});
        }
    });
    calendar.render();
    document.getElementById('addEventBtn').onclick = function() {
        showEventModal('create', {});
    };
    eventForm.onsubmit = function(e) {
        e.preventDefault();
        saveEvent();
    };
    deleteEventBtn.onclick = function() {
        deleteEvent();
    };
    document.getElementById('eventType').onchange = function() {
        const isMatch = this.value === 'Match Day';
        document.getElementById('matchFields').style.display = isMatch ? '' : 'none';
        // Set required attributes for match fields
        document.getElementById('matchTeam').required = isMatch;
        document.getElementById('matchDate').required = isMatch;
        document.getElementById('matchTime').required = isMatch;
        document.getElementById('matchVenue').required = isMatch;
        document.getElementById('matchVenueName').required = isMatch;
        // Set required attributes for normal event fields
        document.getElementById('eventTitle').required = !isMatch;
        document.getElementById('eventStart').required = !isMatch;
        document.getElementById('eventEnd').required = !isMatch;
        document.getElementById('eventLocation').required = !isMatch;
    };
    // Reset form and required fields when the modal is closed
    document.getElementById('eventModal').addEventListener('hidden.bs.modal', function() {
        eventForm.reset();
        eventFormErrors.textContent = '';
        document.getElementById('matchFields').style.display = 'none';
        // Reset required attributes
        document.getElementById('matchTeam').required = false;
        document.getElementById('matchDate').required = false;
        document.getElementById('matchTime').required = false;
        document.getElementById('matchVenue').required = false;
        document.getElementById('matchVenueName').required = false;
        document.getElementById('eventTitle').required = true;
        document.getElementById('eventStart').required = true;
        document.getElementById('eventEnd').required = true;
        document.getElementById('eventLocation').required = true;
    });
});

function showEventModal(mode, event) {
    eventForm.reset();
    eventFormErrors.textContent = '';
    document.getElementById('matchFields').style.display = 'none';
    if (mode === 'edit') {
        document.getElementById('eventModalLabel').textContent = 'Edit Event';
        document.getElementById('eventId').value = event.id;
        document.getElementById('eventTitle').value = event.title;
        document.getElementById('eventStart').value = event.start ? event.start.toISOString().slice(0,16) : '';
        document.getElementById('eventEnd').value = event.end ? event.end.toISOString().slice(0,16) : '';
        document.getElementById('eventLocation').value = event.extendedProps.location || '';
        document.getElementById('eventDescription').value = event.extendedProps.description || '';
        document.getElementById('eventType').value = event.extendedProps.type || '';
        if (event.extendedProps.type === 'Match Day') {
            document.getElementById('matchFields').style.display = '';
            document.getElementById('matchTeam').value = event.extendedProps.team || '';
            document.getElementById('matchOpponent').value = event.extendedProps.opponent || '';
            document.getElementById('matchDate').value = event.extendedProps.match_date || '';
            document.getElementById('matchTime').value = event.extendedProps.match_time || '';
            document.getElementById('matchVenue').value = event.extendedProps.venue || '';
            document.getElementById('matchVenueName').value = event.extendedProps.venue_name || '';
        }
        deleteEventBtn.style.display = '';
    } else {
        document.getElementById('eventModalLabel').textContent = 'Add Event';
        document.getElementById('eventId').value = '';
        document.getElementById('eventStart').value = event.start ? event.start.slice(0,16) : '';
        deleteEventBtn.style.display = 'none';
    }
    eventModal.show();
}

function saveEvent() {
    eventFormErrors.textContent = '';
    saveEventBtn.disabled = true;
    document.getElementById('eventLoadingBar').style.display = '';
    let id = document.getElementById('eventId').value;
    let url = id ? '<?= site_url('admin/events/update/') ?>' + id : '<?= site_url('admin/events/create') ?>';
    let method = 'POST';
    let formData = new FormData(eventForm);
    fetch(url, {
        method: method,
        body: formData
    })
    .then(r => r.json().then(data => ({status: r.status, body: data})))
    .then(res => {
        saveEventBtn.disabled = false;
        document.getElementById('eventLoadingBar').style.display = 'none';
        if (res.status === 200 && res.body.status === 'success') {
            eventModal.hide();
            calendar.refetchEvents();
        } else {
            eventFormErrors.textContent = Object.values(res.body.errors || {}).join(' ');
        }
    })
    .catch(() => {
        saveEventBtn.disabled = false;
        document.getElementById('eventLoadingBar').style.display = 'none';
        eventFormErrors.textContent = 'An error occurred.';
    });
}

function deleteEvent() {
    let id = document.getElementById('eventId').value;
    if (!id) return;
    if (!confirm('Delete this event?')) return;
    deleteEventBtn.disabled = true;
    fetch('<?= site_url('admin/events/delete/') ?>' + id, {
        method: 'POST'
    })
    .then(r => r.json().then(data => ({status: r.status, body: data})))
    .then(res => {
        deleteEventBtn.disabled = false;
        if (res.status === 200 && res.body.status === 'success') {
            eventModal.hide();
            calendar.refetchEvents();
        } else {
            eventFormErrors.textContent = Object.values(res.body.errors || {}).join(' ');
        }
    })
    .catch(() => {
        deleteEventBtn.disabled = false;
        eventFormErrors.textContent = 'An error occurred.';
    });
}

document.getElementById('cancelEventBtn').onclick = function() {
    eventModal.hide();
};
</script>
<?= $this->endSection() ?>
