<?php

  $routes->get('/', function() {
    HelloWorldController::index();
  });
  
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
  
  $routes->get('/items/1/edit', function() {
    HelloWorldController::item_edit();
  });
  
  $routes->get('/login', function() {
    UserController::login();
  });
  
  $routes->post('/login', function() {
    UserController::handle_login();
  });
  
  $routes->post('/logout', function() {
    UserController::logout();
  });

  $routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
  });
