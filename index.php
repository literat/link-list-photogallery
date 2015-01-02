<?php

/**
DROP TABLE IF EXISTS `photogalleries`;
CREATE TABLE IF NOT EXISTS `photogalleries` (
`id` smallint(6) NOT NULL,
  `year` year(4) NOT NULL,
  `link` varchar(120) COLLATE utf8_czech_ci NOT NULL,
  `name` varchar(60) COLLATE utf8_czech_ci NOT NULL,
  `author` varchar(120) COLLATE utf8_czech_ci NOT NULL,
  `email` varchar(60) COLLATE utf8_czech_ci NOT NULL,
  `publication` enum('0','1') COLLATE utf8_czech_ci NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Klíče pro tabulku `photogalleries`
--
ALTER TABLE `photogalleries`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `id` (`id`);

--
-- AUTO_INCREMENT pro tabulky
--

--
-- AUTO_INCREMENT pro tabulku `photogalleries`
--
ALTER TABLE `photogalleries`
MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
 */

/**
 * This is directory definitions
 *
 * * do not forget to add slash at the end
 */

/* System Directories */
define('ROOT_DIR', __DIR__ . '/../');
define('HTTP_DIR', 'http://'.$_SERVER['HTTP_HOST'].'/');
define('IMG_DIR',	HTTP_DIR.'images/');
define('CSS_DIR',	HTTP_DIR.'css/');
define('JS_DIR',	HTTP_DIR.'remote/jslib/');

require_once(ROOT_DIR . 'config.php');

define('SESSION_PREFIX', md5($server.$database.$user.'sunlight')."-");

function savePhotogallery($connection) {
	$sql = 'INSERT INTO photogalleries (year, link, name, author, email, num, publication) VALUES (:year, :link, :name, :author, :email, :num, :publication)';

	$values = array(
		':year' 		=> $_POST['year'],
		':link' 		=> $_POST['link'],
		':name' 		=> $_POST['name'],
		':author' 		=> $_POST['author'],
		':email' 		=> $_POST['email'],
		':num'			=> $_POST['num'],
		':publication' 	=> '1'
	);

	$query = $connection->prepare($sql);
	$result = $query->execute($values);

	return $result;
}

function groupByYear($rows) {
	$by_year = array();
	foreach ($rows as $row) {
		$year = $row['year'];
		if (isset($by_year[$year])) {
			$by_year[$year][] = $row;
		} else {
			$by_year[$year] = array($row);
		}
	}

	return $by_year;
}

function renderPhotogalleries($db, $data) {
	$buffer = '';

	foreach ($data as $key_year => $key_value) {
		$buffer .= '<strong>'.htmlspecialchars($key_year)."</strong><br />\n";
		foreach ($key_value as $row) {
			$unpublished = '';
			if($row['publication'] == 0) {$unpublished = 'class="unpublished"';}
			$buffer .= '<a '.$unpublished.' href="'.$row['link'].'" title="'.htmlspecialchars($row['name']).'" target="_blank">'.htmlspecialchars($row['name']).'</a>';
			$buffer .= ' (autor '.htmlspecialchars($row['author']).') ';
			if(sessionUserAuth($db, 20) || sessionUserAuth($db, 2)) {
				$buffer .= '<span class="handler">';
				$buffer .= '<a href="?act=hide&id='.$row['id'].'">Schovat</a> ';
				$buffer .= '<a href="?act=delete&id='.$row['id'].'">Smazat</a>';
				$buffer .= '</span>';
			}
			$buffer .= '<br />';
		}
	}

	echo $buffer;
}

function sessionUserAuth($db, $userId) {
	// check session time and time of its inactivity
	if(!isset($_SESSION[SESSION_PREFIX.'user']) || !isset($_SESSION[SESSION_PREFIX.'password'])) {
		$_SESSION['user']["logged"] = false;
		session_unset();
	} else {
		$_SESSION['user']["logged"] = true;
	}

	if(isset($_SESSION['user']['logged']) && ($_SESSION['user']['logged'] == true)) {
		// do not trust the session from other system
		// authentication of system data
		$sql = "SELECT * FROM `sunlight-users` WHERE id = '".$_SESSION[SESSION_PREFIX.'user']."'";
		$db_user = $db->prepare($sql);
		$db_user->execute();
		if($db_user->rowCount()) {
			$user = $db_user->fetch(PDO::FETCH_ASSOC);
			if($_SESSION[SESSION_PREFIX.'password'] != $user['password']) {
				header("Location: ".HTTP_DIR."admin/");
				die('Bad password!');
			} else {
				// regenerate time count
				$_SESSION['user']['access_time'] = time();
			}

			if($_SESSION[SESSION_PREFIX.'user'] == $userId) {
				return TRUE;
			} else {
				return FALSE;
			}
		}
		$db_user->closeCursor();
	} else {
		session_unset();
	}

	return FALSE;
}

//nastartovani session
session_name(SESSION_PREFIX.'session');
//session_save_path(SESSION_DIR);
session_start();

$db_dns = 'mysql:host='.$server.';dbname='.$database;
$db_options = array(
	PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
);

$db = new PDO($db_dns, $user, $password, $db_options);

