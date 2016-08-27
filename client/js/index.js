$(document).ready(function(){
	//get the Set description object
	$.get('api.php?method=getSet', function(data){		
		var program = new Program(data);
		program.start('#canvas');

	}, 'json');
});