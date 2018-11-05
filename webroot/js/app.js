function dibujar(el, data, visita){
	$(el).empty();
    var result="";
    var letras = ['A','B','C','D','E','F','G','H','I','J'];

    for(i=data.timbres.length-1; i>=0; i--){
	    if(i==data.timbres.length-1){
	      result += '<tr class="row">';
	    }

	    if(typeof visita !== 'undefined' && visita){
		    if(typeof data.timbres[i].porcentaje === 'undefined'){
		      var timbreColor= "rgb(148, 148, 148)"
		    }else{
		      var n = Math.round(data.timbres[i].porcentaje * 2.55);
		      var timbreColor= "rgb(255, " + (255 - n) +", 0)"
		    }

		    var atendioClass = 'glyphicon-stop';
		    var atendio = data.timbres[i].atendio;
		    if(atendio){
		      atendioClass = 'glyphicon-ok';
		    }else{
		      atendioClass = 'glyphicon-remove';
		    }

	    	result += '<td class="col-md-1"><button data-id="'+ data.timbres[i].id +'" data-atendio="'+atendio+'"><span style="color:'+ timbreColor +'" class="timbre glyphicon '+ atendioClass +'"></span></button></td>';
	    }else{
	    	result += '<td class="col-md-1"><button><span style="color:green" class="timbre glyphicon glyphicon-stop"></span></button></td>';
	    }

	    if(i==0){
	      result += '</tr>';
	    }else{
	      if(data.timbres[i].row != data.timbres[i-1].row){
	        result += '</tr>';
	        result += '<tr class="row">';
	      }
	    }
	  }r

	  $(el).append(result);
}