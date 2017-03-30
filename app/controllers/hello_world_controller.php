<?php

  class HelloWorldController extends BaseController{

    public static function index(){
      // make-metodi renderöi app/views-kansiossa sijaitsevia tiedostoja
   	  echo 'Tämä on etusivu!';
    }

    public static function sandbox(){
      // Testaa koodiasi täällä
      $tulos = Tulos::etsi(1);
      $tulokset = Tulos::kaikki();

      Kint::dump($tulos);
      Kint::dump($tulokset);
    }

    public static function tulokset(){
      View::make('tulos/tulokset.html');
    }

    public static function tulos(){
      View::make('tulos/tulos.html');
    }

    public static function tuloksenMuokkaus(){
      View::make('tulos/tuloksenMuokkaus.html');
    }

    public static function kirjautuminen(){
      View::make('suunnitelmat/kirjautuminen.html');
    }

    public static function etusivu(){
      View::make('suunnitelmat/etusivu.html');
    }

    public static function uusi(){
      View::make('tulos/uusi.html');
    }
  }
