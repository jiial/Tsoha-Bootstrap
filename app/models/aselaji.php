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

}