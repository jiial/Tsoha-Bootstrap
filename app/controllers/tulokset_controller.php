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
        }
        if ($next_page > $pages) {
            $next_page = null;
        }

		if(isset($parametrit['search'])){
			$options['search'] = $parametrit['search'];
		}
		$options['sivun_koko'] = $page_size;
		$options['sivu'] = $page;
		$tulokset = Tulos::kaikki($options);

		if(count($tulokset) == 0){
			if(!isset($parametrit['search'])){
				$tuloksia = false;
				$tyhja = 'Et ole vielä lisännyt tuloksia';
		    }else{
		    	$tuloksia = true;
		    	$tyhja = 'Ei tuloksia annetulla hakusanalla';
		    }
		}else{
			$tuloksia = true;
			$tyhja = false;
		}

		$rajaus = false;

		View::make('tulos/tulokset.html', array('tulokset' => $tulokset, 'page_size' => $page_size, 'pages' => $pages, 'page' => $page, 'next_page' => $next_page, 'prev_page' => $prev_page, 'tuloksia' => $tuloksia, 'tyhja' => $tyhja, 'rajaus' => $rajaus));
	}

	public static function muokkaa($id){
		self::check_logged_in();
		$tulos = Tulos::etsi($id);
		//Jos tuloksen kilpailumuoto on 3x20ls tai 3x40ls, lasketaan tulokset kokonaislukuna, joten tuloksen arvo castataan int:iksi.
		if($tulos->kilpailumuoto == 2 || $tulos->kilpailumuoto == 3){
			$tulos->arvo = (int)$tulos->arvo;
		}
		View::make('tulos/tuloksenMuokkaus.html', array('attribuutit' => $tulos));
	}

	public static function paivita($id){
		self::check_logged_in();
		$parametrit = $_POST;

		//Haetaan tietokannasta tuloksen aselaji ja kilpailumuoto, joita tarvitaan muokkausten tallentamiseen.
		$apu = Tulos::etsi($id);
		$aselaji = array();
		$aselaji = Aselaji::getId($apu->aselaji);
		$aselaji = $aselaji['id'];
		$kilpailumuoto = array();
		$kilpailumuoto = Kilpailumuoto::getId($apu->kilpailumuoto);
		$kilpailumuoto = $kilpailumuoto['id'];

		$attribuutit = array(
		    'id' => $id, 
			'arvo' => $parametrit['arvo'],
			'kilpailu' => $parametrit['kilpailu'],
			'paivamaara' => $parametrit['paivamaara'],
			'lisatiedot' => $parametrit['lisatiedot'],
			'kayttaja' => $_SESSION['kayttaja'],
			'napakympit' => $parametrit['napakympit'],
			'aselaji' => $aselaji,
			'kilpailumuoto' => $kilpailumuoto
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
		self::check_logged_in();
		$tulos = new Tulos(array('id' => $id));
		//Poistetaan haluttu tulos ja siihen liittyvät sarjat.
		$sarjat = array();
		$sarjat = $tulos->tuloksen_sarjat($id);
		foreach ($sarjat as $sarja) {
			if($sarja != null){
				$sarja->poista();
			}
		}

		$tulos->poista();

		Redirect::to('/tulokset', array('message' => 'Tulos poistettu'));
	}

	public static function nayta($id){

		$tulos = Tulos::etsi($id);
		$sarjat = array();
		$sarjat = Tulos::tuloksen_sarjat($tulos->id);
		//Tulosnäkymässä näytetään myös tulokseen liittyvät sarjat, jotka haetaan muuttujaan $sarjat.
		if($tulos->kilpailumuoto == 2){
			foreach ($sarjat as $sarja) {
				$sarja->arvo = (int)$sarja->arvo;
			}
			$tulos->arvo = (int)$tulos->arvo;
		}
		if($tulos->kilpailumuoto == 3){
			foreach ($sarjat as $sarja) {
				$sarja->arvo = (int)$sarja->arvo;
			}
			$tulos->arvo = (int)$tulos->arvo;
		}

		View::make('tulos/tulos.html', array('tulos' => $tulos, 'sarjat' => $sarjat));
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

		$tulos = new Tulos($attribuutit);
		$virheet = $tulos->errors();

		$sarjat = array();
		$sarjat = $parametrit['sarjat'];
		// Taulukkoa $sarjat käytetään ainoastaan sarjojen validointiin, koska se ei vielä tiedä mihin tulokseen kuuluu. Tämä johtuu siitä että tulosta ei ole vielä luotu joten sillä ei ole id:tä.

		$sarjat2 = array();
		//Taulukkoa $sarjat2 käytetään sarjojen lisäämiseen tietokantaan, mikäli validoinnit menevät läpi.
		$pisteet = 0;
		//$pisteet muuttujaan lasketaan osasarjojen pisteet yhteen. Muuttujaa käytetään tarkistamaan, että tuloksen arvo täsmää sarjojen pisteiden kanssa.

		foreach ($sarjat as $sarja) {
			$pisteet = $pisteet + $sarja;
			$sarja = new Sarja(array('arvo' => (int)$sarja, 'lisatiedot' => '', 'tulos' => null));
			foreach ($sarja->errors() as $virhe) {
				$virheet[] = $virhe;
			}	
		}

		if($pisteet != $tulos->arvo){
			$virheet[] = 'Sarjojen summan tulee olla yhtä kuin tuloksen arvo';
		}
		$sarjoja = array();
		$sarjojenMaara = count($sarjat);
		for($x = 1; $x <= $sarjojenMaara; $x++){
				$sarjoja[] = new Sarja($attribuutit);
		}

		$neljakymmenta = false;
		$kuusikymmenta = false;
		$satakaksikymmenta = false;

		if($sarjojenMaara == 4){
			$neljakymmenta = true;
		}elseif($sarjojenMaara == 12){
			$satakaksikymmenta = true;
		}else{
			$kuusikymmenta = true;
		}

		if(count($virheet) == 0){
			$tulos->tallenna();

			foreach ($sarjat as $sarja) {
			$sarja = new Sarja(array('arvo' => (int)$sarja, 'lisatiedot' => '', 'tulos' => $tulos->id));
			$sarjat2[] = $sarja;
			}

			foreach ($sarjat2 as $sarja) {
				$sarja->tallenna();
		    }

			Redirect::to('/tulos/' . $tulos->id, array('message' => 'Tulos on lisätty järjestelmään! Huomaa että voit nyt lisätä sarjoihin lisätietoja.'));
		}else{
			$sarjoja = $sarjat;
			$aselajit = Aselaji::kaikki();
      		$kilpailumuodot = Kilpailumuoto::kaikki();
      		$attribuutit['aselaji'] = Aselaji::getNimi($attribuutit['aselaji']);
      		$attribuutit['kilpailumuoto'] = Kilpailumuoto::getNimi($attribuutit['kilpailumuoto']);
			View::make('/tulos/uusi.html', array('virheet' => $virheet, 'attribuutit' => $attribuutit, 'aselajit' => $aselajit, 'kilpailumuodot' => $kilpailumuodot, 'sarjoja' => $sarjoja, 'aselaji' => $parametrit['aselaji'], 'kilpailumuoto' => $parametrit['kilpailumuoto'], 'neljakymmenta' => $neljakymmenta, 'kuusikymmenta' => $kuusikymmenta, 'satakaksikymmenta' => $satakaksikymmenta));
		}		
	}

	public static function aselaji(){
		self::check_logged_in();
		$aselajit = Aselaji::kaikki();

		View::make('/tulos/valitseAselaji.html', array('aselajit' => $aselajit));
	}

	public static function kilpailumuoto(){
		self::check_logged_in();

		View::make('/tulos/valitseKilpailumuoto.html');
	}

	public static function aselajinVahvistus(){
		self::check_logged_in();
		$params = $_POST;
		$aselaji = $params['aselaji'];
		$kilpailumuodot = Aselaji::kilpailumuodot($aselaji);
		Redirect::to('/valitseKilpailumuoto', array('aselaji' => $aselaji, 'kilpailumuodot' => $kilpailumuodot));
	}

	public static function kilpailumuodonVahvistus(){
		self::check_logged_in();
		$parametrit = $_POST;
		$aselaji = $parametrit['aselaji'];
		$kilpailumuoto = $parametrit['kilpailumuoto'];
		$sarjoja = array();
		$attribuutit = array();

		$neljakymmenta = false;
		$kuusikymmenta = false;
		$satakaksikymmenta = false;

		if($kilpailumuoto == 3){
			for($x = 1; $x <= 12; $x++){
				$sarjoja[] = "";
			}
			$satakaksikymmenta = true;
		}elseif($kilpailumuoto == 4){
			for($x = 1; $x <= 4; $x++){
				$sarjoja[] = "";
			}
			$neljakymmenta = true;
		}else{
			for($x = 1; $x <= 6; $x++){
				$sarjoja[] = "";
			};
			$kuusikymmenta = true;
		}
		Redirect::to('/uusi', array('aselaji' => $aselaji, 'kilpailumuoto' => $kilpailumuoto, 'sarjoja' => $sarjoja, 'neljakymmenta' => $neljakymmenta, 'kuusikymmenta' => $kuusikymmenta, 'satakaksikymmenta' => $satakaksikymmenta));
	}

	public static function aselajinEnnatykset($aselaji){

        $kilpailumuodot = array();
        $kilpailumuodot = Aselaji::kilpailumuodot((int)$aselaji);

        $ennatykset = array();
        foreach ($kilpailumuodot as $kilpailumuoto) {
        	$enkat[] = Tulos::ennatykset($aselaji, $kilpailumuoto);
        }
        $ennatykset = array();
        foreach($enkat as $ennatys){
        	if($ennatys == null){
        		$ennatys['arvo'] = 'Ei tuloksia';
        		$ennatykset[] = $ennatys;
        	}elseif($ennatys['kilpailumuoto'] == 2 || $ennatys['kilpailumuoto'] == 3){
        		$ennatys['arvo'] = (int)$ennatys['arvo'];
        		$ennatykset[] = $ennatys;
        	}else{
        		$ennatykset[] = $ennatys;
        	}
        }

        $aselaji = Aselaji::getNimi($aselaji);

        View::make('tulos/ennatykset.html', array('kilpailumuodot' => $kilpailumuodot, 'ennatykset' => $ennatykset, 'aselaji' => $aselaji));
    }

    public static function ik(){
    	self::check_logged_in();
    	$aselaji = Aselaji::getId('Ilmakivääri');
    	$aselaji = $aselaji['id'];
    	TuloksetController::aselajinEnnatykset($aselaji);
    }

    public static function pk(){
    	self::check_logged_in();
    	$aselaji = Aselaji::getId('Pienoiskivääri');
    	$aselaji = $aselaji['id'];
    	TuloksetController::aselajinEnnatykset($aselaji);
    }

    public static function vk(){
    	self::check_logged_in();
    	$aselaji = Aselaji::getId('300m Vakiokivääri');
    	$aselaji = $aselaji['id'];
    	TuloksetController::aselajinEnnatykset($aselaji);
    }

    public static function kk(){
    	self::check_logged_in();
    	$aselaji = Aselaji::getId('300m Kivääri');
    	$aselaji = $aselaji['id'];
    	TuloksetController::aselajinEnnatykset($aselaji);
    }

    public static function rajaa($aselaji){
    	
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
        }
        if ($next_page > $pages) {
            $next_page = null;
        }

		if(isset($parametrit['search'])){
			$options['search'] = $parametrit['search'];
		}

		$options['rajaa'] = $aselaji;
		$options['sivun_koko'] = $page_size;
		$options['sivu'] = $page;
		$tulokset = Tulos::kaikki($options);

		if(count($tulokset) == 0){
			$rajaus = true;
			if(!isset($parametrit['search'])){
				$tuloksia = true;
				$tyhja = 'Ei tuloksia tällä rajauksella';
		    }else{
		    	$tuloksia = true;
		    	$tyhja = 'Ei tuloksia annetulla hakusanalla';
		    }
		}else{
			$rajaus = false;
			$tuloksia = true;
			$tyhja = false;
		}

		if($aselaji == 1){
			$aselaji = 'ilmakivaari';
		}
		if($aselaji == 2){
			$aselaji = 'pienoiskivaari';
		}
		if($aselaji == 3){
			$aselaji = 'vakiokivaari';
		}
		if($aselaji == 4){
			$aselaji = '300mkivaari';
		}

		View::make('tulos/tulokset.html', array('tulokset' => $tulokset, 'page_size' => $page_size, 'pages' => $pages, 'page' => $page, 'next_page' => $next_page, 'prev_page' => $prev_page, 'tuloksia' => $tuloksia, 'tyhja' => $tyhja, 'rajaus' => $rajaus, 'aselaji' => $aselaji));
    }

    public static function rajaaik(){
    	$aselaji = Aselaji::getId('Ilmakivääri');
    	TuloksetController::rajaa((int)$aselaji['id']);
    }

    public static function rajaapk(){
    	$aselaji = Aselaji::getId('Pienoiskivääri');
    	TuloksetController::rajaa((int)$aselaji['id']);
    }

    public static function rajaavk(){
    	$aselaji = Aselaji::getId('300m Vakiokivääri');
    	TuloksetController::rajaa((int)$aselaji['id']);    }

    public static function rajaakk(){
    	$aselaji = Aselaji::getId('300m Kivääri');
    	TuloksetController::rajaa((int)$aselaji['id']);
    }


}