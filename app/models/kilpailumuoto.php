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

}