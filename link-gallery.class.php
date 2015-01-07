<?php

/**
 * Simple Link Gallery
 *
 * @author  Tomas Litera  <tomaslitera@hotmail.com>
 */

namespace slg;
use PDO;

/*****************************************************************************
DROP TABLE IF EXISTS `photogalleries`;
CREATE TABLE IF NOT EXISTS `photogalleries` (
`id` smallint(6) NOT NULL,
  `year` year(4) NOT NULL,
  `link` varchar(120) COLLATE utf8_czech_ci NOT NULL,
  `name` varchar(60) COLLATE utf8_czech_ci NOT NULL,
  `author` varchar(120) COLLATE utf8_czech_ci NOT NULL,
  `email` varchar(60) COLLATE utf8_czech_ci NOT NULL,
  `num` varchar(6) COLLATE utf8_czech_ci NULL,
  `publication` enum('0','1') COLLATE utf8_czech_ci NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Keys for table `photogalleries`
--
ALTER TABLE `photogalleries`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `id` (`id`);

--
-- AUTO_INCREMENT for table `photogalleries`
--
ALTER TABLE `photogalleries`
MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
*****************************************************************************/

/**
 * Simple link photogallery class
 *
 * @author   Tomas Litera  <tomaslitera@hotmail.com>
 * @version  1.0  2015-01-03
 */
class LinkGallery
{
	private $connection;
	private $authFunc;
	private $mailFrom;
	private $mailAdmin;
	private $messages;
	public static $instance;

	/** Disable construct */
	protected function __construct() {}

	/** Disable cloning */
	private function __clone() {}

