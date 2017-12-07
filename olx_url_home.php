<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html>
<head>
<title>URL Shortener - go.olxbr.com</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
<style>
.new_callout
{
	padding: 1.25rem;
	margin-top: 1.25rem;
	margin-bottom: 1.25rem;
	border: 1px solid #eee;
	border-left-width: .25rem;
	border-radius: .25rem;	
}
</style>
</head>

<body>
<div class="container-fluid" style="background-color:#efefef;border-bottom: 1px solid #dddddd;">
	<div class="container">
		<div class="row">
			<div class="col-sm-2">
				<img src="http://static.olx.com.br/img/olx_logo_96.png" align="left"> 
			</div>
			<div class="col-sm-10" style="text-align:left;padding-top:18px;letter-spacing:-1px;">
				<h1>URL Shortener - go.olxbr.com</h1>
			</div>
		</div>
		<div class="row" style="font-size:18px;padding-bottom:20px;">
			Bem-vindo(a) ao sistema de shortlinks da OLX Brasil.  
			<br><strong>Estamos ainda em fase de testes!</strong>  Qualquer feedback, mande email/hangout/Slack para o PK.
		</div>
	</div>
</div>

<div class="container" style="padding-top:20px;">
	<div class="row">


<?PHP
$CURLOPT_USERPWD = 'ENTER HERE';
$url = 'http://';
if ($_POST["acao"] == "1" )
{
	$custom_hash = urlencode($_POST["shortlink"]);
	$long_url = $_POST["url"];

	$deletable = '';
	if ($_POST["deletable"] == '1')
		$deletable = 'undeletable';
	
	$pk = '{"urls": [	{ "custom_hash": "'.$custom_hash.'", "long_url": "'.$long_url.'", "note": "'.$deletable.'" } ] }';
		
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	curl_setopt($ch, CURLOPT_USERPWD, $CURLOPT_USERPWD);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json') );
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_URL, 'http://tinyccpro.com/tiny/api/3/urls/');
	curl_setopt($ch, CURLOPT_POSTFIELDS, $pk);
 
	$result = curl_exec($ch);
	curl_close($ch);
	$resp = json_decode($result, true);

/*
	var_dump($resp);
	echo "<hr>";
	var_dump($pk);
	echo "<hr>";
	var_dump($result);
	echo "<hr>";
*/	

	if ($resp['error']['code'] == '1303')
	{
?>
		<div class="alert alert-danger" role="alert">
			Eita ... problemas t&eacute;cnicos acontecendo.  Estouramos nosso limite de chamadas de API, o seu shortlink n&atilde;o foi criado.
			<br><br>Voc&ecirc; ter&aacute; que criar seu link amanh&atilde;.
		</div>  
		
<?PHP
	}
	else if ($resp['urls'][0]['error']['code'] == '1215')
	{
		$shortlink = $custom_hash;
		$url = $long_url;
?>	
		<form method="post" name="form1" id="form2" action="/olx_url">
			<input type="hidden" name="acao" id="acao" value="2">
			<input type="hidden" name="shortlink_del" id="shortlink_del" value="<?=$custom_hash?>">
			
			<div class="alert alert-danger" role="alert">
				A URL <a href="http://go.olx.com/<?=$custom_hash?>" target="_blank">http://go.olx.com/<strong><?=$custom_hash?></strong></a> j&aacute; existe!  
<?PHP
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_USERPWD, $CURLOPT_USERPWD);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json') );
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, 'http://tinyccpro.com/tiny/api/3/urls/'.$custom_hash);
	 	$result = curl_exec($ch);
		curl_close($ch);
		$resp = json_decode($result, true);
	
		if ($resp['urls'][0]['note'] != 'undeletable')
		{
?>
				&nbsp;&nbsp;&nbsp; <button type="submit" class="btn btn-danger">DELETAR</button>
<?PHP  }  ?>
			</div>
		</form>
<?PHP  
	} else
	{
?>	
		<div class="alert alert-success" role="alert">
			A URL nova foi criada: <a href="http://go.olxbr.com/<?=$custom_hash?>" target="_blank">http://go.olxbr.com/<strong><?=$custom_hash?></strong></a>
		</div>
	
<?PHP  }  ?>


<?PHP  } else if ($_POST["acao"] == "2" ) { 
	$custom_hash = urlencode($_POST["shortlink_del"]);

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	curl_setopt($ch, CURLOPT_USERPWD, $CURLOPT_USERPWD);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json') );
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_URL, 'http://tinyccpro.com/tiny/api/3/urls/'.$custom_hash);
 	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE"); 
	$result = curl_exec($ch);
	curl_close($ch);
	
	$shortlink = $custom_hash;
?>
	
		<div class="alert alert-success" role="alert">
			A URL <a href="http://go.olxbr.com/<?=$custom_hash?>" target="_blank">http://go.olxbr.com/<strong><?=$custom_hash?></strong></a> foi deletada
		</div>
<?PHP  }  ?>


	</div>
	<div class="row new_callout">
		<h2>Criar nova URL:</h2> &nbsp;&nbsp;&nbsp; <span style="padding-top:10px;font-size:15px;background-color:#efefef;border-radius:4px;;color:#808080;">&nbsp;&nbsp;&nbsp;http://go.olxbr.com/<span id="newid" style="font-weight:bold;"><?=$shortlink?></span>&nbsp;&nbsp;&nbsp;</span>
	</div>
	<div class="row">
		<form method="post" name="form1" id="form1" action="/olx_url">
			<input type="hidden" name="acao" id="acao" value="1">
			
			<div class="form-group">
				<label for="shortlink">Shortlink:</label>
				<input type="text" class="form-control" name="shortlink" id="shortlink" style="width:600px;" value="<?=$shortlink?>">
				<small id="shortlinkHelp" class="form-text text-muted">Apenas o valor despois do <code>http://go.olxbr.com/</code></small>
			</div>			

			<div class="form-group">
				<label for="url">URL:</label>
				<input type="text" class="form-control" name="url" id="url" style="width:600px;" value="<?=$url?>">
				<small id="urlHelp" class="form-text text-muted">A URL completa onde o shortlink ser&aacute; direcionado.</small>
			</div>			

			<div class="form-check">
				<label class="form-check-label">
				  <input type="checkbox" class="form-check-input" name="deletable" id="deletable" value="1">
				  Link sem possibilidade de dele&ccedil;&atilde;o (<em>ser&aacute; necess&aacute;rio abrir ticket</em>)
				</label>
			</div>			
			
			<button type="submit" class="btn btn-primary">CRIAR</button>
		</form>
	</div>

</div>


<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>


<script type="text/javascript">

$('#form1').submit(function () {
    var shortlink = $.trim($('#shortlink').val());
    if (shortlink  === '') {
        alert('Por favor insira um shortlink.');
        $('#shortlink').focus();
        return false;
    }
    
    var url = $.trim($('#url').val());

    if (/^(http|https|ftp):\/\/[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/i.test($("#url").val()))
    {
	    
    } else 
    {    
        alert('Por favor insira uma URL valida.');
        $('#url').focus();
        return false;
	}    
    
});

$(document).ready(function () {
	$('#shortlink').on('input',function(e)
   	{ 
       	$('#newid').html($('#shortlink').val()); 
  	});
});



</script>	


</body>
</html>