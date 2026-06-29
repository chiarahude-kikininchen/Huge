
<html>
<h1>Kalender</h1>

<form action="<?= Config::get('URL'); ?>calendar/create" method="post">
    <input type="text" name="title" placeholder="Titel" required>

    <input type="date" name="event_date" required>

    <input type="time" name="event_time">

    <textarea name="description" placeholder="Beschreibung"></textarea>

    <button type="submit">Termin speichern</button>
</form>

<hr>

<h2>Meine Termine</h2>

<?php foreach ($this->events as $event): ?>
    <div class="calendar-event">
        <strong><?= htmlspecialchars($event->title); ?></strong><br>

        <?= htmlspecialchars($event->event_date); ?>
        <?= htmlspecialchars($event->event_time); ?><br>

        <?= htmlspecialchars($event->description); ?><br>

        <a href="<?= Config::get('URL'); ?>calendar/delete/<?= $event->event_id; ?>">
            Löschen
        </a>
    </div>
<?php endforeach; ?>
</html>