	/**
	 * Get Singleton instance
	 *
	 * @param  void
	 * @return LinkGallery  instance of LinkGallery
	 */
	public static function getInstance()
	{
		if (null == self::$instance) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Initialization of values and connection
	 *
	 * @param  array  $options  array of options
	 * @return void
	 */
	public function init(array $options)
	{
		$pdoDNS = 'mysql:host='.$options['server'].';dbname='.$options['database'];
		$pdoOptions = array(
			PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
		);

		$this->connection = new \PDO($pdoDNS, $options['user'], $options['password'], $pdoOptions);

		$this->authFunc = $options['auth'];
		$this->mailFrom = $options['mail-from'];
		$this->mailAdmin = $options['mail-admin'];
		$this->messages = $options['messages'];
	}

	/**
	 * Regroupe associative array by year
	 *
	 * @param   array $rows  PDO::FETCH_ASSOC
	 * @return  array PDO::FETCH_ASSOC
	 */
	private function groupByYear($rows) {
		$groupedByYear = array();
		foreach ($rows as $row) {
			$year = $row['year'];
			if (isset($groupedByYear[$year])) {
				$groupedByYear[$year][] = $row;
			} else {
				$groupedByYear[$year] = array($row);
			}
		}

		return $groupedByYear;
	}

	/**
	 * Get the connection
	 *
	 * @param   void
	 * @return  PDO  PHP Database Object connection
	 */
	public function getConnection()
	{
		return $this->connection;
	}

	/**
	 * Get data from database
	 *
	 * @param   void
	 * @return  array PDO::FETCH_ASSOC
	 */
	private function getData()
	{
		if (call_user_func($this->authFunc, $this->connection, 2) || call_user_func($this->authFunc, $this->connection, 20)) {
			$sql = 'SELECT * FROM photogalleries ORDER BY year DESC';
		} else {
			$sql = 'SELECT * FROM photogalleries WHERE publication = "1" ORDER BY year DESC';
		}

		$result = $this->connection->query($sql);
		$rows = $result->fetchAll(PDO::FETCH_ASSOC);

		return $rows;
	}

	/**
	 * Send mail after item is inserted into gallery
	 *
	 * @param  array  $values  array of values
	 * @return  bool  TRUE | FALSE
	 */
	private function inform($values)
	{
		$subject = 'Galerie byla přidána';
		$message = 'Vaše galerie '.$values['link'].' byla přidána.';
		$headers = 'From: '.$this->mailFrom. "\r\n" .
					'Reply-To: '.$this->mailAdmin. "\r\n";

		$result = mail($values['email'].', '.$this->mailAdmin, $subject, $message, $headers);

		return $result;
	}

	/**
	 * Get HTML of error message
	 *
	 * @param  string  $actin   success | danger
	 * @param  bool    $result  TRUE | FALSE
	 * @return string  html of the message
	 */
	public function getMessage($action, $result)
	{
		$smile = ($result) ? ':-)' : ':-(';
		$result = ($result) ? 'success' : 'danger';

		return '<span class="alert alert-'.$result.'"><strong>'.$smile.'</strong> '.$this->messages[$result.'-'.$action].'</span>';
	}

	/**
	 * Insert new item
	 *
	 * @param  array  $values  data to insert
	 * @return bool   TRUE | FALSE
	 */
	public function insert($values) {
		$sql = 'INSERT INTO photogalleries (year, link, name, author, email, num, publication) VALUES (:year, :link, :name, :author, :email, :num, :publication)';

		$queryValues = array(
			':year' 		=> $values['year'],
			':link' 		=> $values['link'],
			':name' 		=> $values['name'],
			':author' 		=> $values['author'],
			':email' 		=> $values['email'],
			':num'			=> $values['num'],
			':publication' 	=> '1'
		);

		$query = $this->connection->prepare($sql);
		$result = $query->execute($queryValues);

		if ($result) {
			$result = $this->inform($values);
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/**
	 * Hide gallery from visitors
	 *
	 * @param  int  $id  id of item
	 * @return bool TRUE | FALSE
	 */
	public function hide($id)
	{
		$sql = 'UPDATE photogalleries SET publication = "0" WHERE id = "'.$id.'" LIMIT 1';
		$result = $this->connection->query($sql);
		return $result;
	}

	/**
	 * Show gallery to visitors
	 *
	 * @param  int  $id  id of item
	 * @return bool TRUE | FALSE
	 */
	public function show($id)
	{
		$sql = 'UPDATE photogalleries SET publication = "1" WHERE id = "'.$id.'" LIMIT 1';
		$result = $this->connection->query($sql);
		return $result;
	}

	/**
	 * Delete gallery from database
	 *
	 * @param  int  $id  id of item
	 * @return bool TRUE | FALSE
	 */
	public function delete($id)
	{
		$sql = 'DELETE FROM photogalleries WHERE id = "'.$id.'" LIMIT 1';
		$result = $this->connection->query($sql);
		return $result;
	}

	/**
	 * Render link photogalleries
	 *
	 * @param   void
	 * @return  void
	 */
	public function render() {
		$data = $this->groupByYear($this->getData());

		$buffer = '';

		foreach ($data as $key_year => $key_value) {
			$buffer .= '<strong>'.htmlspecialchars($key_year)."</strong><br />\n";
			foreach ($key_value as $row) {
				$unpublished = '';
				if ($row['publication'] == 0) {
					$unpublished = 'class="unpublished"';
				}
				$buffer .= '<a '.$unpublished.' href="'.$row['link'].'" title="'.htmlspecialchars($row['name']).'" target="_blank">'.htmlspecialchars($row['name']).'</a>';
				if (call_user_func($this->authFunc, $this->connection, 2) || call_user_func($this->authFunc, $this->connection, 20)) {
					$buffer .= ' (autor <a href="mailto:'.htmlspecialchars($row['email']).'" title="'.htmlspecialchars($row['email']).'">'.htmlspecialchars($row['author']).'</a>) ';
				} else {
					$buffer .= ' (autor '.htmlspecialchars($row['author']).') ';
				}
				if (call_user_func($this->authFunc, $this->connection, 2) || call_user_func($this->authFunc, $this->connection, 20)) {
					$buffer .= '<span class="handler">';
					if ($row['publication'] == 1) {
						$buffer .= '<a href="?act=hide&id='.$row['id'].'">Schovat</a> ';
					} else {
						$buffer .= '<a href="?act=show&id='.$row['id'].'">Zobrazit</a> ';
					}
					$buffer .= '<a href="?act=delete&id='.$row['id'].'">Smazat</a>';
					$buffer .= '</span>';
				}
				$buffer .= '<br />';
			}
		}

		echo $buffer;
	}
}