<?php

Class Event_Reminder_WC_Event_Retriever implements Event_Reminder_Event_Retriever {

  public function get_events() {

    $options = get_option( EVENT_REMINDER_OPTIONS_NAME, false) ;

    if ( !isset( $options['lead_time'])) {
      error_log(__FILE__ . ':' . __LINE__ . ", Cannot get lead time setting");
      return;
    }

    $lead_time = $options['lead_time'];

    $args = array( 
      'post_type' => 'product',
      'meta_key' => EVENT_REMINDER_DATE_TIME_FIELD,
      'meta_compare' => 'EXISTS'
    );

    $event_post_IDs = array();

    $query = $query = new WP_Query( $args );
  
    if ( $query->have_posts() ) {

      while ( $query->have_posts() ) {
        $query->the_post();
        $prod_id =  get_the_ID();

        $event_date = get_post_meta( $prod_id, EVENT_REMINDER_DATE_TIME_FIELD, true );
        $event_date .=  " " . wp_timezone_string();

        if ( $event_date ) {
          $event_timestamp = strtotime( $event_date );
          if ( $event_timestamp ) {
            $now = time();
            if ( $event_timestamp > $now && (  $event_timestamp - $now ) < ( $lead_time * 3600 ) ) {
              $event_post_IDs[] = $prod_id;
            }
          }
          else {
            error_log(__FILE__ . "," . __LINE__ . ":" . __("Could not parse date/time for ID = ", EVENT_REMINDER_TEXT_DOMAIN)  . $prod_id );
            continue;
          }

        }
       
      }

      return $event_post_IDs;
    }
    else {
      return false;
    }


  }
}