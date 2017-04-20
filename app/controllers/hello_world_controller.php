<?php

  class HelloWorldController extends BaseController{

    public static function index(){
      // make-metodi renderöi app/views-kansiossa sijaitsevia tiedostoja
   	  echo 'Tämä on etusivu!';
    }

    public static function sandbox(){
      // Testaa koodiasi täällä
      //$tulos = Tulos::etsi(1);
      //$tulokset = Tulos::kaikki();

      //Kint::dump($tulos);
      //Kint::dump($tulokset);
      $testi = new Tulos(array(
      'id' => '100',  
      'arvo' => '2',
      'kilpailu' => '',
      'paivamaara' => '02.04.2023',
      'lisatiedot' => '',
      'napakympit' => 'n',
      'kayttaja' => 1,
      'aselaji' => 1,
      'kilpailumuoto' => 1
      ));
      $virheet = $testi->errors();

      Kint::dump($virheet);
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
      $aselajit = Aselaji::kaikki();
      $kilpailumuodot = Kilpailumuoto::kaikki();
      View::make('tulos/uusi.html', array('aselajit' => $aselajit, 'kilpailumuodot' => $kilpailumuodot));
    }
  }
