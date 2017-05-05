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

  $routes->post('/sarja/:id/muokkaa', 'check_logged_in', function($id){
    SarjatController::paivita($id);
  });

  $routes->get('/sarja/:id/muokkaa', 'check_logged_in', function($id){
    SarjatController::muokkaa($id);
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

  $routes->get('/register', function() {
    KayttajaController::register();
  });

  $routes->post('/register', function() {
    KayttajaController::tallenna();
  });

  $routes->get('/valitseAselaji', function() {
    TuloksetController::aselaji();
  });

  $routes->post('/valitseAselaji', function() {
    TuloksetController::aselajinVahvistus();
  });

  $routes->get('/valitseKilpailumuoto', function() {
    TuloksetController::kilpailumuoto();
  });

  $routes->post('/valitseKilpailumuoto', function() {
    TuloksetController::kilpailumuodonVahvistus();
  });

  $routes->get('/ennatykset/ilmakivaari', function() {
    TuloksetController::ik();
  });

  $routes->get('/ennatykset/pienoiskivaari', function() {
    TuloksetController::pk();
  });

  $routes->get('/ennatykset/vakiokivaari', function() {
    TuloksetController::vk();
  });

  $routes->get('/ennatykset/300mkivaari', function() {
    TuloksetController::kk();
  });

    $routes->get('/tulokset/ilmakivaari', function() {
    TuloksetController::rajaaik();
  });

  $routes->get('/tulokset/pienoiskivaari', function() {
    TuloksetController::rajaapk();
  });

  $routes->get('/tulokset/vakiokivaari', function() {
    TuloksetController::rajaavk();
  });

  $routes->get('/tulokset/300mkivaari', function() {
    TuloksetController::rajaakk();
  });

  $routes->get('/sarjat/40ls', function() {
    SarjatController::neljakymmenta();
  });

  $routes->get('/sarjat/60ls', function() {
    SarjatController::kuusikymmenta();
  });

  $routes->get('/sarjat/3x20ls', function() {
    SarjatController::kolmekertaakaksikymmenta();
  });

  $routes->get('/sarjat/3x40ls', function() {
    SarjatController::kolmekertaaneljakymmenta();
  });

  $routes->get('/sarjat/makuu', function() {
    SarjatController::makuu();
  });



