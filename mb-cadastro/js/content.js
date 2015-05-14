/**
 * Variaveis do sistema
 */
var files;
var cancelar = false;



/**
 * Função que abre modal
 * @param  modal é o id da modal que deverá ser aberto
 * @param  obj é o link que que foi clicado
 * 
 */
function showModal(obj, modal){
	$(obj).closest('ul').children('.active').removeClass('active');
	$(obj).parent().addClass('active');
	$(modal).modal('show');
}



/**
 * Carrega a tabela com os arquivos
 */
$.get('app/dashboard.php',function(data){
	console.log(data);
	var objs = jQuery.parseJSON(data);
	var total= objs.length-1;
	for(i=0;i<=total;i++){
		span = document.createElement('span');
		span.classList.add('label');
		span.innerHTML = objs[i].status;
		if(objs[i].status == 'Pendente'){
			span.classList.add('label-danger');
		}else{
			span.classList.add('label-success');
		}

		tr = document.createElement('tr');
		td_1 = document.createElement('td');
		td_2 = document.createElement('td');
		td_3 = document.createElement('td');

		if(permissao == 1 && objs[i].status == 'Pendente'){
			link = document.createElement('a');
			link.classList.add('liberarlink');
			link.setAttribute('data-id',objs[i].id);
			link.setAttribute('data-nome',objs[i].nome);
			link.setAttribute('data-id_aplicativo',objs[i].id_aplicativo);
			link.setAttribute('data-usuario_upload',objs[i].usuario_upload);
			link.addEventListener("click", function(e){ e.preventDefault();
		    var targ;
		    if (!e) var e = window.event;
		    if (e.target) targ = e.target;
		    else if (e.srcElement) targ = e.srcElement;
		    if (targ.nodeType == 3) // defeat Safari bug
		        targ = targ.parentNode;
		    liberaArquivo(targ);});
		    link.appendChild(span);
			td_3.appendChild(link);
		}else{
			td_3.appendChild(span);
		}
		td_1.innerHTML = '<span style="display:none;">' + objs[i].dataen + '</span>' + objs[i].data;
		td_2.innerHTML = objs[i].nome;
		tr.appendChild(td_1);
		tr.appendChild(td_2);
		tr.appendChild(td_3);
		document.getElementById("tablecontent").appendChild(tr);
	}

	$(document).ready(function() {
	    $('#datatablecontent').DataTable({
	    	"bFilter": false ,
	    	"order": [[ 0, "desc" ]],
	    	 "language": {
		    	"sEmptyTable": "Nenhum registro encontrado",
			    "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
			    "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
			    "sInfoFiltered": "(Filtrados de _MAX_ registros)",
			    "sInfoPostFix": "",
			    "sInfoThousands": ".",
			    "sLengthMenu": "_MENU_ resultados por página",
			    "sLoadingRecords": "Carregando...",
			    "sProcessing": "Processando...",
			    "sZeroRecords": "Nenhum registro encontrado",
			    "sSearch": "Pesquisar",
			    "oPaginate": {
			        "sNext": "Próximo",
			        "sPrevious": "Anterior",
			        "sFirst": "Primeiro",
			        "sLast": "Último"
		    	},
		   		 "oAria": {
		        "sSortAscending": ": Ordenar colunas de forma ascendente",
		        "sSortDescending": ": Ordenar colunas de forma descendente"
		    	}
		    }
	    });
	} );
});

/**
 * Libera o arquivo para ser adicionado ao relatório
 * @param obj é o link clicado
 * @return retorna atualização do link
 */

function liberaArquivo(obj){
	$('#processo').modal('show');
	var file = $(obj).parent().data('id');
	var fileName = $(obj).parent().data('nome');
	var id_aplicativo = $(obj).parent().data('id_aplicativo');
	var usuario_upload = $(obj).parent().data('usuario_upload');	
	//alert (file + " | " + fileName + " | " + id_aplicativo + " | " + usuario_upload );
	$.post('app/approveFile.php',{id : file, filename :  fileName, userup: usuario_upload, idapp :  id_aplicativo},function(retorno){
		console.log(retorno);
		//if(retorno == 'ok'){
			$(obj).html('Aprovado');
			$(obj).removeClass('label-danger');
			$(obj).addClass('label-success');
			$('#processo').modal('hide');
		// }else{
		// }
	});
}



/**

 * Eventos que ocorrem por padrão ao carregar o documento

 */

