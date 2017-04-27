<?php

class Sarja extends BaseModel{

	public $id, $arvo, $lisatiedot, $tulos;

	public function __construct($attributes){
		parent::__construct($attributes);
		$this->validators = array('validoiArvo');
	}

	public static function kaikki($options){

		if(isset($options['sivu']) && isset($options['sivun_koko'])){
            $sivun_koko = $options['sivun_koko'];
            $sivu = $options['sivu'];
            $query = DB::connection()->prepare('SELECT Sarja.arvo, Sarja.id, Sarja.lisatiedot, Tulos.id AS tulos FROM Sarja LEFT JOIN Tulos ON Sarja.tulos = Tulos.id AND Tulos.kayttaja = :kayttaja WHERE Tulos.kayttaja = :kayttaja LIMIT :limit OFFSET :offset');

            $query->execute(array('kayttaja' => $_SESSION['kayttaja'], 'limit' => $sivun_koko, 'offset' => $sivun_koko * ($sivu - 1)));
        }else{
            $sivun_koko = 10;
            $sivu = 1;
            $query = DB::connection()->prepare('SELECT Sarja.arvo, Sarja.id, Sarja.lisatiedot, Tulos.id AS tulos FROM Sarja LEFT JOIN Tulos ON Sarja.tulos = Tulos.id AND Tulos.kayttaja = :kayttaja WHERE Tulos.kayttaja = :kayttaja');

            $query->execute(array('kayttaja' => $_SESSION['kayttaja']));
        }

		

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

  	    $query = DB::connection()->prepare('INSERT INTO Sarja (arvo, lisatiedot, tulos) VALUES (:arvo, :lisatiedot, :tulos) RETURNING id');

  	    $query->execute(array('arvo' => $this->arvo, 'lisatiedot' => $this->lisatiedot, 'tulos' => $this->tulos));

  	    $rivi = $query->fetch();
      	Kint::trace();
  	    Kint::dump($rivi);
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
    	$apu = (int)$this->id;
    	$query = DB::connection()->prepare('DELETE FROM Sarja WHERE tulos = :apu');

    	$query->execute(array('apu' => $apu));

    	$rivi = $query->fetch();
    }

    public function laske(){
        $query = DB::connection()->prepare('SELECT COUNT(*) FROM Sarja WHERE kayttaja = :id');
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
  	    if(strlen($this->arvo) < 3){
  	    	$virheet[] = 'Sarjan arvon tulee olla vähintään kolme merkkiä pitkä.';
  	    }
        if(is_numeric($this->arvo) == false){
            $virheet[] = 'Sarjan arvon tulee olla numero!';
        }

  	    return $virheet;
    }
}