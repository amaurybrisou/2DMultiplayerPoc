function Set(options){
	var options = options || {};

	this.max_step_distance   = parseInt(options.max_step_distance) || 1;
	this.height   = options.height   || 15;
	this.width    = options.width    || 15;
	this.attach   = options.attach   || "#canvas";
	this.cell = {};
	this.cell.size = options.cell.size || 80;
	this.cell.img = options.cell.img || "grass.png";
	
	this.grid 	  = !options.grid  ? false : true;
}

Set.prototype.draw = function(attach) {
	var attach = $(attach);
	var td, tr, img, wh = this.cell.size + 'px';

	var table = $('<table>');

	for (var i = this.height - 1; i >= 0; i--) {
		tr = $('<tr>');
		for (var j = this.height - 1; j >= 0; j--) {
			td = $('<td style="background:url('+this.cell.img+');">');
			if(this.grid) td.css('border', '1px solid black') ;
			td.height(wh).width(wh).attr('id', i + '_' + j);
			tr.append(td);
		}
		table.append(tr);
	}
	attach.append(table);
};


Set.prototype.addPlayers = function(players) {
	var addPlayer = function(id, player){
		$('.'+id).empty().removeClass(id);


		var cell = $('#' + player.position.x + '_' + player.position.y);

		cell.addClass(id);

		
		cell.append('<img src="'+player.img+'" />');
	}

	for(var id in players){
		addPlayer(id, players[id]);
	};
};

