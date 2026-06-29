<?php

class CalendarController extends Controller {

    public function index() {
        $this->View->render('calender/index', array(
            'events' => CalendarModel::getEvents()
        ));
    }

    public function create() {
        CalendarModel::createEvent();
        Redirect::to('calendar');
    }

    public function delete($eventId) {
        CalendarModel::deleteEvent($eventId);
        Redirect::to('calendar');
    }
}
