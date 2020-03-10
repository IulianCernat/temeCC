<?php
require ROOT . 'core/Model.php';

class MoviesModel extends Model
{
    private function search_movie_by_title($title)
    {
        if (is_null($title))
            return False;
        $sql = "SELECT id FROM movies where title = :movie_title LIMIT 1";
        $query = $this->connection->prepare($sql);
        $parameters = array(':movie_title' => $title);
        $query->execute($parameters);
        return $query->rowcount() ? true : false;

    }

    public function count_movies()
    {
        $sql = "SELECT COUNT(id) AS nr_of FROM movies";
        $query = $this->connection->prepare($sql);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }


    public function get_movies()
    {
        $sql = "SELECT * FROM movies";
        $query = $this->connection->prepare($sql);
        $success = $query->execute();
        if ($success) {
            $response = array();
            $results = $query->fetchAll(PDO::FETCH_ASSOC);
            foreach ($results as $result) {
                $new_array = array();
                foreach ($result as $k => $v)
                    $new_array[utf8_encode($k)] = utf8_encode($v);
                array_push($response, $new_array);

            }
            return $response;
        } else
            return null;
    }

    public function get_movie($movie_id)
    {

        $sql = "SELECT * FROM movies WHERE id = :movie_id LIMIT 1";
        $query = $this->connection->prepare($sql);
        $parameters = array(':movie_id' => $movie_id);
        $query->execute($parameters);
        $result = $query->rowcount() ? $query->fetch(PDO::FETCH_ASSOC) : false;
        if ($result)
            foreach ($result as $k => $v) {
                $result[$k] = utf8_encode($v);
            }
        return $result;
    }


    public function add_movie($movie_data)
    {
        if ($this->search_movie_by_title($movie_data["title"]))
            return false;
        $sql = "INSERT INTO movies (title, year, runtime, genre, director, actors, 
                   language, awards, imdb_rating, poster, plot) VALUES (:title, :year, :runtime, :genre, :director, :actors, 
                   :language, :awards, :imdb_rating, :poster, :plot)";
        $query = $this->connection->prepare($sql);

        foreach ($movie_data as $k => $v) {
            $movie_data[':' . $k] = $movie_data[$k];
            unset($movie_data[$k]);
        }
        $success = $query->execute($movie_data);
        if ($success)
            return $this->connection->lastInsertId();
        else
            return 'sql_error';

    }

    public function delete_movie($movie_id)
    {
        if (!$this->get_movie($movie_id))
            return false;
        $sql = "DELETE FROM movies WHERE id = :movie_id LIMIT 1";
        $query = $this->connection->prepare($sql);
        $parameters = array(':movie_id' => $movie_id);
        $success = $query->execute($parameters);
        if ($success)
            return true;
        else
            return 'sql_error';
    }

    public function delete_movies()
    {   if ($this->count_movies()['nr_of'] == 0)
            return false;

        $sql = "DELETE FROM movies";
        $query = $this->connection->prepare($sql);
        $success = $query->execute();
        if ($success)
            return true;
        else
            return 'sql_error';
    }

    public function update_movie($movie_data, $movie_index)
    {

        $database_movie = $this->get_movie($movie_index);
        if (!$database_movie)
            return false;
        if (!($database_movie == $movie_data)) {

            $sql = "UPDATE movies SET title=:title, year=:year, runtime=:runtime, genre=:genre, director=:director, actors=:actors,
                language=:language, awards=:awards, imdb_rating=:imdb_rating, poster=:poster, plot=:plot WHERE id=:movie_index";
            $query = $this->connection->prepare($sql);

            foreach ($movie_data as $k => $v) {
                $movie_data[':' . $k] = $movie_data[$k];
                unset($movie_data[$k]);
            }
            $movie_data[":movie_index"] = $movie_index;
            $success = $query->execute($movie_data);
        } else {
            $success = true;
        }

        if ($success)
            return true;
        else
            return 'sql_error';

    }


    public function update_entire_collection($movies_data)
    {
        $this->connection->beginTransaction();

        $operation_status = true;

        foreach ($movies_data["movies"] as $movie) {
            if (!$this->get_movie($movie["id"]))
            {
                $this->connection->rollback();
                return false;
            }
            $movie_index = $movie["id"];
            unset($movie["id"]);
            $operation_status = $this->update_movie($movie, $movie_index);
            if ($operation_status !== true)
            {
                $this->connection->rollback();
                return "sql_error";
            }
        }
        $this->connection->commit();
        return $operation_status;

    }
}