<?php

  $routes->get('/items', function() {
    ItemController::index();
  });
  
  $routes->post('/items', function() {
    ItemController::store();
  });
  
  $routes->get('/items/new', function() {
    ItemController::create();
  });
  
  $routes->get('/items/:id', function($id) {
    ItemController::show($id);
  });
  
  $routes->get('/items/:id/edit', function($id) {
    ItemController::edit($id);
  });
  
  $routes->post('/items/:id/edit', function($id) {
    ItemController::update($id);
  });
  
  $routes->post('/items/:id/destroy', function($id) {
    ItemController::destroy($id);
  });