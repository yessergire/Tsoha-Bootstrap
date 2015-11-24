<?php

  $routes->get('/admin/login', function() {
    AdminController::login();
  });
  
  $routes->post('/admin/login', function() {
    AdminController::handle_login();
  });