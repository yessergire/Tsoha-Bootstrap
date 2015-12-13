<?php

  $routes->get('/customers', 'check_admin_logged_in', function() {
    CustomerController::index();
  });
  
  $routes->post('/customers', function() {
    CustomerController::store();
  });
  
  $routes->get('/customers/new', function() {
    CustomerController::create();
  });
  
  $routes->get('/customers/:id', 'check_customer_logged_in', function($id) {
    CustomerController::show($id);
  });
  
  $routes->get('/customers/:id/edit', 'check_customer_logged_in', function($id) {
    CustomerController::edit($id);
  });
  
  $routes->post('/customers/:id/edit', 'check_customer_logged_in', function($id) {
    CustomerController::update($id);
  });
  
  $routes->post('/customers/:id/destroy', 'check_customer_logged_in', function($id) {
    CustomerController::destroy($id);
  });
  
  $routes->get('/customer/login', function() {
    CustomerController::login();
  });
  
  $routes->post('/customer/login', function() {
    CustomerController::handle_login();
  });