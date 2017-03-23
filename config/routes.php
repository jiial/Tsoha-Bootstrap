<?php

  $routes->get('/', function() {
    HelloWorldController::index();
  });

  $routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
  });

  $routes->get('/tulokset', function() {
  	HelloWorldController::tulokset();
  });

  $routes->get('/tulos', function() {
  	HelloWorldController::tulos();
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
