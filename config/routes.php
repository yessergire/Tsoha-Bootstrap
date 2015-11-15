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
  
  $routes->get('/items/1/edit', function() {
    HelloWorldController::item_edit();
  });
  
  $routes->get('/login', function() {
    HelloWorldController::login();
  });

  $routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
  });
