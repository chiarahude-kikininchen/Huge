<?php

class MessengerModel {

    public static function getMessagesForChat($chatId) {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "CALL spGetMessagesForChat(:chat_id)";
        $query = $database->prepare($sql);
        $query->execute(['chat_id' => $chatId]);

        return $query->fetchAll();
    }

    public static function sendMessage($chatId, $fromUserId, $toUserId, $contentText) {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "CALL spSendMessage(:chat_id, :from_user_id, :to_user_id, :content_text)";
        $query = $database->prepare($sql);

        return $query->execute([
            'chat_id' => $chatId,
            'from_user_id' => $fromUserId,
            'to_user_id' => $toUserId,
            'content_text' => $contentText
        ]);
    }

    public static function unreadMessages()
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "CALL spGetUnreadMessagesCount(:user_id)";
        $query = $database->prepare($sql);
        $query->execute(['user_id' => Session::get('user_id')]);

        return $query->fetchColumn();
    }

    public static function markMessagesAsRead($chatId)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "CALL spMarkMessagesAsRead(:chat_id, :user_id)";
        $query = $database->prepare($sql);

        return $query->execute([
            'chat_id' => $chatId,
            'user_id' => Session::get('user_id')
        ]);
    }

    public static function getOrCreateChat($user1, $user2)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "CALL spGetOrCreateChat(:user1, :user2)";
        $query = $database->prepare($sql);

        $query->execute([
            'user1' => $user1,
            'user2' => $user2
        ]);

        return $query->fetchColumn();
    }
}