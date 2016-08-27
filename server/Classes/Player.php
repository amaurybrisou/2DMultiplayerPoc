<?php

/**
* Player
*/
class Player implements JsonSerializable 
{
	private $id;
	private $name;	
	private $img;	
	public $position  = [ "x" => 0, "y" => 0];

	function __construct($name, $img = "client/img/mario.png")
	{
		$this->id = md5(session_id());
		$this->img = "client/img/" . $img;
		$this->name = $name;
	}

	public function jsonSerialize(){
		return [
		    'img' => $this->img,
		    'name' => $this->name,
		    'position' => $this->position,
		    'id' => $this->id
		];
	}

	public function getId(){
		return $this->id;
	}
}
?>