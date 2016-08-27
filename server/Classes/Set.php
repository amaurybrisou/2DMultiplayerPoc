<?php

require_once 'Player.php';

/**
 * Set
 */
class Set implements JsonSerializable {
  
  private $height;
  private $width;
  private $attach = "#canvas";
  private $cell = [
  	"img" => "client/img/grass.jpg", 
  	"size" => 50
  ];
  private $grid = true;
  
  public function __construct($pHeight = 10, $pWidth = 10, $pMasStepDistance = 1)
  {
    $this->height = $pHeight;
    $this->width = $pWidth;
    $this->max_step_distance = $pMasStepDistance;
  }
  
  public function jsonSerialize(){
    return [
        'height' => $this->height,
        'width' => $this->width,
        'attach' => $this->attach,
        'grid' => $this->grid,
        'cell' => $this->cell,
        'max_step_distance' => $this->max_step_distance
    ];
  }
}

?>