$result_info = '';
if(isset($_POST['save']) && $_POST['save'] == 'save') {
	$result = savePhotogallery($db);

	if($result) {
		$subject = 'Galerie byla přidána';
		$message = 'Vaše galerie '.$_POST['link'].' byla přidána.';
		$headers = 'From: fotogalerie@vodni.skauting.cz' . "\r\n" .
    				'Reply-To: hvezdar@skaut.cz' . "\r\n";

		mail($_POST['email'].', hvezdar@skaut.cz', $subject, $message, $headers);
		$result_info = '<span class="alert alert-success"><strong>OK!</strong> Galerie byla úspěšně uložena!</span>';
	} else {
		$result_info = '<span class="alert alert-danger"><strong>Sakra!</strong> Galerii se nepodařilo uložit!</span>';
	}
}

if(isset($_GET['act']) && intval($_GET['id']) && (sessionUserAuth($db, 20) || sessionUserAuth($db, 2))) {
	$id = $_GET['id'];
	switch ($_GET['act']) {
		case 'hide':
			$sql = 'UPDATE photogalleries SET publication = "0" WHERE id = "'.$id.'" LIMIT 1';
			$result = $db->query($sql);
			if($result) {
				$result_info = '<span class="alert alert-success"><strong>OK!</strong> Galerie byla úspěšně schována!</span>';
			} else {
				$result_info = '<span class="alert alert-danger"><strong>Sakra!</strong> Galerii se nepodařilo schovat!</span>';
			}
			break;
		case 'delete':
			$sql = 'DELETE FROM photogalleries WHERE id = "'.$id.'" LIMIT 1';
			$result = $db->query($sql);
			if($result) {
				$result_info = '<span class="alert alert-success"><strong>OK!</strong> Galerie byla úspěšně smazána!</span>';
			} else {
				$result_info = '<span class="alert alert-danger"><strong>Sakra!</strong> Galerii se nepodařilo smazat!</span>';
			}
			break;
		default:
			break;
	}
}

if(sessionUserAuth($db, 2) || sessionUserAuth($db, 20)) {
	$sql = 'SELECT * FROM photogalleries ORDER BY year DESC';
} else {
	$sql = 'SELECT * FROM photogalleries WHERE publication = "1" ORDER BY year DESC';
}
$result = $db->query($sql);
$rows = $result->fetchAll(PDO::FETCH_ASSOC);

include_once('vodni_header.inc.php');
?>

<div id="content">
	<div id="content-pad">
		<div class="photogalleries">
			<h1>Fotogalerie</h1>
			<?php echo $result_info; ?>
			<p><a href="" onClick="$('#form').show();return false;">Přidat fotogalerii</a> (formulář pro přidání odkazu)</p>
			<script src='<?php echo JS_DIR; ?>validation/messages_cs.js' type='text/javascript'></script>
			<script src='<?php echo JS_DIR; ?>validation/methods_de.js' type='text/javascript'></script> 
			<script type="text/javascript">
				$.validator.addMethod("num", function(value, element) {
					if(value.match(/^[1-9]{1}[0-9a-zA-Z]{2}\.[0-9a-zA-Z]{1}[0-9a-zA-Z]{1}$/)) return true;
					else if(value.match(/^$/)) return true;
					else return false;
				}, "Hodnota musí být ve formátu nnn.nn!");
				$(document).ready(function(){
					$("#form").validate({
						submitHandler: function(form) {
							form.submit();
						},
						rules: {
							year: {
								required: true,
								number: true,
								minlength: 4,
								maxlength: 4
							},
							link: {
								required: true,
								url: true,
								maxlength: 120
							},
							name: {
								required: true,
								maxlength: 120
							},
							author: {
								required: true,
								maxlength: 120
							},
							email: {
								required: true,
								email: true,
							},
							num: {
								num: true,
								maxlength: 6,
							},
						},
						messages: {
							year: 	"Rok musí být vyplněn (rrrr)!",
							link: 	"Odkaz musí být vyplněn (max 120 znaků)!",
							name: 	"Název musí být vyplněn (max 120 znaků)!",
							author: "Autor musí být vyplněn (max 120 znaků)!",
							email: 	"E-mail musí být vyplněn, zadejte ho ve správném formátu!",
						}
					});
				});
			</script>
			<form id="form" method="post" action="index.php">
				<fieldset>
					<legend>Přidat fotogalerii</legend>
					<div>Autoři souhlasící s využitím svých fotografií pro propagaci vodního skautingu mají za odkazem v závorce své jméno. Prosíme o jeho důsledné uvádění.</div>
					<label>Rok: <input type="text" name="year" value="<?php echo date('Y'); ?>" /></label>
					<label>Odkaz na galerii: <input type="text" name="link" value="http://" /></label>
					<label>Název galerie: <input type="text" name="name" value="" /></label>
					<label>Autor: <input type="text" name="author" value="" /></label>
					<label>E-mail: <input type="text" name="email" value="@" /></label>
					<label>Číslo přístavu: <input type="text" name="num" value="" /></label>
					<input type="submit" name="button" value="Uložit">
				</fieldset>
				<input type="hidden" name="save" value="save" />
			</form>
			<?php renderPhotogalleries($db, groupByYear($rows)); ?>
			<div class="feedback">
				Je nějaká galerie nefunkční? Chcete nahlásit chybu? Kontaktujte <a href="mailto:hvezdar@skaut.cz" title="E-mail na Hvězdáře">Hvězdáře</a>!
			</div>
		</div>
	</div>
</div>
<div class="cleaner"></div>
<?php

include_once('vodni_footer.inc.php');