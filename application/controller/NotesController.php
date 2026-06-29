<?php

class NotesController extends Controller {
    public function index()
    {
        $search = Request::get('search');

        if (!empty($search)) {
            $notes = NotesModel::searchNotes($search);
        } else {
            $notes = NotesModel::getAllNotes();
        }

        $this->View->render('notes/index', [
            'notes' => $notes
        ]);
    }


    public function create()
    {
        $title = trim(Request::post('title'));
        $content = trim(Request::post('content'));

        if (empty($title) || empty($content)) {
            Session::add('feedback_negative', 'Titel und Inhalt dürfen nicht leer sein.');
            Redirect::to('notes');
            return;
        }

        if (strlen($title) > 255) {
            Session::add('feedback_negative', 'Der Titel darf maximal 255 Zeichen haben.');
            Redirect::to('notes');
            return;
        }

        NotesModel::createNote($title, $content);

        Redirect::to('notes');
    }

    public function delete($notesId) {
        NotesModel::deleteNote ($notesId);
        Redirect::to('notes');
    }

    public function edit($notesId) {
        $note = NotesModel::getNote($notesId);

        $this->View->render('notes/edit', [
            'note' => $note
            ]);
    }

    public function editSave($noteId)
    {
        NotesModel::updateNote(
            $noteId,
            Request::post('title'),
            Request::post('content')
        );

        Redirect::to('notes');
    }

    public function updateOrder()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        NotesModel::updateOrder($data);
    }

    public function search() {
        $searchTerm = Request::post('searchTerm');
        $notes = NotesModel::searchNotes($searchTerm);
    }
}