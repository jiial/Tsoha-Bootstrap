<?php

class Tulos extends BaseModel{

	public $id, $arvo, $kilpailu, $paivamaara, $lisatiedot, $napakympit, $kayttaja, $aselaji, $kilpailumuoto;
	
	public function __construct($attributes){
		parent::__construct($attributes);
		$this->validators = array('validoiArvo', 'validoiPaivamaara', 'validoiKilpailu', 'validoiNapakympit');
	}

	public static function kaikki($id){

		$query = DB::connection()->prepare('SELECT * FROM Tulos WHERE kayttaja = :id');

		$query->execute(array('id' => $id));

		$rivit = $query->fetchAll();
		$tulokset = array();


		foreach ($rivit as $rivi) {
			
			$tulokset[] = new Tulos(array(
				'id' => $rivi['id'], 
				'arvo' => $rivi['arvo'],
				'kilpailu' => $rivi['kilpailu'],
				'paivamaara' => $rivi['paivamaara'],
				'lisatiedot' => $rivi['lisatiedot'],
				'napakympit' => $rivi['napakympit'],
				'kayttaja' => $rivi['kayttaja'],
				'aselaji' => $rivi['aselaji'],
				'kilpailumuoto' => $rivi['kilpailumuoto']
				));
		}

		return $tulokset;
	}

	public static function etsi($id){
        $query = DB::connection()->prepare('SELECT * FROM Tulos WHERE id = :id LIMIT 1');
        $query->execute(array('id' => $id));
        $rivi = $query->fetch();

        if($rivi){
            $tulos = new Tulos(array(
            'id' => $rivi['id'], 
			'arvo' => $rivi['arvo'],
			'kilpailu' => $rivi['kilpailu'],
			'paivamaara' => $rivi['paivamaara'],
			'lisatiedot' => $rivi['lisatiedot'],
			'napakympit' => $rivi['napakympit'],
			'kayttaja' => $rivi['kayttaja'],
			'aselaji' => $rivi['aselaji'],
			'kilpailumuoto' => $rivi['kilpailumuoto']
		    ));

            return $tulos;
        }

        return null;
    }

    public function tallenna(){

  	    $query = DB::connection()->prepare('INSERT INTO Tulos (arvo, kilpailu, paivamaara, lisatiedot, napakympit, kayttaja, aselaji, kilpailumuoto) VALUES (:arvo, :kilpailu, :paivamaara, :lisatiedot, :napakympit, :kayttaja, :aselaji, :kilpailumuoto) RETURNING id');

  	    $query->execute(array('arvo' => $this->arvo, 'kilpailu' => $this->kilpailu, 'paivamaara' => $this->paivamaara, 'lisatiedot' => $this->lisatiedot, 'napakympit' => $this->napakympit, 'kayttaja' => $this->kayttaja, 'aselaji' => $this->aselaji, 'kilpailumuoto' => $this->kilpailumuoto));

  	    $rivi = $query->fetch();
      	Kint::trace();
  	    Kint::dump($rivi);
      	$this->id = $rivi['id'];
    }

    public function paivita($id){

    	$query = DB::connection()->prepare('UPDATE Tulos SET arvo = :arvo, kilpailu = :kilpailu, paivamaara = :paivamaara, lisatiedot = :lisatiedot, napakympit = :napakympit, kayttaja = :kayttaja, aselaji = :aselaji, kilpailumuoto = :kilpailumuoto WHERE id = :id');

    	$query->execute(array(
    		'arvo' => $this->arvo,
    		'kilpailu' => $this->kilpailu,
    		'paivamaara' => $this->paivamaara,
    		'lisatiedot' => $this->lisatiedot,
    		'napakympit' => $this->napakympit,
    		'kayttaja' => $this->kayttaja, 
    		'aselaji' => $this->aselaji, 
    		'kilpailumuoto' => $this->kilpailumuoto, 
    		'id' => (int)$id));

    	$rivi = $query->fetch();
    	Kint::trace();
    	Kint::dump($rivi);
    }

    public function poista(){
    	$apu = $this->id;
    	$query = DB::connection()->prepare('DELETE FROM Tulos WHERE id = :apu');

    	$query->execute(array('apu' => $apu));

    	$rivi = $query->fetch();

    	Kint::dump($rivi);
    }

    public function validoiArvo(){
  	    $virheet = array();
  	    if($this->arvo == '' || $this->arvo == null){
  	    	$virheet[] = 'Tulos ei saa olla tyhjä!';
  	    }
  	    if(strlen($this->arvo) < 3){
  	    	$virheet[] = 'Tuloksen arvon tulee olla vähintään kolme merkkiä pitkä.';
  	    }

  	    return $virheet;
    }

    public function validoiKilpailu(){
    	$kisa = $this->kilpailu;
    	$virheet = $this->validoiMerkkijono($kisa);

    	return $virheet;
    }

    public function validoiNapakympit(){
    	$virheet = array();
    	if(is_numeric($this->napakympit) == false){
    		$virheet[] = 'Napakymppien määrän tulee olla numero!';
    		return $virheet;
    	}
    	if($this->kilpailumuoto == 1){
    		if($this->napakympit > 60){
    			$virheet[] = 'Liikaa napakymppejä';
    		}
    		if($this->napakympit < 0){
    			$virheet[] = 'Liian vähän napakymppejä';
    		}
    	}
    	if($this->kilpailumuoto == 2){
    		if($this->napakympit > 120){
    			$virheet[] = 'Liikaa napakymppejä';
    		}
    		if($this->napakympit < 0){
    			$virheet[] = 'Liian vähän napakymppejä';
    		}
    	}
    	
    	return $virheet;
    }

    public function validoiPaivamaara(){
    	$virheet = array();

    	if($this->paivamaara == '' || $this->paivamaara == null){
    		$virheet[] = 'Päivämäärä ei saa olla tyhjä!';
    	}
    	if(strlen($this->paivamaara) != 10){
    		$virheet[] = 'Päivämäärässä liian vähän merkkejä, muistithan etunollat!';
    	}
    	$str = $this->paivamaara;
    	sscanf($str, '%d-%d-%d', $vuosi, $kuukausi, $paiva);
    	if($paiva == '' || $kuukausi == '' || $vuosi == ''){
    		$virheet[] = 'Päivä, kuukausi tai vuosi puuttuu!';
    	}
    	//if(strlen($paiva) != 2 || strlen($kuukausi) != 2 || strlen($vuosi) != 4){
    		//$virheet[] = 'Päivämäärä ei kelvollinen (pitää olla muotoa [vvvv.kk.pp])!';
    	//}
    	if($kuukausi > 12 || $kuukausi < 1){
    		$virheet[] = 'Virheellinen kuukausi!';
    	} 
    	if($paiva > 31 || $paiva < 1){
    		$virheet[] = 'Virheellinen päivä!';
    	} 
    	if($vuosi > date('Y') || $vuosi < 1900){
    		$virheet[] = 'Virheellinen vuosi!';
    	} 

    	return $virheet;
    }

}