<?php

  class HelloWorldController extends BaseController{

    public static function index(){
      // make-metodi renderöi app/views-kansiossa sijaitsevia tiedostoja
   	  echo 'Tämä on etusivu!';
    }

    public static function sandbox(){
      // Testaa koodiasi täällä
      View::make('helloworld.html');
    }

    public static function tulokset(){
      View::make('suunnitelmat/tulokset.html');
    }

    public static function tulos(){
      View::make('suunnitelmat/tulos.html');
    }

    public static function tuloksenMuokkaus(){
      View::make('suunnitelmat/tuloksenMuokkaus.html');
    }

    public static function kirjautuminen(){
      View::make('suunnitelmat/kirjautuminen.html');
    }

    public static function etusivu(){
      View::make('suunnitelmat/etusivu.html');
    }
  }
