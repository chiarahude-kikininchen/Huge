<h1>Meine Galerie</h1>

<form action="<?= Config::get('URL'); ?>gallery/upload"
      method="post"
      enctype="multipart/form-data">

    <input type="file" name="datei" accept=".jpg,.jpeg,.png">
    <button type="submit">Hochladen</button>
</form>

<br>

<button type="button" onclick="deleteSelectedImage()">
    Ausgewähltes Bild löschen
</button>

<input type="hidden" id="selectedFile">

<section class="gallery">
    <?php foreach ($this->pictures as $picture): ?>
        <figure tabindex="1"
                onclick="selectImage(this, '<?= htmlspecialchars($picture->filename); ?>')">

            <img src="<?= Config::get('URL'); ?>gallery/show/<?= urlencode($picture->filename); ?>"
                 alt="<?= htmlspecialchars($picture->original_filename); ?>">

            <figcaption>
                <?= htmlspecialchars($picture->original_filename); ?>
            </figcaption>
        </figure>
    <?php endforeach; ?>
</section>

<script>
    function selectImage(element, filename) {
        document.querySelectorAll('.gallery figure').forEach(function (figure) {
            figure.classList.remove('selected');
        });

        element.classList.add('selected');
        document.getElementById('selectedFile').value = filename;
    }

    function deleteSelectedImage() {
        const filename = document.getElementById('selectedFile').value;

        if (!filename) {
            alert('Bitte zuerst ein Bild auswählen.');
            return;
        }

        if (confirm('Bild wirklich löschen?')) {
            window.location.href = "<?= Config::get('URL'); ?>gallery/delete/" + encodeURIComponent(filename);
        }
    }
</script>

<style>
    .gallery {
        display: grid;
        grid-template-columns: repeat(3, 160px);
        gap: 15px;
        margin-top: 20px;
    }

    .gallery figure {
        margin: 0;
        width: 160px;
        height: 160px;
        overflow: hidden;
        position: relative;
        cursor: pointer;
    }

    .gallery img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: 0.3s ease;
    }

    .gallery figure:hover img,
    .gallery figure:focus img {
        transform: scale(1.2);
    }

    .gallery figure.selected {
        outline: 4px solid black;
    }

    .gallery figcaption {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        padding: 5px;
        box-sizing: border-box;
        background: rgb(0 0 0 / 0.5);
        color: white;
        text-align: center;
        font-size: 12px;
        opacity: 0;
        transition: 0.3s ease;
    }

    .gallery figure:hover figcaption,
    .gallery figure:focus figcaption,
    .gallery figure.selected figcaption {
        opacity: 1;
    }
</style>