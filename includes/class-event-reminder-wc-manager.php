<?php

class Event_Reminder_WC_Manager implements Event_Reminder_Manager {

  public function send_reminders() {

    $event_retriever = new Event_Reminder_WC_Event_Retriever();
    $event_ids = $event_retriever->get_events();

    $attendee_mgr = new Event_Reminder_WC_Attendees_Manager();

    $headers = array('Content-Type: text/html; charset=UTF-8');
  
    foreach ( $event_ids as $event_id ) {
      
      $attendees = $attendee_mgr->get_attendees( $event_id );

      if ( $attendees ) {
        $subject = get_the_title( $event_id );

        foreach ( $attendees as $attendee ) {
          $message = $this->get_customized_message( $attendee, $event_id );
          wp_mail( $attendee['email'], html_entity_decode($subject) , $message, $headers);
        }
      }

    }
  }

  public function cron_exec() {

    if ( ! wp_next_scheduled('tnotw_event_reminders_cron' ) ) {
      wp_schedule_event( time(), 'daily', 'tnotw_event_reminders_cron'  );
    }
    $this->send_reminders();
    $timestamp = wp_next_scheduled( 'tnotw_event_reminders_cron'  );
    wp_unschedule_event( $timestamp, 'tnotw_event_reminders_cron'   );

  }

  private function get_customized_message( $attendee, $event_id ) {

    $first = $attendee['first_name'];
    $title = get_the_title( $event_id );
    $title = html_entity_decode( $title );
    $event_datetime = get_post_meta( $event_id, EVENT_REMINDER_DATE_TIME_FIELD, true);

    $timestamp = strtotime( $event_datetime);
    $notice_datetime = date('m/d/Y h:i a', $timestamp);

    $settings = new Event_Reminder_Settings();
    $message = $settings->get_message_text();

    $message = str_replace( '__FIRST__',$first,  $message);
    $message = str_replace( '__TITLE__', $title,$message);
    $message = str_replace( '__DATETIME__', $notice_datetime, $message);

    return $message;

  }
}