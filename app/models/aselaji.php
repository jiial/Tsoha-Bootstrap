<?php

class Aselaji extends BaseModel{

	public $id, $nimi;

	public function __construct($attributes){
		parent::__construct($attributes);
		$this->validators = array();
	}

	public static function kaikki(){
		$query = DB::connection()->prepare('SELECT * FROM Aselaji');

		$query->execute(array());

		$rivit = $query->fetchAll();
		$aselajit = array();

		foreach ($rivit as $rivi){
			$aselajit[] = new Aselaji(array(
				'id' => $rivi['id'],
				'nimi' => $rivi['nimi']
				));
		}

		return $aselajit;
	}

	public static function getNimi($id){
		$query = DB::connection()->prepare('SELECT nimi FROM Aselaji WHERE id = :id');

		$query->execute(array('id' => $id));

		$rivi = $query->fetch();

		return $rivi;
	}

	public static function getId($nimi){
		$query = DB::connection()->prepare('SELECT id FROM Aselaji WHERE nimi = :nimi');

		$query->execute(array('nimi' => $nimi));

		$rivi = $query->fetch();

		return $rivi;
	}

	public static function kilpailumuodot($id){
		$query = DB::connection()->prepare('SELECT KilpailumuodonLaji.kilpailumuoto AS id, Kilpailumuoto.nimi AS nimi FROM KilpailumuodonLaji LEFT JOIN Kilpailumuoto ON KilpailumuodonLaji.kilpailumuoto = Kilpailumuoto.id  WHERE aselaji = :id');
		$query->execute(array('id' => $id));

		$rivit = $query->fetchAll();

		return $rivit;
	}

}