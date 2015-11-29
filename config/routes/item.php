<?php

  $routes->get('/items', function() {
    ItemController::index();
  });
  
  $routes->post('/items', 'check_logged_in', function() {
    ItemController::store();
  });
  
  $routes->get('/items/new', 'check_logged_in', function() {
    ItemController::create();
  });
  
  $routes->get('/items/:id', function($id) {
    ItemController::show($id);
  });
  
  $routes->get('/items/:id/edit', 'check_logged_in', 'check_logged_in', function($id) {
    ItemController::edit($id);
  });
  
  $routes->post('/items/:id/edit', 'check_logged_in', function($id) {
    ItemController::update($id);
  });
  
  $routes->post('/items/:id/destroy', 'check_logged_in', function($id) {
    ItemController::destroy($id);
  });