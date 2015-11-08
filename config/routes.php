<?php

  $routes->get('/', function() {
    HelloWorldController::index();
  });
  
  $routes->get('/items', function() {
    HelloWorldController::item_list();
  });
  
  $routes->get('/items/1', function() {
    HelloWorldController::item_show();
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
