<?php

class TuloksetController extends BaseController{
	public static function index(){

		$tulokset = Tulos::kaikki();

		View::make('tulos/tulokset.html', array('tulokset' => $tulokset));
	}

	public static function nayta($id){

		$tulos = Tulos::etsi($id);

		View::make('tulos/tulos.html', array('tulos' => $tulos));
	}

	public static function varastoi(){

		$parametrit = $_POST;

		$tulos = new Tulos(array( 
			'arvo' => $parametrit['arvo'],
			'kilpailu' => $parametrit['kilpailu'],
			'paivamaara' => $parametrit['paivamaara'],
			'lisatiedot' => $parametrit['lisatiedot'],
			'kayttaja' => 1,
			'napakympit' => $parametrit['napakympit'],
			'aselaji' => $parametrit['aselaji'],
			'kilpailumuoto' => $parametrit['kilpailumuoto'] 

		));

		Kint::dump($parametrit);

		$tulos->tallenna();

		Redirect::to('/tulos/' . $tulos->id, array('message' => 'Tulos on lisätty järjestelmään!'));
	}
}