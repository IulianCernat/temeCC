<?php

class Model
{
    public $connection = null;
    public $columns = array("title", "year", "runtime", "genre", "director", "actors",
        "language", "awards", "imdb_rating", "poster", "plot");

    function __construct()
    {
        try {
            $this->connect();
        } catch (PDOException $exc) {
            http_response_code(500);
            exit('Failed to make database connection');
        }
    }

    private function connect()
    {
        $connection_string = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';';
        $this->connection = new PDO($connection_string, DB_USER, DB_PASS);
    }
}
