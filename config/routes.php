<?php
  function check_logged_in(){
    BaseController::check_logged_in();
  }

  $routes->get('/', function() {
    HelloWorldController::index();
  });
  
  require 'routes/admin.php';
  require 'routes/customer.php';
  
  require 'routes/item.php';
  require 'routes/category.php';
  
  //require 'routes/auction.php';
  //require 'routes/bid.php';
  
  

  $routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
  });

  $routes->post('/logout', function() {
    HelloWorldController::logout();
  });