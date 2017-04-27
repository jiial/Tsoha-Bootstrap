<?php

class TuloksetController extends BaseController{


	public static function index(){
		self::check_logged_in();
		$user_logged_in = self::get_user_logged_in();
		$parametrit = $_GET;
		$options = array('kayttaja_id' => $user_logged_in->id);

		$tulosten_maara = Tulos::laske();
		$tulosten_maara = $tulosten_maara['count'];
		
		$page_size = 10;
		$pages = ceil($tulosten_maara/$page_size);

		$page = (isset($_GET['page']) AND (int) $_GET['page'] > 0) ? (int) $_GET['page'] : 1;
        $prev_page = $page - 1;
        $next_page = $page + 1;
        if ($prev_page < 1) {
            $prev_page = null;
        } elseif ($next_page > $pages) {
            $next_page = null;
        }

		if(isset($parametrit['search'])){
			$options['search'] = $parametrit['search'];
		}

		$options['sivun_koko'] = $page_size;
		$options['sivu'] = $page;
		$tulokset = Tulos::kaikki($options);

		View::make('tulos/tulokset.html', array('tulokset' => $tulokset, 'page_size' => $page_size, 'pages' => $pages, 'page' => $page));
	}

	public static function muokkaa($id){
		self::check_logged_in();
		$tulos = Tulos::etsi($id);
		$aselajit = Aselaji::kaikki();
		$kilpailumuodot = Kilpailumuoto::kaikki();
		View::make('tulos/tuloksenMuokkaus.html', array('attribuutit' => $tulos, 'aselajit' => $aselajit, 'kilpailumuodot' => $kilpailumuodot));
	}

	public static function paivita($id){
		self::check_logged_in();
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

		$aselajit = Aselaji::kaikki();
		$kilpailumuodot = Kilpailumuoto::kaikki();

		if(count($virheet) > 0){
			View::make('/tulos/tuloksenMuokkaus.html', array('virheet' => $virheet, 'attribuutit' => $attribuutit, 'aselajit' => $aselajit, 'kilpailumuodot' => $kilpailumuodot));
		}else{
			$tulos->paivita($id);

			Redirect::to('/tulos/' . $id, array('message' => 'Tuloksen muokkaus onnistui'));
		}

	}

	public static function tuhoa($id){
		self::check_logged_in();
		$tulos = new Tulos(array('id' => $id));
		$sarjat = array();
		$sarjat = array($tulos->tuloksen_sarjat($id));
		foreach ($sarjat as $sarja) {
			$sarja->poista();
		}

		$tulos->poista();

		Redirect::to('/tulokset', array('message' => 'Tulos poistettu'));
	}

	public static function nayta($id){

		$tulos = Tulos::etsi($id);

		View::make('tulos/tulos.html', array('tulos' => $tulos));
	}

	public static function varastoi(){
		self::check_logged_in();

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

		//$attribuutit2 = array(
		//	'arvo' => $parametrit['sarja1'],
		//	'lisatiedot' => '',
		//	'tulos' => $parametrit['']
		//);

		$tulos = new Tulos($attribuutit);
		//$sarja1 = new Sarja($parametrit['sarja1'], '', $tulos);
		//$sarja2 = new Sarja($parametrit['sarja2'], '', $tulos);
		//$sarja3 = new Sarja($parametrit['sarja3'], '', $tulos);
		//$sarja4 = new Sarja($parametrit['sarja4'], '', $tulos);
		//$sarja5 = new Sarja($parametrit['sarja5'], '', $tulos);
		//$sarja6 = new Sarja($parametrit['sarja6'], '', $tulos);
		$virheet = $tulos->errors();

		if(count($virheet) == 0){
			$tulos->tallenna();
			$sarja1 = new Sarja(array('arvo' => $parametrit['sarja1'], 'lisatiedot' => '', 'tulos' => $tulos->id));		//$sarja2 = new Sarja($parametrit['sarja2'], '', $tulos);
		//$sarja3 = new Sarja($parametrit['sarja3'], '', $tulos);
		//$sarja4 = new Sarja($parametrit['sarja4'], '', $tulos);
		//$sarja5 = new Sarja($parametrit['sarja5'], '', $tulos);
		//$sarja6 = new Sarja($parametrit['sarja6'], '', $tulos);
			$sarja1->tallenna();
			//$sarja2->tallenna();
			//$sarja3->tallenna();
			//$sarja4->tallenna();
			//$sarja5->tallenna();
			//$sarja6->tallenna();

			Redirect::to('/tulos/' . $tulos->id, array('message' => 'Tulos on lisätty järjestelmään!'));
		}else{
			$aselajit = Aselaji::kaikki();
      		$kilpailumuodot = Kilpailumuoto::kaikki();
			View::make('/tulos/uusi.html', array('virheet' => $virheet, 'attribuutit' => $attribuutit, 'aselajit' => $aselajit, 'kilpailumuodot' => $kilpailumuodot));
		}
		
	}
}