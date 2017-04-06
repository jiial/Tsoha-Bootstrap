<?php

class TuloksetController extends BaseController{
	public static function index(){

		$tulokset = Tulos::kaikki($_SESSION['kayttaja']);

		View::make('tulos/tulokset.html', array('tulokset' => $tulokset));
	}

	public static function muokkaa($id){
		$tulos = Tulos::etsi($id);
		View::make('tulos/tuloksenMuokkaus.html', array('attribuutit' => $tulos));
	}

	public static function paivita($id){
		$parametrit = $_POST;

		$attribuutit = array(
		    'id' => $id, 
			'arvo' => $parametrit['arvo'],
			'kilpailu' => $parametrit['kilpailu'],
			'paivamaara' => $parametrit['paivamaara'],
			'lisatiedot' => $parametrit['lisatiedot'],
			'kayttaja' => 1,
			'napakympit' => $parametrit['napakympit'],
			'aselaji' => $parametrit['aselaji'],
			'kilpailumuoto' => $parametrit['kilpailumuoto'] 
		);	

		$tulos = new Tulos($attribuutit);
		$virheet = $tulos->errors();

		if(count($virheet) > 0){
			View::make('/tulos/tuloksenMuokkaus.html', array('virheet' => $virheet, 'attribuutit' => $attribuutit));
		}else{
			$tulos->paivita($id);

			Redirect::to('/tulos/' . $id, array('message' => 'Tuloksen muokkaus onnistui'));
		}

	}

	public static function tuhoa($id){
		$tulos = new Tulos(array('id' => $id));

		$tulos->poista();

		Redirect::to('/tulokset', array('message' => 'Tulos poistettu'));
	}

	public static function nayta($id){

		$tulos = Tulos::etsi($id);

		View::make('tulos/tulos.html', array('tulos' => $tulos));
	}

	public static function varastoi(){

		$parametrit = $_POST;

		$attribuutit = array( 
			'arvo' => $parametrit['arvo'],
			'kilpailu' => $parametrit['kilpailu'],
			'paivamaara' => $parametrit['paivamaara'],
			'lisatiedot' => $parametrit['lisatiedot'],
			'kayttaja' => $_SESSION['kayttaja'],
			'napakympit' => $parametrit['napakympit'],
			'aselaji' => $parametrit['aselaji'],
			'kilpailumuoto' => $parametrit['kilpailumuoto'] 

		);

		$tulos = new Tulos($attribuutit);
		$virheet = $tulos->errors();

		if(count($virheet) == 0){
			$tulos->tallenna();

			Redirect::to('/tulos/' . $tulos->id, array('message' => 'Tulos on lisätty järjestelmään!'));
		}else{
			View::make('/tulos/uusi.html', array('virheet' => $virheet, 'attribuutit' => $attribuutit));
		}
		
	}
}