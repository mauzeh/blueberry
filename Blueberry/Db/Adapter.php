<?php

/**
 * Represents the connection to the MySQL database. Allows implementors to use
 * different connections at once and refer easily to them by a self-determined
 * keyword.
 */
class Blueberry_Db_Adapter {

	const DEFAULT_LABEL = 'default';

	static $connections = array();
	static $activeConnection = false;

    /**
     * Introduces a new MySQL connection to the current environment.
     *
     * @param string $host The MySQL hostname
     * @param string $username The MySQL username
     * @param string $password The MySQL password
     * @param string $database The MySQL database name
     * @param string $label The label by which to refer to this connection
     */
	public static function addConnection($host, $username, $password, $database,
										 $label = self::DEFAULT_LABEL){

		$link = mysql_connect($host, $username, $password)
                or die('MySQL cannot connect: '.mysql_error());

		self::$connections[$label] = array(
			'link' => $link,
			'database' => $database
		);

		if(self::$activeConnection == false){
			self::setActiveConnection($label);
		}
	}

    /**
     * Sets the requested connection as the active one; all queries will now be
     * executed with the new connection. Only applies when using a Bb_Db_Query
     * based mechanism for database interaction.
     *
     * @param string $label The label by which to refer to this connection.
     */
	public static function setActiveConnection($label)
	{
		if(!is_resource(self::$connections[$label]['link'])){
			throw new Blueberry_Db_Exception("Cannot use connection $label.");
		}

		self::$activeConnection = self::$connections[$label];
	}

    /**
     * Returns the current MySQL connection resource for use by Bb_Db_Query based
     * mechanisms.
     *
     * @return resource The current MySQL connection.
     */
	public static function getActiveConnection()
	{
		if(self::$activeConnection == false){
			self::addConnection(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		}

		mysql_select_db(
			self::$activeConnection['database'],
			self::$activeConnection['link'])

		or die(mysql_error());

		return self::$activeConnection['link'];
	}
}
