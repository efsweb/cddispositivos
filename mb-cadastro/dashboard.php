<?php
session_start();
require_once('google/appengine/api/cloud_storage/CloudStorageTools.php');
use google\appengine\api\cloud_storage\CloudStorageTools;
if(!isset($_SESSION["disp"]["permissao"])){
	echo '<script>window.location.href="/";</script>';
}
$options = ['gs_bucket_name' => 'mb-cadastro-arquivos'];
$upload_url = CloudStorageTools::createUploadUrl('/upload.php', $options);
?>
<!DOCTYPE html>
<html lang="pt-br">
	<head>
		<meta charset="utf-8">
		<title>Dispositivos - Controle</title>
		<link rel="stylesheet" href="css/bootstrap.css">
	    <link rel="stylesheet" href="css/bootstrap-theme.css">
	    <link rel="stylesheet" href="css/jquery.dataTables.css">
	    <link rel="stylesheet" href="css/style.css">

	    <script type="text/javascript" src="js/jquery-1.11.1.js" ></script>
	    <script type="text/javascript" src="js/bootstrap.js"></script>
	    <script type="text/javascript" src="js/jquery.fileDownload.js"></script>
	    <script type="text/javascript" src="js/jquery.dataTables.js"></script>

	    <script type="text/javascript">
	    	var permissao = 0;
	    	<?php if($_SESSION["disp"]["permissao"] == 'yboh'): ?>
	    	permissao = 1;
	    	<?php endif; ?>
	    </script>
	    <script type="text/javascript" src="js/content.js"></script>
	</head>

	<body>
		<div class="navbar navbar-default navbar-fixed-top" role="navigation">
			<div class="container-fluid">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
						<span class="sr-only">Ocultar menu</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="#"><img src="images/logo_yboh.png" height="57"></a>
				</div>
				<div class="navbar-collapse collapse">
					<ul class="nav navbar-nav navbar-right">
						<li><a href="app/logout.php">Sair</a></li>
					</ul>
				</div>
			</div>
		</div>
		<div class="container-fluid">

			<div class="row">

				<div class="col-sm-3 col-md-2 sidebar">

					<ul class="nav nav-sidebar">
						<li class="active"><a href="#">Principal</a></li>
						<li><a href="#" onclick="showModal(this, '#upload');">Upload</a></li>
						<li><a href="#" onclick="showModal(this, '#relatorio');">Relatório</a></li>
						<li><a href="app/logout.php">Sair</a></li>
					</ul>
					<a id="force_download" href="PDF/relatorio.pdf" target="_blank" download="Relatório.pdf" style="visibility:hidden;">asd</a>
				</div>
				<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
					<h2 class="sub-header">Arquivos enviados</h2>
					<div class="table-responsive">
						<table class="table table-striped" id="datatablecontent">
							<thead>
								<tr>
									<th>Recebido em</th>
									<th>Nome do arquivo</th>
									<th>Status</th>
								</tr>
							</thead>
							<tbody id="tablecontent"></tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div id="upload" class="modal fade" data-backdrop="static">
	        <form id="frmupload" class="form-horizontal" role="form" action="<?= $upload_url; ?>" enctype="multipart/form-data" method="post">
	            <div class="modal-dialog">
	                <div class="modal-content">
	                    <div class="modal-header">
	                        <button type="button" class="close" data-dismiss="modal">
	                            <span aria-hidden="true">&times;</span><span class="sr-only">Fechar</span>
	                        </button>
	                        <h4 class="modal-title">Upload de arquivo</h4>
	                    </div>
	                    <div class="modal-body">
	                        <p>O formato do arquivo deve ser xls. Caso não tenha o arquivo padrão pode baixa-lo clicando <a download href="modelos/Modelo_Lista_de_Cadastro.xlsx">aqui</a>.</p>
	                        <p><b>Orientações</b></p>
	                        <ol>
	                        	<li>
	                        		<b>No Modelo</b>
	                        		<ol>
	                        	<li>Não alterar a primeira linha, onde contém os nomes das colunas;</li>
	                        	<li>Não alterar a quantidade de colunas e o descritivo das colunas na linha <b>1</b>;</li>
	                        	<li>A coluna K deverá conter apenas dois tipos de informação: Android <b>OU</b> IOS;</li>
	                        	<li>A coluna M deverá conter apenas dois tipos de informação: Em Aprovação <b>OU</b> Em Cancelamento;</li>
	                        </ol></li>
	                        	<li>Selecione o app que se refere ao arquivo;</li>
	                        	<li>Selecione o arquivo em seu computador e clique em upload;</li>
	                        </ol>
	                        <div class="input-group">
	                        	<span class="input-group-addon"><i class="glyphicon glyphicon-phone"></i></span>
	                            <select id="appname" name="appname" class="form-control" required>
									<option value="">Selecione um app</option>
									<option value="businfomb">BusInfo MB</option>
									<option value="sprinterinfomb">SprinterInfo MB</option>
								</select>
	                        </div>
	                        <div class="clear"></div><br />
	                        <div class="input-group">
	                        	<span class="input-group-addon"><i class="glyphicon glyphicon-file"></i></span>
	                            <input name="arquivo" id="txtarquivo" type="file" class="form-control" required />
	                        </div>
	                        <div class="clear"></div><br />
	                        <div id="alertasenha" class="alert hide" role="alert"></div>
	                    </div>
	                    <div class="modal-footer">
	                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
	                        <button type="submit" class="btn btn-primary" id="processupload">Enviar</button>
	                    </div>
	                </div>
	            </div>
	        </form>
	    </div>


	    <div id="relatorio" class="modal fade" data-backdrop="static">

	    	<form id="frmrelatorio" method="post" action="app/geraRelatorio.php">

	            <div class="modal-dialog">

	                <div class="modal-content">

	                    <div class="modal-header">

	                        <button type="button" class="close" data-dismiss="modal">

	                            <span aria-hidden="true">&times;</span><span class="sr-only">Fechar</span>

	                        </button>

	                        <h4 class="modal-title">Gerar Relatório</h4>

	                    </div>

	                    <div class="modal-body">

	                    	<ol>

	                    		<li>

	                    			Apenas os arquivos com status <span class="label label-success">Aprovado</span> serão considerados no Relatório.

	                    		</li>

	                    		<li>

	                    			O tempo de geração do relatório dependerá da velocidade da sua conexão.

	                    		</li>

	                    		<li>

	                    			Selecione o Aplicativo abaixo e clique em <span class="label label-primary">Gerar Relatório</label>

	                    		</li>

	                    	</ol><br />

	                    	<div class="input-group">

	                        	<span class="input-group-addon"><i class="glyphicon glyphicon-phone"></i></span>

	                            <select id="appnamerelatorio" class="form-control" required>

									<option value="">Selecione um app</option>

									<option value="businfomb">BusInfo MB</option>

									<option value="sprinterinfomb">SprinterInfo MB</option>

								</select>

	                        </div>

	                        <br />

	                        <div id="alertrelatorio" class="alert alert-info hide" role="alert">Reunindo informações! Isso pode demorar um pouco.</div>

	                    </div>

	                    <div class="modal-footer">

	                        <button type="button" class="btn btn-default" id="processacancela" data-dismiss="modal">Cancelar</button>

	                        <button type="submit" class="btn btn-primary" id="processrelatorio">Gerar relatório</button>

	                    </div>

	                </div>

	            </div>

	        </form>

	    </div>





	    <div id="processo" class="modal fade" data-backdrop="static">

            <div class="modal-dialog">

                <div class="modal-content">

                    <div class="modal-header">

                        <button type="button" class="close hide" data-dismiss="modal">

                            <span aria-hidden="true">&times;</span><span class="sr-only">Fechar</span>

                        </button>

                        <h4 class="modal-title">Liberando o arquivo</h4>

                    </div>

                    <div class="modal-body">

                        <div id="alertasenha" class="alert alert-info" role="alert">Estamos atualizando o arquivo! Por favor aguarde um momento.</div>

                    </div>

                    <div class="modal-footer">

                        <button type="button" class="btn btn-default hide" data-dismiss="modal">Cancelar</button>

                        <button type="submit" class="btn btn-primary hide">Gerar relatório</button>

                    </div>

                </div>

            </div>

	    </div>





	</body>	

</html>