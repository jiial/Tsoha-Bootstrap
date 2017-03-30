<?php

class Tulos extends BaseModel{

	public $id, $arvo, $kilpailu, $paivamaara, $lisatiedot, $napakympit, $kayttaja, $aselaji, $kilpailumuoto;
	
	public function __construct($attributes){
		parent::__construct($attributes);
	}

	public static function kaikki(){

		$query = DB::connection()->prepare('SELECT * FROM Tulos');

		$query->execute();

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
}