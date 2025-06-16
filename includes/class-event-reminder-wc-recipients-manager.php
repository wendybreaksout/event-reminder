<?php

Class Event_Reminder_WC_Attendees_Manager implements Event_Reminder_Attendees_Manager{

  public function get_attendees($event_id) {

    $attendees = array();

    $options = get_option( EVENT_REMINDER_OPTIONS_NAME );

    // get the send limit setting. Set to 1 if not set. 
    if ( ! isset(  $options['send_limit'])) {
			error_log(__FILE__ . ':' . __LINE__ . ',' . 'Event reminder notification limit is not set');
      $notification_limit = 1;
		}
    else {
      $notification_limit = $options['send_limit'];
    }



    // get WC orders for $id. 
    $order_ids = $this->get_orders_ids_by_product_id( $event_id );

    foreach( $order_ids as $order_id ) {

      // get notification count, don't send if at limit else increment and set send count. 
      $notification_count = get_post_meta( $order_id, EVENT_REMINDER_SEND_COUNT_FIELD_NAME, true );
      if ( $notification_count > 0 && $notification_count == $notification_limit ) {
        continue;
      } 
      $notification_count++ ;
      update_post_meta($order_id, EVENT_REMINDER_SEND_COUNT_FIELD_NAME, $notification_count  );

      // get customer info and add to attendees array
      $order = wc_get_order( $order_id );
      $attendee = array(
        'first_name' => $order->get_billing_first_name(),
        'last_name' => $order->get_billing_last_name(),
        'email' => $order->get_billing_email()
      );
      $attendees[] = $attendee;
    }
    return $attendees;
    
  }

  private function get_orders_ids_by_product_id( $product_id, $order_status = array( 'wc-completed', 'wc-pending') ){
    global $wpdb;

    $results = $wpdb->get_col("
        SELECT order_items.order_id
        FROM {$wpdb->prefix}woocommerce_order_items as order_items
        LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_item_meta ON order_items.order_item_id = order_item_meta.order_item_id
        WHERE order_item_meta.meta_key = '_product_id'
        AND order_item_meta.meta_value = '$product_id'
    ");

    return $results;
}

}