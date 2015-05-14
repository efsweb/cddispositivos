$(document).ready(function(){
	/**

	 * Efetua o login no sistema

	 * @param  {usuario, senha} e [usuario cadastrado no sistema, senha cadastrada no sistema]

	 * @return {verdadeiro/falso}   [Direciona para pagina seguinte se dados forem validos]

	 */

	$('#loginform').on('submit', function(e) {
        e.preventDefault();
        $('#btnlogin').attr('disabled', 'disabled');
        $('#lksenha').attr('disabled', 'disabled');
        form   = $(this);
        alerta = $('#alertalogin');
        $(alerta).html('Conectando com o banco...');
        if($(alerta).hasClass('alert-danger'))
        	$(alerta).removeClass('alert-danger');
        if($(alerta).hasClass('alert-info'))
        	$(alerta).removeClass('alert-info');
        $(alerta).addClass('alert-info');
        $(alerta).removeClass('hide');

        $.post($(form).attr('action'), $(form).serialize(), function(retorno){
        	if(retorno == "ok"){
        		$(alerta).removeClass('alert-info');
        		$(alerta).addClass('alert-success');
        		$(alerta).html('Login efetuado com sucesso!');
        		window.location.href="dashboard.php";
        	}else{
        		$(alerta).removeClass('alert-info');
        		$(alerta).addClass('alert-danger');
        		$(alerta).html('Usuário ou senha incorretos!');
        	}
        });
        $('#btnlogin').removeAttr('disabled');
        $('#lksenha').removeAttr('disabled');
    });



	/**

	 * Recuperação de senha

	 * @param  {email} [email cadastrado no sistema]

	 * @return {verdadeiro/falso}   [Se valido envia email com informações de acesso]

	 */

	$('#frmsenha').on('submit', function(e) {

        e.preventDefault();

        

        $(this).find('button').each(function(){

        	$(this).attr('disabled', 'disabled');

        });



        form   = $(this);

        alerta = $('#alertasenha');

        $(alerta).html('Verificando o e-mail e enviando a mensagem.');

        if($(alerta).hasClass('alert-danger'))

        	$(alerta).removeClass('alert-danger');

        if($(alerta).hasClass('alert-info'))

        	$(alerta).removeClass('alert-info');

        $(alerta).addClass('alert-info');

        $(alerta).removeClass('hide');

        $.post($(form).attr('action'), $(form).serialize(), function(retorno){

            console.log(retorno);

        	if(retorno == "ok"){

        		$(alerta).removeClass('alert-info');

        		$(alerta).addClass('alert-success');

        		$(alerta).html('Verifique as informações em seu e-mail!');

                $('#enviarsenha').addClass('hide');

                $('#cancelarsenha').html('Fechar');

        	}else{

        		$(alerta).removeClass('alert-info');

        		$(alerta).addClass('alert-danger');

        		$(alerta).html('No momento não podemos enviar e-mails para o endereço cadastrado!');

        	}

        });

        $(this).find('button').each(function(){

        	$(this).removeAttr('disabled');

        });

    });



	/**

	 * Limpa os campos do formulário de recuperação de senha

	 * @param Modal com o formulário

	 * 

	 */

	$('#modal').on('hidden.bs.modal', function(e){

		$('#email').val('');

		alerta = $('#alertasenha');

		$(alerta).html('');

        $(alerta).addClass('hide');

        $('#enviarsenha').removeClass('hide');

        $('#cancelarsenha').html('Cancelar');

		if($(alerta).hasClass('alert-danger'))

        	$(alerta).removeClass('alert-danger');

        if($(alerta).hasClass('alert-info'))

        	$(alerta).removeClass('alert-info');

        if(!$(alerta).hasClass('hide'))

        	$(alerta).removeClass('hide');

	});

});