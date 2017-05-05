<?php

class Kilpailumuoto extends BaseModel{

	public $id, $nimi;

	public function __construct($attributes){
		parent::__construct($attributes);
		$this->validators = array();
	}

	public static function kaikki(){
		$query = DB::connection()->prepare('SELECT * FROM Kilpailumuoto');

		$query->execute(array());

		$rivit = $query->fetchAll();
		$kilpailumuodot = array();

		foreach ($rivit as $rivi){
			$kilpailumuodot[] = new Kilpailumuoto(array(
				'id' => $rivi['id'],
				'nimi' => $rivi['nimi']
				));
		}

		return $kilpailumuodot;
	}

	public static function getNimi($id){
		$query = DB::connection()->prepare('SELECT nimi FROM Kilpailumuoto WHERE id = :id');

		$query->execute(array('id' => $id));

		$rivi = $query->fetch();

		return $rivi;
	}

	public static function getId($nimi){
		$query = DB::connection()->prepare('SELECT id FROM Kilpailumuoto WHERE nimi = :nimi');

		$query->execute(array('nimi' => $nimi));

		$rivi = $query->fetch();

		return $rivi;
	}

}