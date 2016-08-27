function Player(options){
	var options = options || {};
	this.position = options.position || { x:0, y:0 };
	this.name = options.name || 'Mario';
	this.img = options.img || 'mario.png';
	this.id = options.id;
	console.log(options);
};

Player.prototype.move = function(newPosition) {
	if(newPosition){
		var newPosition = { x: parseInt(newPosition.x), y: parseInt(newPosition.y)}
	} else {
		newPosition = this.position;
	}

	var currentCell = $('#' + this.position.x + '_' + this.position.y);
	currentCell.empty();

	//set new position
	this.position = newPosition || this.position;
	
	//show next img at position
	var nextCell = $('#' + this.position.x + '_' + this.position.y);
	nextCell.append($('<img src="'+this.img+'" />'));
};