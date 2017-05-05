<?php

class Sarja extends BaseModel{

	public $id, $arvo, $lisatiedot, $tulos;

	public function __construct($attributes){
		parent::__construct($attributes);
		$this->validators = array('validoiArvo');
	}

	public static function kaikki($options){

		$querystring = 'SELECT Sarja.arvo, Sarja.id, Sarja.lisatiedot, Tulos.kilpailumuoto, Tulos.id AS tulos FROM Sarja LEFT JOIN Tulos ON Sarja.tulos = Tulos.id AND Tulos.kayttaja = :kayttaja WHERE Tulos.kayttaja = :kayttaja';

		if(isset($options['sivu']) && isset($options['sivun_koko'])){
			if(isset($options['kilpailumuoto'])){
				$querystring .= ' AND Tulos.kilpailumuoto = :kilpailumuoto';
            	$sivun_koko = $options['sivun_koko'];
            	$sivu = $options['sivu'];
            	$querystring .= ' LIMIT :limit OFFSET :offset';
            	$query = DB::connection()->prepare($querystring);

            $query->execute(array('kayttaja' => $_SESSION['kayttaja'], 'limit' => $sivun_koko, 'offset' => $sivun_koko * ($sivu - 1), 'kilpailumuoto' => $options['kilpailumuoto']));
        	}else{
        		$sivun_koko = $options['sivun_koko'];
            	$sivu = $options['sivu'];
            	$querystring .= ' LIMIT :limit OFFSET :offset';
            	$query = DB::connection()->prepare($querystring);

            $query->execute(array('kayttaja' => $_SESSION['kayttaja'], 'limit' => $sivun_koko, 'offset' => $sivun_koko * ($sivu - 1)));
        	}
        }else{
        	if(isset($options['kilpailumuoto'])){
				$querystring .= ' AND Tulos.kilpailumuoto = :kilpailumuoto';
        		$query = DB::connection()->prepare($querystring);
            	$query->execute(array('kayttaja' => $_SESSION['kayttaja'], 'kilpailumuoto' => $options['kilpailumuoto']));
        	}else{
        		$query = DB::connection()->prepare($querystring);
            	$query->execute(array('kayttaja' => $_SESSION['kayttaja']));
        	}
        }	

		$rivit = $query->fetchAll();
		$sarjat = array();

		foreach ($rivit as $rivi){

			if($rivi['kilpailumuoto'] == 2){
				$rivi['arvo'] = (int)$rivi['arvo'];
			}
			if($rivi['kilpailumuoto'] == 3){
				$rivi['arvo'] = (int)$rivi['arvo'];
			}

			$sarjat[] = new Sarja(array(
				'id' => $rivi['id'],
				'arvo' => $rivi['arvo'],
				'lisatiedot' => $rivi['lisatiedot'],
				 'tulos' => $rivi['tulos']
				));
		}

		return $sarjat;
	}

	public static function etsi($id){
		$query = DB::connection()->prepare('SELECT * FROM Sarja WHERE id = :id LIMIT 1');
		$query->execute(array('id' => $id));

		$rivi = $query->fetch();
		
		if($rivi){
			$sarja = new Sarja(array(
				'id' => $rivi['id'],
				'arvo' => $rivi['arvo'],
				'lisatiedot' => $rivi['lisatiedot'],
				'tulos' => $rivi['tulos']
			));

			return $sarja;
		}
		return null;
	}

	public static function sarjanTulos($id){
		$query = DB::connection()->prepare('SELECT Tulos.id, Tulos.kilpailumuoto FROM Sarja LEFT JOIN Tulos ON Sarja.tulos = Tulos.id WHERE Sarja.id = :id');

		$query->execute(array('id' => $id));

		$rivi = $query->fetch();

		return $rivi;
	}

	public function tallenna(){

  	    $query = DB::connection()->prepare('INSERT INTO Sarja (arvo, lisatiedot, tulos) VALUES (:arvo, :lisatiedot, :tulos) RETURNING id');

  	    $query->execute(array('arvo' => $this->arvo, 'lisatiedot' => $this->lisatiedot, 'tulos' => $this->tulos));

  	    $rivi = $query->fetch();
      	$this->id = $rivi['id'];
    }

    public function paivita($id){

    	$query = DB::connection()->prepare('UPDATE Sarja SET arvo = :arvo, lisatiedot = :lisatiedot WHERE id = :id');

    	$query->execute(array('arvo' => $this->arvo, 'lisatiedot' => $this->lisatiedot, 'id' => (int)$id));

    	$rivi = $query->fetch();
    	Kint::trace();
    	Kint::dump($rivi);
    }

    public function poista(){
    	$apu = (int)$this->tulos;
    	$query = DB::connection()->prepare('DELETE FROM Sarja WHERE tulos = :apu');

    	$query->execute(array('apu' => $apu));

    	$rivi = $query->fetch();
    }

    public function laske($kilpailumuoto){
        $query = DB::connection()->prepare('SELECT COUNT(Sarja.arvo) FROM Sarja LEFT JOIN Tulos ON Sarja.tulos = Tulos.id AND Tulos.kayttaja = :kayttaja WHERE Tulos.kayttaja = :kayttaja AND kilpailumuoto = :kilpailumuoto');
        $query->execute(array('kayttaja' => $_SESSION['kayttaja'], 'kilpailumuoto' => $kilpailumuoto));
        $rivi = $query->fetch();

        return $rivi;
    }

    public function laskeKaikki(){
        $query = DB::connection()->prepare('SELECT COUNT(Sarja.arvo) FROM Sarja LEFT JOIN Tulos ON Sarja.tulos = Tulos.id AND Tulos.kayttaja = :kayttaja WHERE Tulos.kayttaja = :kayttaja');
        $query->execute(array('kayttaja' => $_SESSION['kayttaja']));
        $rivi = $query->fetch();

        return $rivi;
    }

    public function validoiArvo(){
  	    $virheet = array();
  	    if($this->arvo == '' || $this->arvo == null){
  	    	$virheet[] = 'Sarja ei saa olla tyhjä!';
  	    }
        if(is_numeric($this->arvo) == false){
            $virheet[] = 'Sarjan arvon tulee olla numero!';
        }      
        if($this->arvo > 109.0){
        	$virheet[] = 'Sarjan arvo liian suuri';
        }
        if($this->arvo < 40){
        	$virheet[] = 'Sarjan arvo liian pieni';
        }
        
  	    return $virheet;
    }
}