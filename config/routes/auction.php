<?php

/**
 * Auction
 * path: /auction/:id
 */

  $routes->get('/auctions', 'check_admin_logged_in', function() {
    AuctionController::index();
  });
  
  $routes->post('/auctions', 'check_admin_logged_in', function() {
    AuctionController::store();
  });
  
  $routes->get('/auctions/:id', 'check_admin_logged_in', function($id) {
    AuctionController::show($id);
  });
  
  $routes->get('/auctions/:id/edit', 'check_admin_logged_in', function($id) {
    AuctionController::edit($id);
  });
  
  $routes->post('/auctions/:id/edit', 'check_admin_logged_in', function($id) {
    AuctionController::update($id);
  });
  
  $routes->post('/auctions/:id/destroy', 'check_admin_logged_in', function($id) {
    AuctionController::destroy($id);
  });
  
  $routes->post('/auctions/:id/close', 'check_admin_logged_in', function($id) {
    AuctionController::close($id);
  });