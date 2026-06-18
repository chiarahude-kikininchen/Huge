<?php


class GalleryModel
{
    public static function uploadPicture()
    {
        $userId = Session::get('user_id');

        // Prüfen, die ob Datei überhaupt existiert
        if (!isset($_FILES['datei'])) {
            die('Keine Datei ausgewählt!');
        }

        // 1) Upload-Fehler prüfen
        if ($_FILES['datei']['error'] !== UPLOAD_ERR_OK) {
            die('Upload fehlgeschlagen!');
        }

        // 2) Dateigröße prüfen: max. 5 MB
        if ($_FILES['datei']['size'] > 5 * 1024 * 1024) {
            die('Datei zu groß!');
        }

        // 3) MIME-Type prüfen
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($_FILES['datei']['tmp_name']);

        $erlaubt = ['image/jpeg', 'image/png'];

        if (!in_array($mime, $erlaubt)) {
            die('Dateityp nicht erlaubt!');
        }

        // 4) Sicheren Dateinamen erzeugen
        $originalName = basename($_FILES['datei']['name']);
        $safeName = preg_replace('/[^a-zA-Z0-9._-]/', '_', $originalName);

        // damit keine Datei überschrieben wird
        $newName = time() . '_' . $safeName;

        // 5) User-Ordner außerhalb von public
        $folder = dirname(__DIR__, 2) . '/userPictures/' . $userId . '/';

        if (!is_dir($folder)) {
            mkdir($folder, 0777, true);
        }

        $ziel = $folder . $newName;

        // 6) Datei speichern
        if (!move_uploaded_file($_FILES['datei']['tmp_name'], $ziel)) {
            die('Datei konnte nicht gespeichert werden!');
        }

        // 7) In DB speichern
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "INSERT INTO files (filename, original_filename, mime_type, size)
                VALUES (:filename, :original_filename, :mime_type, :size)";

        $query = $database->prepare($sql);
        $query->execute([
            ':filename' => $newName,
            ':original_filename' => $originalName,
            ':mime_type' => $mime,
            ':size' => $_FILES['datei']['size']
        ]);
    }

    public static function getPictures()
    {
        $userId = Session::get('user_id');

        $folder = dirname(__DIR__, 2) . '/userpictures/' . $userId . '/';

        if (!is_dir($folder)) {
            return [];
        }

        $files = scandir($folder);

        $pictures = [];

        foreach ($files as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            $pictures[] = (object)[
                'filename' => $file,
                'original_filename' => $file
            ];
        }

        return $pictures;
    }

    public static function showPictures($filename) {
        $userID = Session::get('user_id');

        $filename = basename($filename);

        $path = dirname(__DIR__, 2) . '/userPictures/' . $userID . '/' . $filename;

        if (!file_exists($path)) {
            die('Datei existiert nicht!');
        }

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($path);

        if (!in_array($mime, ['image/jpeg', 'image/png'])) {
            die('Dateityp nicht erlaubt!');
        }

        header('Content-Type: ' . $mime);
        header('Content-Length: ' . filesize($path));

        readfile($path);
        exit;
    }

    public static function deletePicture($filename) {
        $userID = Session::get('user_id');

        $filename = basename($filename);

        $path = dirname(__DIR__, 2) . '/userPictures/' . $userID . '/' . $filename;

        if (!file_exists($path)) {
            die('Datei nicht gefunden');
        }

        unlink($path);

        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "DELETE FROM files WHERE filename = :filename";
        $query = $database->prepare($sql);
        $query->execute([':filename' => $filename]);
    }
}