<?php

class Tulos extends BaseModel{

	public $id, $arvo, $kilpailu, $paivamaara, $lisatiedot, $napakympit, $kayttaja, $aselaji, $kilpailumuoto;
	
	public function __construct($attributes){
		parent::__construct($attributes);
		$this->validators = array('validoiArvo', 'validoiPaivamaara', 'validoiKilpailu', 'validoiNapakympit');
	}

	public static function kaikki($options){

        $query_string = 'SELECT * FROM Tulos WHERE kayttaja = :kayttaja_id';
        $parametrit = array('kayttaja_id' => $options['kayttaja_id']);

        if(isset($options['rajaa'])){
            $query_string .= ' AND aselaji = :aselaji';
            $parametrit['aselaji'] = $options['rajaa'];
        }

        if(isset($options['search'])){
            $query_string .= ' AND kilpailu LIKE :like';
            $parametrit['like'] = '%' . $options['search'] . '%';
        }

        if(isset($options['sivu']) && isset($options['sivun_koko'])){
            $sivun_koko = $options['sivun_koko'];
            $sivu = $options['sivu'];
        }else{
            $sivun_koko = 10;
            $sivu = 1;
        }
        $query_string .= ' LIMIT :limit OFFSET :offset';
        $parametrit['limit'] = $sivun_koko;
        $parametrit['offset'] = $sivun_koko * ($sivu - 1);

		$query = DB::connection()->prepare($query_string);

		$query->execute($parametrit);

		$rivit = $query->fetchAll();
        
		$tulokset = array();


		foreach ($rivit as $rivi) {

			$query = DB::connection()->prepare('SELECT nimi FROM Aselaji WHERE id = :id');

		    $query->execute(array('id' => $rivi['aselaji']));

		    $apu = $query->fetch();
		    $aselaji = $apu['nimi'];

		    $query = DB::connection()->prepare('SELECT nimi FROM Kilpailumuoto WHERE id = :id');

		    $query->execute(array('id' => $rivi['kilpailumuoto']));

		    $apu = $query->fetch();
		    $kilpailumuoto = $apu['nimi'];

            if($kilpailumuoto == '3x40 ls'){
                $rivi['arvo'] = (int)$rivi['arvo'];
            }	
            if($kilpailumuoto == '3x20 ls'){
                $rivi['arvo'] = (int)$rivi['arvo'];
            }

			
			$tulokset[] = new Tulos(array(
				'id' => $rivi['id'], 
				'arvo' => $rivi['arvo'],
				'kilpailu' => $rivi['kilpailu'],
				'paivamaara' => $rivi['paivamaara'],
				'lisatiedot' => $rivi['lisatiedot'],
				'napakympit' => $rivi['napakympit'],
				'kayttaja' => $rivi['kayttaja'],
				'aselaji' => $aselaji,
				'kilpailumuoto' => $kilpailumuoto
				));
		}

		return $tulokset;
	}


	public static function etsi($id){
        $query = DB::connection()->prepare('SELECT * FROM Tulos WHERE id = :id LIMIT 1');
        $query->execute(array('id' => $id));
        $rivi = $query->fetch();

        if($rivi){
        	$query = DB::connection()->prepare('SELECT nimi FROM Aselaji WHERE id = :id');

		    $query->execute(array('id' => $rivi['aselaji']));

		    $apu = $query->fetch();
		    $aselaji = $apu['nimi'];

		    $query = DB::connection()->prepare('SELECT nimi FROM Kilpailumuoto WHERE id = :id');

		    $query->execute(array('id' => $rivi['kilpailumuoto']));

		    $apu = $query->fetch();
		    $kilpailumuoto = $apu['nimi'];	

            $tulos = new Tulos(array(
            'id' => $rivi['id'], 
			'arvo' => $rivi['arvo'],
			'kilpailu' => $rivi['kilpailu'],
			'paivamaara' => $rivi['paivamaara'],
			'lisatiedot' => $rivi['lisatiedot'],
			'napakympit' => $rivi['napakympit'],
			'kayttaja' => $rivi['kayttaja'],
			'aselaji' => $aselaji,
			'kilpailumuoto' => $kilpailumuoto
		    ));

            return $tulos;
        }

        return null;
    }

