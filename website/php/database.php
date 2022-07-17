<?php


class Database
{

	# Controll database interactions with this class.
	# No data manipulation is to be done here,
	# this is only for retrieving or inserting data.

	private function connect()
	{
		# Establish a connection to the database, and return PDO
		$db_name	= 'my_website';
		$db_user	= 'root';
		$db_password	= 'test123';
		$db_host	= 'database';

		$pdo = new PDO("mysql:host=" . $db_host . ";dbname=" . $db_name, $db_user, $db_password);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

		return $pdo;
	}

	private function countRows() 
	{
		$pdo = $this->connect();
		$template = "SELECT COUNT(*) FROM comments";
		$statement = $pdo->prepare($template);
		$statement->execute();
		$result = $statement->fetchAll(PDO::FETCH_ASSOC);
		return $result[0]["COUNT(*)"];
	}

	public function getData($template, $params)
	{
		# get data from the database
		$pdo = $this->connect();
		$statement = $pdo->prepare($template);

		if (count($params) > 0)
		{
			$counter = 1;
			foreach ($params as $value)
			{
				$statement->bindValue($counter, $value, PDO::PARAM_STR);
				$counter += 1;
			}
		}
		$statement->execute();
		$results = $statement->fetchAll(PDO::FETCH_ASSOC);
		return $results;
	}

	public function insertData($template, $params)
	{
		# Insert the data (no return value)	
		$rows = $this->countRows();
		if ($rows >= 200) 
		{
			echo "Table full";
		} else {

			$pdo = $this->connect();
			$statement = $pdo->prepare($template);

			$counter = 1;
			foreach ($params as $value)
			{
				$statement->bindValue($counter, $value, PDO::PARAM_STR);
				$counter += 1;
			}
			$statement->execute();
		}
	}
}




?>
