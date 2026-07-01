<h1>Meine Notizen</h1>

<form method="get" action="<?= Config::get('URL'); ?>notes/index">
    <input type="text" name="search" placeholder="Notizen suchen..."
           value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
    <button type="submit">Suchen</button>
    <a href="<?= Config::get('URL'); ?>notes">Zurücksetzen</a>
</form>

<button type="button" onclick="openCreatePopup()">
    Neue Notiz
</button>

<div id="notesBoard">
    <?php foreach ($this->notes as $note): ?>
        <div class="note"
             data-id="<?= $note->note_id ?>"
             style="left: <?= isset($note->pos_x) ? $note->pos_x : 0 ?>px; top: <?= isset($note->pos_y) ? $note->pos_y : 0 ?>px;">

            <div class="note-header">
                <span class="note-title">
                    <?= htmlspecialchars($note->title) ?>
                </span>
            </div>

            <div class="note-content">
                <?= nl2br(htmlspecialchars($note->content)) ?>
            </div>

            <div class="note-actions">
                <button type="button"
                        onclick="openEditPopup(
                        <?= $note->note_id ?>,
                                '<?= htmlspecialchars($note->title, ENT_QUOTES) ?>',
                                '<?= htmlspecialchars($note->content, ENT_QUOTES) ?>'
                                )">
                    Bearbeiten
                </button>

                <a href="<?= Config::get('URL'); ?>notes/delete/<?= $note->note_id ?>"
                   onclick="return confirm('Notiz wirklich löschen?');">
                    Löschen
                </a>
            </div>

        </div>
    <?php endforeach; ?>
</div>

<div id="createPopup" style="display:none;">
    <form id="createForm" method="post" action="<?= Config::get('URL'); ?>notes/create">
        <h2>Neue Notiz</h2>

        <input type="text" name="title" placeholder="Titel">

        <textarea name="content" placeholder="Notiz schreiben..."></textarea>

        <button type="submit">Speichern</button>
        <button type="button" onclick="closeCreatePopup()">Abbrechen</button>
    </form>
</div>

<div id="editPopup" style="display:none;">
    <form id="editForm" method="post">
        <h2>Notiz bearbeiten</h2>

        <input type="text" id="editTitle" name="title">

        <textarea id="editContent" name="content"></textarea>

        <button type="submit">Speichern</button>
        <button type="button" onclick="closeEditPopup()">Abbrechen</button>
    </form>
</div>

<style>
    body {
        font-family: "Inconsolata", monospace;
        background-color: #f5f5f5;
    }

    #notesBoard {
        position: relative;
        width: 100%;
        min-height: 700px;
        margin-top: 25px;
        background: #f5f5f5;
        overflow: hidden;
        border: 2px dashed black;
    }

    .note {
        position: absolute;
        background: white;
        border: 2px solid black;
        box-shadow: 3px 3px 0 black;
        width: 260px;
        min-height: 190px;
        font-family: "Inconsolata", monospace;
        cursor: grab;
        user-select: none;
    }

    .note:active {
        cursor: grabbing;
    }

    .note-header {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 26px;
        padding: 2px 6px;
        border-bottom: 2px solid black;
        background: repeating-linear-gradient(
                to bottom,
                black 0px,
                black 2px,
                white 2px,
                white 4px
        );
        cursor: grab;
    }

    .note-title {
        background: white;
        padding: 0 10px;
        font-weight: bold;
        font-size: 14px;
    }

    .note-content {
        padding: 14px;
        min-height: 100px;
        font-size: 14px;
        line-height: 1.3;
        white-space: normal;
    }

    .note-actions {
        border-top: 1px solid black;
        padding: 8px;
        display: flex;
        gap: 8px;
        justify-content: flex-end;
    }

    .note button,
    .note a,
    form button,
    button,
    form a {
        background: white;
        border: 1px solid black;
        padding: 3px 7px;
        color: black;
        text-decoration: none;
        font-family: monospace;
        font-size: 12px;
        cursor: pointer;
    }

    .note button:hover,
    .note a:hover,
    form button:hover,
    button:hover,
    form a:hover {
        background: black;
        color: white;
    }

    form {
        margin-bottom: 12px;
    }

    input,
    textarea {
        border: 1px solid black;
        padding: 5px;
        font-family: monospace;
    }

    #editPopup,
    #createPopup {
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.4);
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }

    #editForm,
    #createForm {
        background: white;
        padding: 25px;
        border: 2px solid black;
        box-shadow: 4px 4px 0 black;
        width: 350px;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    #editContent,
    #createForm textarea {
        min-height: 120px;
    }

    mark {
        background: yellow;
        color: black;
    }
</style>

<script>
    function openCreatePopup() {
        document.getElementById('createPopup').style.display = 'flex';
    }

    function closeCreatePopup() {
        document.getElementById('createPopup').style.display = 'none';
    }

    function openEditPopup(id, title, content) {
        document.getElementById('editPopup').style.display = 'flex';

        document.getElementById('editTitle').value = title;
        document.getElementById('editContent').value = content;

        document.getElementById('editForm').action =
            "<?= Config::get('URL'); ?>notes/editSave/" + id;
    }

    function closeEditPopup() {
        document.getElementById('editPopup').style.display = 'none';
    }

    const board = document.getElementById('notesBoard');
    const notes = document.querySelectorAll('.note');

    notes.forEach((note, index) => {
        let isDragging = false;
        let offsetX = 0;
        let offsetY = 0;

        if (note.style.left === '0px' && note.style.top === '0px') {
            note.style.left = (index * 280) + 'px';
            note.style.top = '0px';
        }

        note.addEventListener('mousedown', function(e) {
            if (e.target.tagName === 'BUTTON' || e.target.tagName === 'A') {
                return;
            }

            isDragging = true;

            const noteRect = note.getBoundingClientRect();

            offsetX = e.clientX - noteRect.left;
            offsetY = e.clientY - noteRect.top;

            note.style.zIndex = 999;
        });

        document.addEventListener('mousemove', function(e) {
            if (!isDragging) return;

            const boardRect = board.getBoundingClientRect();

            let x = e.clientX - boardRect.left - offsetX;
            let y = e.clientY - boardRect.top - offsetY;

            if (x < 0) x = 0;
            if (y < 0) y = 0;

            note.style.left = x + 'px';
            note.style.top = y + 'px';
        });

        document.addEventListener('mouseup', function() {
            if (!isDragging) return;

            isDragging = false;
            note.style.zIndex = 1;

            fetch("<?= Config::get('URL'); ?>notes/updatePosition", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    note_id: note.dataset.id,
                    pos_x: parseInt(note.style.left),
                    pos_y: parseInt(note.style.top)
                })
            });
        });
    });
</script>