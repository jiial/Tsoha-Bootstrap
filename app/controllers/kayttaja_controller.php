<?php

class KayttajaController extends BaseController{

	public static function kirjautuminen(){
		View::make('suunnitelmat/kirjautuminen.html');
	}

	public static function kasittele_kirjautuminen(){
		$parametrit = $_POST;

		$kayttaja = Kayttaja::autentikoi($parametrit['kayttajanimi'], $parametrit['salasana']);

		if(!$kayttaja){
			View::make('suunnitelmat/kirjautuminen.html', array('error' => 'Väärä käyttäjätunnus tai salasana', 'kayttajanimi' => $parametrit['kayttajanimi']));
		}else{
			$_SESSION['kayttaja'] = $kayttaja->id;

			Redirect::to('/tulokset', array('message' => 'Olet kirjautunut sisään'));
		}
	}
}