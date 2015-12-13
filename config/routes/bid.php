<?php

/**
 * Bid
 * path: /items/:id/bids
 */

  $routes->post('/bids', function() {
    BidController::store();
  });
  
  
  $routes->post('/bids/:customer/:auction/:time/destroy',
    function ($route){
        BaseController::check_customer_logged_in($route->getParam('customer'));
    },
    function($customer, $auction, $time) {
        BidController::destroy($customer, $auction, $time);
    }
  );