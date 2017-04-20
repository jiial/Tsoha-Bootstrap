<?php

class SarjatController extends BaseController{
	public static function index(){
		self::check_logged_in();
		$sarjat = Sarja::kaikki($_SESSION['kayttaja']);

		View::make('sarja/sarjat.html', array('sarjat' => $sarjat));
	}

	public static function nayta($id){
		self::check_logged_in();
		$sarja = Sarja::etsi($id);

		View::make('sarja/sarja.html', array('sarja' => $sarja));
	}
}