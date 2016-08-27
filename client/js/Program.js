function Program(data) {
	this.set = new Set(data.set);
	this.players = [];
	if(data.players && data.players.length){
		this.addPlayers(data.players);
	}
	this.player;
}

Program.prototype.start = function(attach) {
	var attach = attach || '#canvas';
	this.set.draw(attach);
	this.addPlayer();
	this.refreshGameState();
};

Program.prototype.addPlayer = function() {
	var that = this;
	$.post('api.php?method=addPlayer', { name: 'Amaury', img: 'mario.png' }, function(data){
		that.player = new Player(data);
		that.isAllowedToMove();
		that.bindControls();
	}, 'json');
};

Program.prototype.addPlayers = function(players) {
	for(key in players){
		if( key in this.players){
			var player = this.players[key];
			player.move(players[key].position);
			continue;
		}
		var player = players[key];
		player = new Player(player);
		this.players[key] = player;
		player.move();
	}
};



Program.prototype.refreshGameState = function() {
	var that = this;
	var interval = function(){
		setTimeout(function(){
			$.get('api.php?method=refresh', function(data){
				that.addPlayers(data);
			}, 'json');
			interval();
		}, 1000);
	}
	interval();
};


Program.prototype.isAllowedToMove = function(expectedPosition) {
	var that = this;
	$.post("api.php?method=movePlayer", expectedPosition || this.player.position, function(data){
		that.player.move(data.position);
	}, 'json');
};

Program.prototype.bindControls = function() {
	var that = this;
	var player = this.player;
	var max_step_distance = this.set.max_step_distance;
	$(document).keydown(function(e){
		var x = player.position.x;
		var y = player.position.y;
		var key = e.keyCode;
		switch(key){
			case 40:
				x-=max_step_distance;
				break;
			case 38:
				x+=max_step_distance;
				break;
			case 39:
				y-=max_step_distance;
				break;
			case 37:
				y+=max_step_distance;
				break;
			default:
				break;
		}

		that.isAllowedToMove({x:x, y:y});
		
	});
};