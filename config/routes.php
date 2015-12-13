<?php
  function check_logged_in(){
    BaseController::check_logged_in();
  }
  
  function check_admin_logged_in(){
      BaseController::check_admin_logged_in();
  }
  
  function check_customer_logged_in($route){
      BaseController::check_customer_logged_in($route->getParam('id'));
  }

  $routes->get('/', function() {
    Redirect::to('/items');
  });
  
  require 'routes/admin.php';
  require 'routes/customer.php';
  
  require 'routes/item.php';
  require 'routes/category.php';
  
  require 'routes/auction.php';
  require 'routes/bid.php';
  
  

  $routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
  });

  $routes->post('/logout', function() {
      BaseController::logout();
  });