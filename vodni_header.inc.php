<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta http-equiv="Content-Style-Type" content="text/css" />
	<meta http-equiv="Content-Script-Type" content="text/javascript" />
	<meta name="keywords" content="HKVS, vodní skauti, seascout" />
	<meta name="description" content="Hlavní kapitanát vodních skautů" />
	<meta name="author" content="HKVS team" />
	<meta name="generator" content="SunLight CMS 7.5.1 STABLE0" />
	<meta name="robots" content="index, follow" />
	<link href="<?php echo HTTP_DIR; ?>plugins/templates/hkvs2/style/system.css?1" type="text/css" rel="stylesheet" />
	<link href="<?php echo HTTP_DIR; ?>plugins/templates/hkvs2/style/layout.css?1" type="text/css" rel="stylesheet" />
	<script type="text/javascript">/* <![CDATA[ */var sl_indexroot='./';/* ]]> */</script>
	<script type="text/javascript" src="<?php echo HTTP_DIR; ?>remote/jscript.php?1&amp;default"></script>
	
	<link rel="stylesheet" href="<?php echo HTTP_DIR; ?>remote/lightbox/style.css?1" type="text/css" media="screen" />
	<script type="text/javascript" src="<?php echo HTTP_DIR; ?>remote/lightbox/script.js?1"></script>
	
	<script type="text/javascript" src="<?php echo JS_DIR; ?>jquery.validate.min.js"></script>

	<link rel="alternate" type="application/rss+xml" href="<?php echo HTTP_DIR; ?>remote/rss.php?tp=4&amp;id=-1" title="Nejnovější články" />
	<link rel="shortcut icon" href="<?php echo HTTP_DIR; ?>favicon.ico?1" />
	<title>Fotogalerie</title>

	<style>
		label {display: block;}
		.photogalleries #form {display: none;}
		.photogalleries strong {font-size: 1.2em;margin-top: 15px;display: inline-block;}
		.photogalleries a {display: inline-block; margin: 2px 0px;}
		.photogalleries label {font-weight: bold;padding: 10px 0px;}
		.photogalleries input,
		.photogalleries select {margin-right: 350px;float: right;}
		.photogalleries input.error {padding:3px;border-radius:0px;}
		.photogalleries .alert,
		.photogalleries .error {padding:10px;border-radius:4px;display:inline-block;background-color:#F2DEDE;border:2px solid #EBCCD1;}
		.photogalleries .alert strong {margin: 0px;}
		.photogalleries .alert-danger {color:#A94442;background-color:#F2DEDE;border-color:#EBCCD1;}
		.photogalleries .alert-success {color:#3C763D;background-color:#DFF0D8;border-color:#D6E9C6;}
		.photogalleries label.error {display:inline-block;margin-left:123px;margin-top:5px;padding:5px;color:#A94442;}
		.photogalleries .feedback {font-weight:bold;margin-top:20px;}
		#footer {
			background: url('<?php echo HTTP_DIR; ?>plugins/templates/hkvs2/images/outer-bottom-program.png') no-repeat scroll left top transparent;
		}
	</style>
</head>

<body>

<!-- outer -->
<div id="outer">

	<!-- page -->
	<div id="page">

	<!-- head -->
	<div id="head">
	<a href="<?php echo HTTP_DIR; ?>" title="HKVS - Hlavní kapitanát vodních skautů"><span>HKVS</span></a>
	</div>
	
	<!-- menu -->
	<div id="menu">
		<ul class='menu'>
			<li class="act menu-item-100 first"><a href='<?php echo HTTP_DIR; ?>'>Novinky</a></li>
			<li class="menu-item-7"><a href='<?php echo HTTP_DIR; ?>najdi-oddil-vs'>Najdi oddíl VS</a></li>
			<li class="menu-item-21"><a href='<?php echo HTTP_DIR; ?>o-vodnim-skautingu'>O vodním skautingu</a></li>
			<li class="menu-item-6"><a href='<?php echo HTTP_DIR; ?>vs-v-obrazech'>VS v obrazech</a></li>
			<li class="menu-item-44 last"><a href='<?php echo HTTP_DIR; ?>srazy-vs'>Srazy VS</a></li>
		</ul>
	</div>

	<hr class="hidden" />

	<!-- column -->
	<div id="column">

		<h3 class="box-title">Vyhledávání</h3>
		<form action="index.php" method="get" class="searchform">
		<input name="m" value="search" type="hidden">
		<input name="root" value="1" type="hidden">
		<input name="art" value="1" type="hidden">
		<input name="post" value="1" type="hidden">
		<input name="_security_token" value="b28b6066219f3d379f64f7022cf781be" type="hidden">
		<input name="q" class="q" type="text"> <input value="Vyhledat" type="submit">
		</form>

		<h3 class="box-title">Menu</h3>
		<ul class="menu">
			<li class="menu-dropdown menu-item-z-kapitanskeho-mustku first">
				<a href="z-kapitanskeho-mustku" class="menu-dropdown-link">Z kapitánského můstku</a>
				<ul class="menu-dropdown-list">
					<li class="menu-item-hkvs-hlasi first"><a href="hkvs-hlasi">HKVS hlásí</a></li>
					<li class="menu-item-slozeni-hkvs"><a href="slozeni-hkvs">Složení HKVS</a></li>
					<li class="menu-item-zapisy"><a href="zapisy">Zápisy</a></li>
					<li class="menu-item-spisovna"><a href="spisovna">Řády, vyhlášky, předpisy</a></li>
					<li class="menu-item-vodacke-desetikoruny last"><a href="vodacke-desetikoruny">Vodácké desetikoruny</a></li>
				</ul>
			</li>
			<li class="menu-dropdown menu-item-metodika"><a href="metodika" class="menu-dropdown-link">Metodika</a>
				<ul class="menu-dropdown-list">
					<li class="menu-item-metodicke-materialy first"><a href="metodicke-materialy">Metodické materiály</a></li>
					<li class="menu-item-zabicky-a-vlcata"><a href="zabicky-a-vlcata">Žabičky a vlčata</a></li>
					<li class="menu-item-skautky-a-skauti last"><a href="skautky-a-skauti">Skautky a skauti</a></li>
				</ul>
			</li>
			<li class="menu-dropdown menu-item-vzdelavani"><a href="vzdelavani" class="menu-dropdown-link">Vzdělávání</a>
				<ul class="menu-dropdown-list">
					<li class="menu-item-vodacke-kvalifikace first"><a href="vodacke-kvalifikace">Vodácké kvalifikace</a></li>
					<li class="menu-item-pro-poradatele-zkousek"><a href="pro-poradatele-zkousek">Pro pořadatele zkoušek</a></li>
					<li class="menu-item-lektori-a-instruktori-vs"><a href="lektori-a-instruktori-vs">Lektoři a instruktoři VS</a></li>
					<li class="menu-item-materialy-ke-studiu last"><a href="materialy-ke-studiu">Materiály ke studiu</a></li>
				</ul>
			</li>
			<li class="menu-dropdown menu-item-akce"><a href="akce" class="menu-dropdown-link">Akce</a>
				<ul class="menu-dropdown-list">
					<li class="menu-item-terminka first"><a href="terminka">Termínka</a></li>
					<li class="menu-item-sraz-vs-usk"><a href="sraz-vs-usk">Sraz VS - ÚSK</a></li>
					<li class="menu-item-pres-tri-jezy"><a href="pres-tri-jezy">Přes tři jezy</a></li>
					<li class="menu-item-skare"><a href="skare" target="_blank">SKARE</a></li>
					<li class="menu-item-navigamus"><a href="navigamus">Navigamus</a></li>
					<li class="menu-item-namorni-akademie-2"><a href="namorni-akademie-2">Námořní akademie</a></li>
					<li class="menu-item-lesni-skola-vodnich-skautu last"><a href="lesni-skola-vodnich-skautu">Vodácká lesní škola</a></li>
				</ul>
			</li>
			<li class="menu-dropdown menu-item-casopisy"><a href="casopisy" class="menu-dropdown-link">Časopisy</a>
				<ul class="menu-dropdown-list">
					<li class="menu-item-kapitanska-posta first"><a href="kapitanska-posta">Kapitánská pošta</a></li>
					<li class="menu-item-modkre-stranky"><a href="modkre-stranky">Mod/kré stránky</a></li>
					<li class="menu-item-euronaut last"><a href="euronaut">Euronaut</a></li>
				</ul>
			</li>
			<li class="menu-item-lod-p550"><a href="lod-p550">Loď P550</a></li>
			<li class="menu-item-kontakty last"><a href="kontakty">Kontakty</a></li>
		</ul>

		<h3 class="box-title">Kapitánská pošta</h3>
		  Chceš dostávat elektronickou KP na svůj email? Napiš ho do okénka ke zvolenému formátu a začne ti chodit z adresy kp()skaut.cz (od příštího čísla).  <br><br>
		<form action="upload/kp/kp-odberatele/kp-odberatele.php" method="post">
		  PDF: <input size="15" name="email" type="text">
		  <input value="Odběr v PDF " type="submit">
		</form><!------br />
		 <form action="https://groups.google.com/a/hkvs.cz/group/odber-kp-epub/boxsubscribe">
			<input type=hidden name="hl" value="cs">
			EPUB: <input type=text size=15 name=email>
			<input type=submit name="sub" value="Odběr v EPUB">
		</form><br />
		<form action="https://groups.google.com/a/hkvs.cz/group/odber-kp-mobi/boxsubscribe">
			<input type=hidden name="hl" value="cs">
			MOBI: <input type=text size=15 name=email>
			<input type=submit name="sub" value="Odběr v MOBI">
		</form------>

		<h3 class="box-title">Uživatel</h3>
		<ul>
		  <li>
			<a class="usermenu-item-settings" href="/admin" title="administrace">administrace</a>
		  </li>
		</ul>
		<ul>
			<li><a href="./index.php?m=login&amp;login_form_return=%2F" class="usermenu-item-login">přihlásit</a></li>
			<li><a href="./index.php?m=reg" class="usermenu-item-reg">registrace</a></li>
			<li><a href="./index.php?m=ulist" class="usermenu-item-ulist">uživatelé</a></li>
		</ul>

	</div>
	<!--div id="column"-->
	<!--/div-->
	
	<hr class="hidden" />
