<?php

class KayttajaController extends BaseController{

	public static function kirjautuminen(){
		View::make('kayttaja/kirjautuminen.html');
	}

	public static function kasittele_kirjautuminen(){
		$parametrit = $_POST;

		$kayttaja = Kayttaja::autentikoi($parametrit['kayttajanimi'], $parametrit['salasana']);

		if(!$kayttaja){
			View::make('kayttaja/kirjautuminen.html', array('error' => 'Väärä käyttäjätunnus tai salasana', 'kayttajanimi' => $parametrit['kayttajanimi']));
		}else{
			$_SESSION['kayttaja'] = $kayttaja->id;

			Redirect::to('/tulokset', array('message' => 'Olet kirjautunut sisään'));
		}
	}

	public static function logout(){
		$_SESSION['kayttaja'] = null;
		Redirect::to('/login', array('message' => 'Olet kirjautunut ulos'));
	}

	public static function register(){
		View::make('kayttaja/rekisteroityminen.html');
	}

	public static function tallenna(){

		$parametrit = $_POST;

		$attribuutit = array(
			'nimi' => $parametrit['nimi'],
			'password' => $parametrit['password']
		);

		$kayttaja = new Kayttaja($attribuutit);
		

		$virheet = $kayttaja->errors();

		if(count($virheet) == 0){
			$kayttaja->luo();

			Redirect::to('/login', array('viesti' => 'Rekisteröityminen onnistui! Voit nyt kirjautua sisään.'));
		}else{
			View::make('/kayttaja/rekisteroityminen.html', array(
				'virheet' => $virheet,
				'nimi' => $parametrit['nimi']
				));
		}


	}
}