<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>

<section class="page container">
  <header class="page-header">
    <h2>Upcoming Events</h2>
  </header>
  <div id="eventList"></div>
</section>
<script>
document.addEventListener('DOMContentLoaded', function() {
    fetch('<?= site_url('api/events') ?>').then(r=>r.json()).then(events => {
        if (!events.length) {
            document.getElementById('eventList').innerHTML = '<p>No upcoming events scheduled.</p>';
            return;
        }
        let currentMonth = '';
        let currentDate = '';
        let html = '';
        events.forEach(ev => {
            const dateObj = new Date(ev.start);
            const monthHeader = dateObj.toLocaleString('default', { month: 'long', year: 'numeric' });
            const dateHeader = dateObj.toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
            if (monthHeader !== currentMonth) {
                currentMonth = monthHeader;
                html += `<h3 class="fixture-month">${monthHeader.toUpperCase()}</h3>`;
            }
            if (dateHeader !== currentDate) {
                currentDate = dateHeader;
                html += `<div class="fixture-date-row"><strong>${currentDate}</strong></div>`;
            }
            html += `<div class="fixture-card">
                <div class="fixture-match">${ev.title}</div>
                <div class="fixture-time">${ev.start ? ev.start.substring(11,16) : 'TBA'}</div>
                <div class="fixture-venue">
                  ${ev.location || ''}
                </div>
                ${ev.description ? `<div class="fixture-description">${ev.description}</div>` : ''}
            </div>`;
        });
        document.getElementById('eventList').innerHTML = html;
    });
});
</script>
<?= $this->endSection() ?>
