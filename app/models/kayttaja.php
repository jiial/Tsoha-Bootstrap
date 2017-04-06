<?php

class Kayttaja extends BaseModel{

	public $id, $nimi, $password;

	public function __construct($attributes){
		parent::__construct($attributes);
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
        	$kayttaja = new Kayttaja(array('id' => $rivi['id'],
  			 'nimi' => $rivi['nimi'],
  			  'password' => $rivi['password']
  			  ));
  			return $kayttaja;
        }
        return null;
    }    
}