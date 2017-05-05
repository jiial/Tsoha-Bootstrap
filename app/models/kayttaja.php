<?php

class Kayttaja extends BaseModel{

	public $id, $nimi, $password;

	public function __construct($attributes){

		parent::__construct($attributes);
    $this->validators = array('validoiSalasana', 'validoiNimi');
	}

	public function autentikoi($kayttajanimi, $salasana){
		$query = DB::connection()->prepare('SELECT * FROM Kayttaja WHERE nimi = :kayttajanimi AND password = :salasana LIMIT 1');
		$query->execute(array('kayttajanimi' => $kayttajanimi, 'salasana' => $salasana));
		$rivi = $query->fetch();
		if($rivi){
  			$kayttaja = new Kayttaja(array('id' => $rivi['id'],
  			 'nimi' => $rivi['nimi'],
  			  'password' => $rivi['password']
  			  ));
  			return $kayttaja;
		}else{
 			return null;
		}
	}

	public static function etsi($id){
    $query = DB::connection()->prepare('SELECT * FROM Kayttaja WHERE id = :id LIMIT 1');
    $query->execute(array('id' => $id));
    $rivi = $query->fetch();

    if($rivi){
      $kayttaja = new Kayttaja(array(
      'id' => $rivi['id'],
  		'nimi' => $rivi['nimi'],
  		'password' => $rivi['password']
  		));
  		return $kayttaja;
    }
    return null;
  }

  public function luo(){

    $query = DB::connection()->prepare('INSERT INTO Kayttaja (nimi, password) VALUES (:nimi, :password) RETURNING id');

    $query->execute(array(
      'nimi' => $this->nimi,
      'password' => $this->password
    ));

    $rivi = $query->fetch();
    Kint::trace();
    Kint::dump($rivi);
    $this->id = $rivi['id'];
  }

  public function validoiSalasana(){
    $virheet = array();

    if(strlen($this->password) < 4){
      $virheet[] = 'Salasana liian lyhyt';
    }
    if(strlen($this->password) > 12){
      $virheet[] = 'Salasana liian pitkä';
    }
    if($this->password == $this->nimi){
      $virheet[] = 'Käyttäjätunnus ja salasana eivät voi olla samat!';
    }

    return $virheet;
  }  

  public function validoiNimi(){
    $virheet = array();

    if(strlen($this->nimi) < 2){
      $virheet[] = 'Käyttäjätunnus liian lyhyt';
    }
    if(strlen($this->nimi) > 12){
      $virheet[] = 'Käyttäjätunnus liian pitkä';
    }

    $query = DB::connection()->prepare('SELECT * FROM Kayttaja WHERE nimi = :nimi');
    $query->execute(array('nimi' => $this->nimi));

    $rivi = $query->fetch();

    if($rivi != null){
      $virheet[] = 'Käyttäjätunnus varattu';
    }

    return $virheet;
  }    
}