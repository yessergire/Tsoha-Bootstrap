<?php

  $routes->get('/categories', function() {
    CategoryController::index();
  });
  
  $routes->post('/categories', 'check_logged_in', function() {
    CategoryController::store();
  });
  
  $routes->get('/categories/new', 'check_logged_in', function() {
    CategoryController::create();
  });
  
  $routes->get('/categories/:id', function($id) {
    CategoryController::show($id);
  });
  
  $routes->get('/categories/:id/edit', 'check_logged_in', function($id) {
    CategoryController::edit($id);
  });
  
  $routes->post('/categories/:id/edit', 'check_logged_in', function($id) {
    CategoryController::update($id);
  });
  
  $routes->post('/categories/:id/destroy', 'check_logged_in', function($id) {
    CategoryController::destroy($id);
  });