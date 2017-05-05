<?php

class SarjatController extends BaseController{
	public static function index(){
		self::check_logged_in();

		$sarjojen_maara = Sarja::laskeKaikki();
		$sarjojen_maara = $sarjojen_maara['count'];
		
		$page_size = 10;
		$pages = ceil($sarjojen_maara/$page_size);

		$page = (isset($_GET['page']) AND (int) $_GET['page'] > 0) ? (int) $_GET['page'] : 1;
        $prev_page = $page - 1;
        $next_page = $page + 1;
        if ($prev_page < 1) {
            $prev_page = null;
        } 
        if ($next_page > $pages) {
            $next_page = null;
        }

        $options = array();
        $options['sivun_koko'] = $page_size;
		$options['sivu'] = $page;

		$sarjat = Sarja::kaikki($options);

		if(count($sarjat) == 0){
			$sarjoja = false;
		}else{
			$sarjoja = true;
		}

		View::make('sarja/sarjat.html', array('sarjat' => $sarjat, 'page_size' => $page_size, 'pages' => $pages, 'page' => $page, 'next_page' => $next_page, 'prev_page' => $prev_page, 'sarjoja' => $sarjoja));
	}

	public static function nayta($id){
		self::check_logged_in();
		$sarja = Sarja::etsi($id);

		$tulos = Sarja::sarjanTulos($id);

		if($tulos['kilpailumuoto'] == 2){
			$sarja->arvo = (int)$sarja->arvo;
		}
		if($tulos['kilpailumuoto'] == 3){
			$sarja->arvo = (int)$sarja->arvo;
		}

		View::make('sarja/sarja.html', array('sarja' => $sarja));
	}

	public static function muokkaa($id){
		self::check_logged_in();
		$sarja = Sarja::etsi($id);

		$tulos = Sarja::sarjanTulos($id);

		if($tulos['kilpailumuoto'] == 2){
			$sarja->arvo = (int)$sarja->arvo;
		}
		if($tulos['kilpailumuoto'] == 3){
			$sarja->arvo = (int)$sarja->arvo;
		}

		View::make('sarja/sarjanMuokkaus.html', array('attribuutit' => $sarja));
	}

	public static function paivita($id){
		self::check_logged_in();
		$parametrit = $_POST;

		$attribuutit = array(
			'arvo' => $parametrit['arvo'],
			'lisatiedot' => $parametrit['lisatiedot']
		);

		$sarja = new Sarja($attribuutit);
		$virheet = $sarja->errors();

		if(count($virheet) > 0){
			View::make('sarja/sarjanMuokkaus.html', array('virheet' => $virheet, 'attribuutit' => $attribuutit));
		}else{
			$sarja->paivita($id);

			Redirect::to('/sarja/' . $id, array('message' => 'Sarjan muokkaus onnistui'));
		}
	}

	public static function rajaa($kilpailumuoto){
		self::check_logged_in();

		$sarjojen_maara = Sarja::laske($kilpailumuoto);
		$sarjojen_maara = $sarjojen_maara['count'];
		
		$page_size = 10;
		$pages = ceil($sarjojen_maara/$page_size);

		$page = (isset($_GET['page']) AND (int) $_GET['page'] > 0) ? (int) $_GET['page'] : 1;
        $prev_page = $page - 1;
        $next_page = $page + 1;
        if ($prev_page < 1) {
            $prev_page = null;
        } 
        if ($next_page > $pages) {
            $next_page = null;
        }

        $options = array();
        $options['sivun_koko'] = $page_size;
		$options['sivu'] = $page;
		$options['kilpailumuoto'] = $kilpailumuoto;

		$sarjat = Sarja::kaikki($options);

		if(count($sarjat) == 0){
			$sarjoja = false;
		}else{
			$sarjoja = true;
		}

		if($kilpailumuoto == 1){
			$kilpailumuoto = '60ls';
		}
		if($kilpailumuoto == 4){
			$kilpailumuoto = '40ls';
		}
		if($kilpailumuoto == 2){
			$kilpailumuoto = '3x20ls';
		}
		if($kilpailumuoto == 3){
			$kilpailumuoto = '3x40ls';
		}
		if($kilpailumuoto == 5){
			$kilpailumuoto = 'makuu';
		}
		$rajaus = true;

		View::make('sarja/sarjat.html', array('sarjat' => $sarjat, 'page_size' => $page_size, 'pages' => $pages, 'page' => $page, 'next_page' => $next_page, 'prev_page' => $prev_page, 'sarjoja' => $sarjoja, 'kilpailumuoto' => $kilpailumuoto, 'rajaus' => $rajaus));
	}

	public static function neljakymmenta(){
		self::check_logged_in();
		$kilpailumuoto = Kilpailumuoto::getId("40 ls");
		$kilpailumuoto = $kilpailumuoto['id'];
		SarjatController::rajaa($kilpailumuoto);
	}

	public static function kuusikymmenta(){
		self::check_logged_in();
		$kilpailumuoto = Kilpailumuoto::getId("60 ls");
		$kilpailumuoto = $kilpailumuoto['id'];
		SarjatController::rajaa($kilpailumuoto);
	}

	public static function kolmekertaakaksikymmenta(){
		self::check_logged_in();
		$kilpailumuoto = Kilpailumuoto::getId("3x20 ls");
		$kilpailumuoto = $kilpailumuoto['id'];
		SarjatController::rajaa($kilpailumuoto);
	}

	public static function kolmekertaaneljakymmenta(){
		self::check_logged_in();
		$kilpailumuoto = Kilpailumuoto::getId("3x40 ls");
		$kilpailumuoto = $kilpailumuoto['id'];
		SarjatController::rajaa($kilpailumuoto);
	}

	public static function makuu(){
		self::check_logged_in();
		$kilpailumuoto = Kilpailumuoto::getId("Makuu 60 ls");
		$kilpailumuoto = $kilpailumuoto['id'];
		SarjatController::rajaa($kilpailumuoto);
	}

}