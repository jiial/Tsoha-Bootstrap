<?php

class SarjatController extends BaseController{
	public static function index(){
		self::check_logged_in();

		$sarjojen_maara = Sarja::laske();
		$sarjojen_maara = $sarjojen_maara['count'];
		
		$page_size = 10;
		$pages = ceil($sarjojen_maara/$page_size);

		$page = (isset($_GET['page']) AND (int) $_GET['page'] > 0) ? (int) $_GET['page'] : 1;
        $prev_page = $page - 1;
        $next_page = $page + 1;
        if ($prev_page < 1) {
            $prev_page = null;
        } elseif ($next_page > $pages) {
            $next_page = null;
        }

        $options = array();
        $options['sivun_koko'] = $page_size;
		$options['sivu'] = $page;

		$sarjat = Sarja::kaikki($options);

		View::make('sarja/sarjat.html', array('sarjat' => $sarjat, 'page_size' => $page_size, 'pages' => $pages, 'page' => $page));
	}

	public static function nayta($id){
		self::check_logged_in();
		$sarja = Sarja::etsi($id);

		View::make('sarja/sarja.html', array('sarja' => $sarja));
	}

	public static function muokkaa($id){
		self::check_logged_in();
		$sarja = Sarja::etsi($id);
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
}