    public function tallenna(){

  	    $query = DB::connection()->prepare('INSERT INTO Tulos (arvo, kilpailu, paivamaara, lisatiedot, napakympit, kayttaja, aselaji, kilpailumuoto) VALUES (:arvo, :kilpailu, :paivamaara, :lisatiedot, :napakympit, :kayttaja, :aselaji, :kilpailumuoto) RETURNING id');

  	    $query->execute(array('arvo' => $this->arvo, 'kilpailu' => $this->kilpailu, 'paivamaara' => $this->paivamaara, 'lisatiedot' => $this->lisatiedot, 'napakympit' => $this->napakympit, 'kayttaja' => $this->kayttaja, 'aselaji' => $this->aselaji, 'kilpailumuoto' => $this->kilpailumuoto));

  	    $rivi = $query->fetch();
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

    public function tuloksen_sarjat($id){
        $query = DB::connection()->prepare('SELECT * FROM Sarja WHERE tulos = :id');

        $query->execute(array('id' => $id));

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

    public function laske(){
        $query = DB::connection()->prepare('SELECT COUNT(*) FROM Tulos WHERE kayttaja = :id');
        $query->execute(array('id' => $_SESSION['kayttaja']));
        $rivi = $query->fetch();

        return $rivi;
    }

    public function ennatykset($aselaji, $kilpailumuoto){
        $query = DB::connection()->prepare('SELECT * FROM Tulos WHERE kayttaja = :id AND (aselaji = :aselaji AND kilpailumuoto = :kilpailumuoto) ORDER BY arvo DESC LIMIT 1');
        $query->execute(array('id' => $_SESSION['kayttaja'], 'aselaji' => $aselaji, 'kilpailumuoto' => $kilpailumuoto['id']));
        $rivi = $query->fetch();

        return $rivi;
    }

    public function validoiArvo(){
  	    $virheet = array();
  	    if($this->arvo == '' || $this->arvo == null){
  	    	$virheet[] = 'Tulos ei saa olla tyhjä!';
  	    }
        if($this->kilpailumuoto == 2 || $this->kilpailumuoto == 3){
  	        if(strlen($this->arvo) < 2){
  	    	    $virheet[] = 'Tuloksen arvon tulee olla vähintään kolme merkkiä pitkä.';
  	        }
            if($this->kilpailumuoto == 2){
                if($this->arvo > 600){
                    $virheet[] = 'Tuloksen arvo liian suuri';
                }
            }
            if($this->kilpailumuoto == 3){
                if($this->arvo > 1200){
                    $virheet[] = 'Tuloksen arvo liian suuri';
                }
            }
        }else{
            if(strlen($this->arvo) < 5){
                $virheet[] = 'Tuloksen arvon tulee olla vähintään viisi merkkiä pitkä (esim 599.5).';
            }
            if($this->arvo > 650.0){
                $virheet[] = 'Tuloksen arvo liian suuri';
            }
        }
        if(is_numeric($this->arvo) == false){
            $virheet[] = 'Tuloksen arvon tulee olla numero!';
        }
        if($this->arvo < 200){
            $virheet[] = 'Tuloksen arvo liian pieni';
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
    	if($this->kilpailumuoto == 4){
    		if($this->napakympit > 40){
    			$virheet[] = 'Liikaa napakymppejä';
    		}
    	}
    	if($this->kilpailumuoto == 3){
    		if($this->napakympit > 120){
    			$virheet[] = 'Liikaa napakymppejä';
    		}
    	}else{
            if($this->napakympit > 60){
                $virheet[] = 'Liikaa napakymppejä';
            }
        }
        if($this->napakympit < 0){
                $virheet[] = 'Liian vähän napakymppejä';
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
    	if($kuukausi > 12 || $kuukausi < 1){
    		$virheet[] = 'Virheellinen kuukausi!';
    	} 
    	if($paiva > 31 || $paiva < 1){
    		$virheet[] = 'Virheellinen päivä!';
    	} 
    	if($vuosi > (int)date('Y') || $vuosi < 1900){
    		$virheet[] = 'Virheellinen vuosi!';
    	}
        if(!checkdate($kuukausi, $paiva, $vuosi)){
            $virheet[] = 'Päivämäärä virheellinen';
        }

        $nyt = new DateTime();
        $pvm = DateTime::createFromFormat('Y-m-d', $this->paivamaara);
        if($pvm > $nyt){
            $virheet[] = 'Päivämäärä ei voi olla tulevaa aikaa!';
        }
    	return $virheet;
    }

}