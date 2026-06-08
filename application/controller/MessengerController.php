<?php

class MessengerController extends Controller {
    public function index($toUserId = null)
    {
        $currentUserId = Session::get('user_id');

        if ($toUserId) {
            $chatId = MessengerModel::getOrCreateChat($currentUserId, $toUserId);
            MessengerModel::markMessagesAsRead($chatId);
            $messages = MessengerModel::getMessagesForChat($chatId);
        } else {
            $chatId = null;
            $messages = [];
        }

        $users = UserModel::getPublicProfilesOfAllUsers();

        $this->View->render('messenger/index', [
            'messages' => $messages,
            'chatId' => $chatId,
            'toUserId' => $toUserId,
            'users' => $users
        ]);
    }

    public function sendMessage()
    {
        $currentUserId = Session::get('user_id');
        $toUserId = Request::post('to_user_id');

        $chatId = MessengerModel::getOrCreateChat($currentUserId, $toUserId);

        MessengerModel::sendMessage(
            $chatId,
            $currentUserId,
            $toUserId,
            Request::post('content_text')
        );

        Redirect::to('messenger/index/' . $toUserId);
    }

}
