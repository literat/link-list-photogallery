<?php

/**
 * Simple Link Gallery
 *
 * @author  Tomas Litera  <tomaslitera@hotmail.com>
 */

namespace VodniLinkGallery;
use slg\LinkGallery;

/**
 * This is directory definitions
 * - do not forget to add slash at the end
 */

/* System Directories */
define('ROOT_DIR', __DIR__ . '/../');
define('HTTP_DIR', 'http://'.$_SERVER['HTTP_HOST'].'/');
define('IMG_DIR',	HTTP_DIR.'images/');
define('CSS_DIR',	HTTP_DIR.'css/');
define('JS_DIR',	HTTP_DIR.'remote/jslib/');
/**/

require_once(ROOT_DIR . 'config.php');
require_once(__DIR__ . '/link-gallery.class.php');
require_once(__DIR__ . '/auth-vodni.func.php');

define('SESSION_PREFIX', md5($server.$database.$user.'sunlight')."-");

$result_message = '';

$messages = array(
	'success-insert' 	=> 'Galerie byla úspěšně uložena!',
	'danger-insert' 	=> 'Galerii se nepodařilo uložit!',
	'success-hide'		=> 'Galerie byla úspěšně schována!',
	'danger-hide' 		=> 'Galerii se nepodařilo schovat!',
	'success-delete'		=> 'Galerie byla úspěšně smazána!',
	'danger-delete' 	=> 'Galerii se nepodařilo smazat!',
);

$options = array(
	'server' 		=> $server,
	'database' 		=> $database,
	'user' 			=> $user,
	'password' 		=> $password,
	'auth' 			=> 'sessionUserAuth',
	'mail-from' 	=> 'fotogalerie@vodni.skauting.cz',
	'mail-admin' 	=> 'hvezdar@skaut.cz',
	'messages' 		=> $messages,
);

// starting the session
session_name(SESSION_PREFIX.'session');
session_start();

$LinkGallery = LinkGallery::getInstance();
$LinkGallery->init($options);

if(isset($_POST['save']) && $_POST['save'] == 'save') {
	$values = array(
		'year' 			=> $_POST['year'],
		'link' 			=> $_POST['link'],
		'name' 			=> $_POST['name'],
		'author' 		=> $_POST['author'],
		'email' 		=> $_POST['email'],
		'num'			=> $_POST['num'],
		'publication' 	=> '1'
	);

	$result = $LinkGallery->insert($values);
	$result_message = $LinkGallery->getMessage('insert', $result);
}

if(
	isset($_GET['act']) &&
	intval($_GET['id']) &&
	(
		sessionUserAuth($LinkGallery->getConnection(), 20) ||
		sessionUserAuth($LinkGallery->getConnection(), 2)
	)
) {
	$id = $_GET['id'];
	switch ($_GET['act']) {
		case 'hide':
			$result = $LinkGallery->hide($id);
			$result_message = $LinkGallery->getMessage('hide', $result);
			break;
		case 'delete':
			$result = $LinkGallery->delete($id);
			$result_message = $LinkGallery->getMessage('delete', $result);
			break;
		default:
			break;
	}
}

include_once('vodni_header.inc.php');
?>

<div id="content">
	<div id="content-pad">
		<div class="photogalleries">
			<h1>Fotogalerie</h1>
			<?php echo $result_message; ?>
			<p><a href="" onClick="$('#form').show();return false;">Přidat fotogalerii</a> (formulář pro přidání odkazu)</p>
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
			<?php $LinkGallery->render(); ?>
			<div class="feedback">
				Je nějaká galerie nefunkční? Chcete nahlásit chybu? Kontaktujte <a href="mailto:<?php echo $options['mail-admin']?>" title="E-mail na Adminitrátora">Administrátora</a>!
			</div>
		</div>
	</div>
</div>
<div class="cleaner"></div>
<?php

include_once('vodni_footer.inc.php');