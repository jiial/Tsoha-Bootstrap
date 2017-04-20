<?php

  function check_logged_in(){
    BaseController::check_logged_in();
  }

  $routes->get('/', function() {
    HelloWorldController::etusivu();
  });

  $routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
  });

  $routes->get('/tulos/:id', 'check_logged_in', function($id){
    TuloksetController::nayta($id);
  });

  $routes->get('/tulokset', 'check_logged_in', function() {
  	TuloksetController::index();
  });

  $routes->get('/sarjat', function() {
    SarjatController::index();
  });

  $routes->get('/sarja/:id', 'check_logged_in', function($id){
    SarjatController::nayta($id);
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

  $routes->get('/uusi', 'check_logged_in', function() {
    HelloWorldController::uusi();
  });

  $routes->post('/uusi', 'check_logged_in', function() {
    TuloksetController::varastoi();
  });

  $routes->get('/uusitulos/uusi', 'check_logged_in', function() {
    TuloksetController::luo();
  });

  $routes->post('/tulos/:id/muokkaa', 'check_logged_in', function($id){
    TuloksetController::paivita($id);
  });

  $routes->get('/tulos/:id/muokkaa', 'check_logged_in', function($id){
    TuloksetController::muokkaa($id);
  });

  $routes->post('/tulos/:id/poista', 'check_logged_in', function($id){
    TuloksetController::tuhoa($id);
  });

  $routes->get('/login', function() {
    KayttajaController::kirjautuminen();
  });

  $routes->post('/login', function() {
    KayttajaController::kasittele_kirjautuminen();
  });

  $routes->post('/logout', function() {
    KayttajaController::logout();
  });


