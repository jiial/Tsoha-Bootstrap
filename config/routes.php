<?php

  $routes->get('/', function() {
    HelloWorldController::index();
  });

  $routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
  });

  $routes->get('/tulos', function() {
  	TuloksetController::index();
  });

  $routes->get('/tulos/:id', function($id){
    TuloksetController::nayta($id);
  });

  $routes->get('/tulokset', function() {
  	TuloksetController::index();
  });

  $routes->get('/tuloksenMuokkaus', function() {
  	HelloWorldController::tuloksenMuokkaus();
  });

  $routes->get('/kirjautuminen', function() {
  	HelloWorldController::kirjautuminen();
  });

  $routes->get('/etusivu', function() {
  	HelloWorldController::etusivu();
  });

  $routes->get('/uusi', function() {
    HelloWorldController::uusi();
  });

  $routes->post('/uusitulos', function() {
    TuloksetController::varastoi();
  });

  $routes->get('/uusitulos/uusi', function() {
    TuloksetController::luo();
  });
