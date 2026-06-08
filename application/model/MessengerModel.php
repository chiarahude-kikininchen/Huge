<?php

/** MessengerModel
 *
 * Handles messaging stuff, as the name implies
 */

class MessengerModel {

    /** Shows all messages between two users */
    public static function getMessagesForChat($chatId) {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT
                    messages.message_id,
                    messages.chat_id,
                    messages.from_user_id,
                    messages.to_user_id,
                    messages.content_text,
                    messages.created_at,
                    users.user_name
                FROM messages
                INNER JOIN users
                    ON messages.from_user_id = users.user_id
                WHERE messages.chat_id = :chat_id
                ORDER BY messages.created_at ASC";

        $query = $database->prepare($sql);
        $query->execute(['chat_id' => $chatId]);

        return $query->fetchAll();
    }

    /** Send messages */
    public static function sendMessage($chatId, $fromUserId, $toUserId, $contentText) {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "INSERT INTO messages
                    (chat_id, from_user_id, to_user_id, content_text, is_read)
                VALUES
                    (:chat_id, :from_user_id, :to_user_id, :content_text, 0)";

        $query = $database->prepare($sql);

        return $query->execute([
            'chat_id' => $chatId,
            'from_user_id' => $fromUserId,
            'to_user_id' => $toUserId,
            'content_text' => $contentText,
        ]);
    }

    /** Unread message count */
    public static function unreadMessages()
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT COUNT(1)
                FROM messages
                WHERE is_read = 0
                AND to_user_id = ?";

        $query = $database->prepare($sql);
        $query->execute([Session::get('user_id')]);

        return $query->fetchColumn();
    }

    public static function markMessagesAsRead($chatId)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "UPDATE messages
            SET is_read = 1
            WHERE chat_id = :chat_id
            AND to_user_id = :user_id";

        $query = $database->prepare($sql);
        return $query->execute([
            'chat_id' => $chatId,
            'user_id' => Session::get('user_id')
        ]);
    }

    public static function getOrCreateChat($user1, $user2)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT cu1.chat_id
            FROM chat_user cu1
            INNER JOIN chat_user cu2
                ON cu1.chat_id = cu2.chat_id
            WHERE cu1.user_id = :user1
              AND cu2.user_id = :user2
            LIMIT 1";

        $query = $database->prepare($sql);
        $query->execute([
            'user1' => $user1,
            'user2' => $user2
        ]);

        $chatId = $query->fetchColumn();

        if ($chatId) {
            return $chatId;
        }

        $sql = "INSERT INTO chats (status) VALUES (NULL)";
        $query = $database->prepare($sql);
        $query->execute();

        $chatId = $database->lastInsertId();

        $sql = "INSERT INTO chat_user (chat_id, user_id)
            VALUES (:chat_id, :user1), (:chat_id, :user2)";

        $query = $database->prepare($sql);
        $query->execute([
            'chat_id' => $chatId,
            'user1' => $user1,
            'user2' => $user2
        ]);

        return $chatId;
    }
}