$(function(){
	$('input[type=file]').on('change', prepareUpload);
	$('.btn-default').on('click', function(){
		cancelar = true;
	});

	$('.modal').on('hide.bs.modal',function(e){
		tipo = $(this).attr('id');
		$('.nav-sidebar').children('.active').removeClass('active');
		$('.nav-sidebar').children(':first').addClass('active');
		switch(tipo){
			case 'upload':
				if( $('#frmupload').find('.btn-default').html() == 'Fechar'){
					location.reload();
				}
				$('#frmupload').find('.alert').removeClass('alert-danger').removeClass('alert-success').removeClass('alert-info');
				$('#frmupload').find('.alert').addClass('hide');
				$('#frmupload').find('.btn-primary').removeClass('hide');
				$('#frmupload').find('.btn-default').html('Cancelar');
				$('#processupload').removeAttr('disabled');
				$('#txtarquivo').val('');
				$('#appname').val($('#appname option:first').val());
				break;
			case 'relatorio':
				if(!$('#alertrelatorio').hasClass('hide')){
					$('#alertrelatorio').addClass('hide');
				}
				$('#processrelatorio').removeAttr('disabled');
				$('#alertrelatorio').html('Reunindo informações! Isso pode demorar um pouco.');
				$('#appnamerelatorio').val($('#appnamerelatorio option:first').val());
				break;
			default:
		}
	});

	/**
	 * Processa dados do relatorio
	 * @param é o botão que invoca o metodo
	 * @result relatório em pdf
	 */
	$('#frmrelatorio').on('submit',function(e){
		e.preventDefault();
		cancelar = false;
		$('#processrelatorio').attr('disabled','disabled');
		//$('#relatorio').find('.close').attr('disabled','disabled');
		//$('#processacancela').attr('disabled','disabled');

		alerta = $('#alertrelatorio');
		modelo = $('#appnamerelatorio').val();
		$(alerta).removeClass('hide');

		if($(alerta).hasClass('alert-danger')){
			$(alerta).removeClass('alert-danger');
			$(alerta).addClass('alert-info');
		}

		if(cancelar === true){
    		return;
    	}

		$.post('app/geraRelatorio.php',{appname:modelo}).done(function(info){
			var json = JSON.parse(info);
			if(cancelar === true){
	    		return;
	    	}
			//console.log(json.info);
			if(json.status == 'ok'){
				$(alerta).html('Agora falta pouco, gerando PDF.');
				if(cancelar === true){
		    		return;
		    	}

				$.post('app/geraPdf.php').done(function(eco){
					if(cancelar === true){
			    		return;
			    	}
					var resposta = JSON.parse(eco);
					$(alerta).html('Iniciando download do relatório. O arquivo será aberto a serguir.');
					link = document.getElementById('force_download');
					link.click(true);
					$('#relatorio').modal('hide');
				}).fail(function(evo){
					$(alerta).addClass('alert-danger');
					$(alerta).html('Desculpe, houve uma falha. Tente mais tarde.');
					console.log(evo);
				});
			}else{
				$(alerta).removeClass('alert-info');
				$(alerta).addClass('alert-danger');
				$(alerta).html('Desculpe, as informações do relatório estão incompletas. Tente mais tarde.');

			}

		}).fail(function(retorno){
			$(alerta).removeClass('alert-info');
			$(alerta).addClass('alert-danger');
			$(alerta).html('Desculpe, as informações do relatório estão incompletas. Tente mais tarde.');
		});

		$('#relatorio').find('.close').removeAttr('disabled');
	});

	

	/**
	 * Envia o arquivo para upload e grava informacoes no banco
	 * @param  e é o elemento que disparo o evento submit
	 * @return retorna mensagens ao usuario
	 */

	$('#frmupload').on('submit', function(e) {
        e.preventDefault();
        cancelar = false;
        alerta = $('#alertasenha');
        form   = $('#frmupload');
        $('#processupload').attr('disabled','disabled');
        $(alerta).html('Analisando o arquivo...');
        if($(alerta).hasClass('alert-danger'))
        	$(alerta).removeClass('alert-danger');
        if($(alerta).hasClass('alert-info'))
        	$(alerta).removeClass('alert-info');
        $(alerta).addClass('alert-info');
        $(alerta).removeClass('hide');

        ext = $('#txtarquivo').val();
        ext = ext.trim();
        ext = ext.substr(-4);

        if(ext != 'xlsx' && ext != '.xls'){
        	//console.log('depois ' + ext);
        	$(alerta).removeClass('alert-info');
        	$(alerta).addClass('alert-danger');
        	$(alerta).html('São permitidos apenas arquivos xls ou xlsx.');
        	return;
        }

        var info = new FormData();
        $.each($('#txtarquivo')[0].files, function(key, value){
        	info.append(key, value);
        });

        info.append('appname', $('#appname').val());
        var urla = $(form).attr('action');// + '?' + files;
        console.log(urla);
        $.ajax({
        	url: urla,//'app/upload.php?arquivo',
        	type: 'POST',
        	data: info,
        	cache: false,
        	processData: false,
        	contentType: false
        }).done(function(retorno){
        	console.log(retorno);
        	/*var json = JSON.parse(retorno);
        	if(json.status == 'confirmacao'){
        		if(cancelar === true){
	        		return;
	        	}
        		if(confirm("Este arquivo já existe. Deseja sobrescreve-lo?")){
        			if(cancelar === true){
		        		return;
		        	}
        			info.append('force', true);
        			$.ajax({
						url: 'app/upload.php?arquivo',
						type: 'POST',
						data: info,
						cache: false,
						processData: false,
						contentType: false
					}).done(function(resposta){
						var jres = JSON.parse(resposta);
						if(jres.status == 'ok'){
							$(alerta).html('Arquivo substituido! Enviando dados para o Banco de Dados.');
							$.post('app/infoProcess.php',{id : jres.info.id_banco, filename : jres.info.nomeFile, nomeReal: jres.info.nomeReal, data : jres.info.data}, function(resposta){
				        		if(resposta == 'ok'){
				        			$(alerta).removeClass('alert-info');
					        		$(alerta).addClass('alert-success');
					        		$(alerta).html('Arquivo enviado com sucesso! Em até 48h ele estará disponível no relatório.');
					        		$(form).find('.btn-primary').addClass('hide');
					        		$(form).find('.btn-default').html('Fechar');
				        		}else{
				        			$(alerta).removeClass('alert-info');
						        	$(alerta).addClass('alert-danger');
						        	$(alerta).html('Houve um erro ao enviar, tente novamente mais tarde!');
				        		}
				        	});
						}else{
							$(alerta).removeClass('alert-info');
				        	$(alerta).addClass('alert-danger');
				        	$(alerta).html('A verificação falhou, tente novamente mais tarde!');
						}
					}).fail(function(erro){
						$(alerta).removeClass('alert-info');
			        	$(alerta).addClass('alert-danger');
			        	$(alerta).html('Houve um erro ao enviar, tente novamente mais tarde!');
					});
        		}else{
        			if(cancelar === true){
		        		return;
		        	}
        			$(alerta).removeClass('alert-info');
		        	$(alerta).addClass('alert-danger');
		        	$(alerta).html('Envio de arquivo cancelado pelo usuário!');
        		}
        	}else if(json.status == "ok"){
        		if(cancelar === true){
        			$.post('app/deleteFile.php', {id : json.info.id_banco});
	        		return;
	        	}
        		$(alerta).html('Arquivo aprovado! Enviando dados para o Banco de Dados.');
	        	$.post('app/infoProcess.php',{id : json.info.id_banco, filename : json.info.nomeFile, nomeReal: jres.info.nomeReal,  data : json.info.data}, function(resposta){
	        		if(cancelar === true){
	        			$.post('app/deleteFile.php', {id : json.info.id_banco});
		        		return;
		        	}
	        		if(resposta == 'ok'){
	        			$(alerta).removeClass('alert-info');
		        		$(alerta).addClass('alert-success');
		        		$(alerta).html('Arquivo enviado com sucesso! Em até 48h ele estará disponível no relatório.');
		        		$(form).find('.btn-primary').addClass('hide');
		        		$(form).find('.btn-default').html('Fechar');
	        		}else{
	        			$(alerta).removeClass('alert-info');
			        	$(alerta).addClass('alert-danger');
			        	$(alerta).html('Houve um erro ao enviar, tente novamente mais tarde!');
	        		}
	        	});
        	}else{
        		$(alerta).removeClass('alert-info');
	        	$(alerta).addClass('alert-danger');
	        	$(alerta).html('A verificação falhou, tente novamente mais tarde!');
        	}*/
        }).fail(function(retorno){
        	console.log(retorno);
        	$(alerta).removeClass('alert-info');
        	$(alerta).addClass('alert-danger');
        	$(alerta).html('A verificação falhou, tente novamente mais tarde!');
        });
    });
});



/**
 * prepareUpload()
 * Salva as informações do arquivo para uma variavel.
 * @param event - O elemento que foi alterado
 * 
 */
function prepareUpload(event){
	files = event.target.files;
}