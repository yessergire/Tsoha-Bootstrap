<?php

  $routes->get('/login', function() {
    UserController::login();
  });
  
  $routes->post('/login', function() {
    UserController::handle_login();
  });
  
  $routes->post('/logout', function() {
    UserController::logout();
  });