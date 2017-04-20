<?php

class Sarja extends BaseModel{

	public $id, $arvo, $lisatiedot, $tulos;

	public function __construct($attributes){
		parent::__construct($attributes);
		$this->validators = array('validoiArvo');
	}

	public static function kaikki(){
		$query = DB::connection()->prepare('SELECT * FROM Sarja LEFT JOIN Tulos ON Sarja.tulos = Tulos.id AND Tulos.kayttaja = :kayttaja WHERE Tulos.kayttaja = :kayttaja');

		$query->execute(array('kayttaja' => $_SESSION['kayttaja']));

		$rivit = $query->fetchAll();
		
		$sarjat = array();

		foreach ($rivit as $rivi){
			$sarjat[] = new Sarja(array(
				'id' => $rivi['id'],
				'arvo' => $rivi['arvo'],
				'lisatiedot' => $rivi['lisatiedot'],
				 'tulos' => $rivi['tulos']
				));
		}
		Kint::dump($sarjat);
		die();
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

	public static function tuloksen_sarjat($id){
		$query = DB::connection()->prepare('SELECT * FROM Sarja WHERE tulos = :id');

		$query->execute(array('id' => $id));

		$rivit = $query->fetchAll();
		$sarjat = array();

		foreach ($sarjat as $sarja){
			$sarjat = new Sarja(array(
				'arvo' => $rivi['arvo'],
				'lisatiedot' => $rivi['lisatiedot'],
				 'tulos' => $rivi['tulos']
				));
		}

		return $sarjat;
	}

	public function tallenna(){

  	    $query = DB::connection()->prepare('INSERT INTO Sarja (arvo, lisatiedot, kilpailu) VALUES (:arvo, :lisatiedot, :kilpailu) RETURNING id');

  	    $query->execute(array('arvo' => $this->arvo, 'lisatiedot' => $this->lisatiedot, 'tulos' => $this->tulos));

  	    $rivi = $query->fetch();
      	Kint::trace();
  	    Kint::dump($rivi);
      	$this->id = $rivi['id'];
    }

    public function validoiArvo(){
  	    $virheet = array();
  	    if($this->arvo == '' || $this->arvo == null){
  	    	$virheet[] = 'Sarja ei saa olla tyhjä!';
  	    }
  	    if(strlen($this->arvo) < 3){
  	    	$virheet[] = 'Sarjan arvon tulee olla vähintään kolme merkkiä pitkä.';
  	    }
        if(is_numeric($this->arvo) == false){
            $virheet[] = 'Sarjan arvon tulee olla numero!';
        }

  	    return $virheet;
    }
}