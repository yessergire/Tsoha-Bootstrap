<?php

  $routes->get('/categories', function() {
    CategoryController::index();
  });
  
  $routes->post('/categories', function() {
    CategoryController::store();
  });
  
  $routes->get('/categories/new', function() {
    CategoryController::create();
  });
  
  $routes->get('/categories/:id', function($id) {
    CategoryController::show($id);
  });
  
  $routes->get('/categories/:id/edit', function($id) {
    CategoryController::edit($id);
  });
  
  $routes->post('/categories/:id/edit', function($id) {
    CategoryController::update($id);
  });
  
  $routes->post('/categories/:id/destroy', function($id) {
    CategoryController::destroy($id);
  });