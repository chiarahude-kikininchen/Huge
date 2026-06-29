<?php

class CalendarModel {
    public static function getEvents() {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT * FROM calendar
                WHERE user_id = :user_id
                ORDER BY event_date ASC, event_time ASC";

        $query = $database->prepare($sql);
        $query->execute([
            'user_id' => Session::get('user_id')
        ]);
        return $query->fetchAll();
    }

    public static function createEvent($eventDate) {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "INSERT INTO calendar
                (user_id, title, description, event_date, event_time)
                VALUES
                (:user_id, :title, :description, :event_date, :event_time)";

        $query = $database->prepare($sql);
        $query->execute([
            'user_id' => Session::get('user_id'),
            'title' => Request::post('title'),
            'description' => Request::post('description'),
            'event_date' => Request::post('event_date'),
            'event_time' => Request::post('event_time')
        ]);
    }

    public static function deleteEvent($eventId) {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "DELETE FROM calendar
                WHERE event_id = :event_id
                AND user_id = :user_id";

        $query = $database->prepare($sql);
        $query->execute([
            'event_id' => $eventId,
            'user_id' => Session::get('user_id')
        ]);
    }
}