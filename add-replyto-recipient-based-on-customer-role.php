/* Add Reply-to recipient to WooCommerce Order Notifications based on customer role */
function wyze_custom_wooemail_headers( $headers, $email_id, $order ) {

    // The order ID | Compatibility with WC version +3
    $order_id = method_exists( $order, 'get_id' ) ? $order->get_id() : $order->id;
    
    // Get the user ID from WC_Order methods
    $user_id = $order->get_customer_id();

    $email = get_post_meta( $order_id, '_approver_email', true );

    // Get the user data
    $user_data = get_userdata( $user_id );
    
    //If user is not logged in
    if ( $user_id == 0 ) {
      // Replace the emails below to your desire email
      $emails = array('customernotloggedin@gmail.com', $email);
    }
    //If user is logged in
    else {
      if ( in_array( 'wholesale_customer', $user_data->roles )  ) {
          $emails = array('wholesalecustomer@gmail.com', $email);
      }
      elseif ( in_array( 'wholesale_customer_10', $user_data->roles )  ) {
          $emails = array('specialwholesalecustomer@gmail.com', $email);
      }
      else {
        $emails = array('otherloggedincustomer@gmail.com', $email);
      }
    }

    switch( $email_id ) {
        case 'customer_processing_order':
            $headers .= 'Reply-To: ' . implode(',', $emails) . "\r\n";
            break;
        case 'customer_completed_order':
        case 'customer_invoice':
            $headers .= 'Reply-To: ' . implode(',', $emails) . "\r\n";
            break;

        default:
    }

    return $headers;
}

add_filter( 'woocommerce_email_headers', 'wyze_custom_wooemail_headers', 10, 3);
