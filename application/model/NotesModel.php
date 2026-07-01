<?php

class NotesModel
{
    public static function getAllNotes()
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT *
                FROM notes
                WHERE user_id = :user_id
                ORDER BY sort_order ASC";

        $query = $database->prepare($sql);
        $query->execute([
            ':user_id' => Session::get('user_id')
        ]);

        return $query->fetchAll();
    }

    public static function getNote($noteId)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT *
            FROM notes
            WHERE note_id = :note_id
            AND user_id = :user_id";

        $query = $database->prepare($sql);

        $query->execute([
            ':note_id' => $noteId,
            ':user_id' => Session::get('user_id')
        ]);

        return $query->fetch();
    }

    public static function createNote($title, $content)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "INSERT INTO notes (user_id, title, content, sort_order)
            VALUES (:user_id, :title, :content, :sort_order)";

        $query = $database->prepare($sql);
        $query->execute([
            ':user_id' => Session::get('user_id'),
            ':title' => $title,
            ':content' => $content,
            ':sort_order' => 0
        ]);
    }

    public static function updateNote($noteId, $title, $content)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "UPDATE notes
            SET title = :title,
                content = :content
            WHERE note_id = :note_id
            AND user_id = :user_id";

        $query = $database->prepare($sql);

        $query->execute([
            ':title' => $title,
            ':content' => $content,
            ':note_id' => $noteId,
            ':user_id' => Session::get('user_id')
        ]);
    }

    public static function deleteNote($noteId)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "DELETE FROM notes
            WHERE note_id = :note_id
            AND user_id = :user_id";

        $query = $database->prepare($sql);
        $query->execute([
            ':note_id' => $noteId,
            ':user_id' => Session::get('user_id')
        ]);
    }

    public static function updateOrder($notes)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        foreach ($notes as $note) {
            $sql = "UPDATE notes
                SET sort_order = :sort_order
                WHERE note_id = :note_id
                AND user_id = :user_id";

            $query = $database->prepare($sql);
            $query->execute([
                ':sort_order' => $note['position'],
                ':note_id' => $note['id'],
                ':user_id' => Session::get('user_id')
            ]);
        }
    }

    public static function searchNotes($search)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT *
            FROM notes
            WHERE user_id = :user_id
            AND (title LIKE :search OR content LIKE :search)
            ORDER BY sort_order ASC";

        $query = $database->prepare($sql);
        $query->execute([
            ':user_id' => Session::get('user_id'),
            ':search' => '%' . $search . '%'
        ]);

        return $query->fetchAll();
    }

    public static function updatePosition($noteId, $posX, $posY)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "UPDATE notes 
            SET pos_x = :pos_x, pos_y = :pos_y 
            WHERE note_id = :note_id 
            AND user_id = :user_id";

        $query = $database->prepare($sql);
        $query->execute([
            ':pos_x' => $posX,
            ':pos_y' => $posY,
            ':note_id' => $noteId,
            ':user_id' => Session::get('user_id')
        ]);
    }
}