<?php

/**
 * Class Database
 */
class Database {

	private $dbh;
	private $error;
	private $stmt;

    /**
     * Database constructor.
     */
	public function __construct() {

	    global $config;

		// Set DSN
        $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['database'] . ';charset=utf8';

		// Set options
		$options = array (
				PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
				PDO::ATTR_PERSISTENT => true,
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION 
		);

		// Create a new PDO instance
		try {
            $this->dbh = new PDO ($dsn, $config['db']['user'], $config['db']['password'], $options);
		}

		// Catch any errors
		catch ( PDOException $e ) {

            global $dev_mode;

			$this->error = $e->getMessage();

			// Show real error, only if DEV Login is turned on
            if ($dev_mode) {
                $maintenanceTitle = 'Can\'t connect on the Database.';
                $maintenanceText = $this->error;
            } else {
                $maintenanceTitle = 'Maintenance';
                $maintenanceText = 'Updating Database in system';
            }

            // Prepare vars
            $maintenance = new stdClass();
            $maintenance->title = $maintenanceTitle;
			$maintenance->text = $maintenanceText;

			// Send variable
            $template = new Template('maintenance.php', true, false, false);
			$template->maintenance = $maintenance;

			// Show template
			echo $template;
			die;
		}
	}

    /**
     * @param $query
     */
	public function query($query) {
		$this->stmt = $this->dbh->prepare($query);
	}

    /**
     * @param $param
     * @param $value
     * @param null $type
     */
	public function bind($param, $value, $type = null) {
		if (is_null ( $type )) {
			switch (true) {
				case is_int ( $value ) :
					$type = PDO::PARAM_INT;
					break;
				case is_bool ( $value ) :
					$type = PDO::PARAM_BOOL;
					break;
				case is_null ( $value ) :
					$type = PDO::PARAM_NULL;
					break;
				default :
					$type = PDO::PARAM_STR;
			}
		}
		$this->stmt->bindValue ( $param, $value, $type );
	}

    /**
     * @return mixed
     */
	public function execute(){
		return $this->stmt->execute();
	}

    /**
     * @return mixed
     */
	public function closeCursor(){
	    return $this->stmt->closeCursor();
    }

    /**
     * @return mixed
     */
	public function resultset(){
		$this->execute();
		return $this->stmt->fetchAll(PDO::FETCH_OBJ);
	}

    /**
     * @return mixed
     */
	public function single(){
		$this->execute();
		return $this->stmt->fetch(PDO::FETCH_OBJ);
	}

    /**
     * @return mixed
     */
	public function rowCount(){
		return $this->stmt->rowCount();
	}

    /**
     * @return string
     */
	public function lastInsertId(){
		return $this->dbh->lastInsertId();
	}

    /**
     * @return bool
     */
	public function beginTransaction(){
		return $this->dbh->beginTransaction();
	}

    /**
     * @return bool
     */
	public function endTransaction(){
		return $this->dbh->commit();
	}

    /**
     * @return bool
     */
	public function cancelTransaction(){
		return $this->dbh->rollBack();
	}
}