<?php

require_once('Classes/Set.php');
require_once('Classes/Player.php');
/**
* Program
*/
class Program implements JsonSerializable
{
	private $players = [];
	private $busyCells = [];
	private $set;

	const WIDTH = 10;
	const HEIGHT = 10;
	const MAX_STEP_DISTANCE = 1;
	const FILENAME = __DIR__ . '/database/game.save';
	/*
		Load game state $set and $players
	*/
	public function __construct(){
		if(file_exists(Program::FILENAME)){
			$fileObj = unserialize(file_get_contents(Program::FILENAME));
			$this->set = $fileObj->set;
			$this->players = $fileObj->players;	
			$this->busyCells = $fileObj->busyCells;			
		} else {
			$this->set = new Set(Program::HEIGHT, Program::WIDTH, Program::MAX_STEP_DISTANCE);
		}
	}

	/*
		Save game state to SESSION
	*/
	public function __destruct(){
		file_put_contents(Program::FILENAME, serialize($this), LOCK_EX);
	}
	public function jsonSerialize(){
		return ['set' => $this->set, 
			'players' => array_filter( $this->players, function($player){
				if($player->getId() !== md5(session_id())) return $player;
			}),
			'busyCells' => array_filter( $this->busyCells, function($id) {
				if($id !== md5(session_id())) return $id;
			}, ARRAY_FILTER_USE_KEY),
		];
	}

	public function getSet(){
		return json_encode($this);
	}

	public function refresh(){
		die(json_encode(array_filter( $this->players, function($player){
				if($player->getId() !== md5(session_id())) return $player;
			})));
	}


	public function addPlayer($name, $img)
	{
		if(array_key_exists(md5(session_id()), $this->players)) die(json_encode($this->players[md5(session_id())])); //if player exists, don't recreate it
		$player = new Player($name, $img); //create a new player
		$this->players[md5(session_id())] = $player; // add the player in players at his md5(session_id()) index
		$this->busyCells[md5(session_id())] = $player->position;
		die(json_encode($player)); //return player only
	}

	public function delPlayer()
	{
		unset($this->players[md5(session_id())]);
		unset($this->busyCells[md5(session_id())]);
	}

	public function delSet()
	{
		unset($this->set);
	}

	public function newSet(){
		unlink(Program::FILENAME);
		$this->set = new Set(Program::HEIGHT, Program::WIDTH, Program::MAX_STEP_DISTANCE);
		$this->players = [];
		$this->busyCells = [];
	}

	public function movePlayer($expectedPosition)
	{
		if(!array_key_exists(md5(session_id()), $this->players)) return; //if player doesn't exists, leave
		$player = $this->players[md5(session_id())]; // get the player in players
		if(
			$expectedPosition['x'] >= 0
			&& $expectedPosition['x'] < Program::HEIGHT //check if his expected position is legal
			&& $expectedPosition['y'] >= 0 
			&& $expectedPosition['y'] < Program::WIDTH
			&& (abs($expectedPosition['x'] - $player->position['x']) <= Program::MAX_STEP_DISTANCE )//player cannot move more than 1 cell each step
			&& (abs($expectedPosition['y'] - $player->position['y']) <= Program::MAX_STEP_DISTANCE )
			&& $this->isNotBusy($expectedPosition)
			){
				// position has been accepted
				$player->position = $expectedPosition; // update player position
				$this->players[md5(session_id())] = $player; //set back the player in players
				$this->busyCells[md5(session_id())] = $player->position;
		}
		die(json_encode($player)); //return player only
		
	}

	private function isNotBusy($expectedPosition){
		foreach ($this->busyCells as $key => $cell) {
			if($cell['x'] == $expectedPosition['x'] && $cell['y'] == $expectedPosition['y']){
				// var_dump($cell, $expectedPosition);
				return false;
			}
				
		}
		return true;
	}


	
}
?>