<?php

  $routes->get('/', function() {
    HelloWorldController::index();
  });
  
  require 'item_routes.php';
  require 'category_routes.php';
  require 'user_routes.php';
  //require 'customer_routes.php';
  //require 'kauppa_routes?php';
  
  

  $routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
  });
