<?php

require ROOT . 'core/Controller.php';
require ROOT . 'app/Model/MoviesModel.php';

class MoviesController extends Controller
{
    private function validate_input_data($input, $id_check=true)
    {
        $id = null;
        if (array_key_exists("id", $input)) {
            if (!$id_check)
                return false;
            $id = htmlentities($input["id"]);
            unset($input["id"]);
        }elseif ($id_check)
            return false;

        $movie_model = new MoviesModel();
        if (!(count($movie_model->columns) == count($input)))
            return false;
        foreach ($movie_model->columns as $column) {
            if (!array_key_exists($column, $input)) {
                return false;
            }
            $input[htmlentities($column)] = htmlentities($input[$column]);
        }
        if (!is_null($id))
            $input["id"] = $id;
        return $input;
    }

    public function handle_get($movie_id = NULL)
    {
        $movie_model = new MoviesModel();

        if (!is_null($movie_id)) {
            if (is_numeric($movie_id)) {
                $movie = $movie_model->get_movie($movie_id);
                if ($movie)
                    return $movie;
            }
        } else {
            $movies = $movie_model->get_movies();
            $response = array("count" => count($movies), "results" => $movies);
            if ($movies)
                return $response;

        }

    }


    public function handle_post()
    {
        $movie_model = new MoviesModel();
        $json = json_decode(file_get_contents('php://input'), True);
        if (!($json = $this->validate_input_data($json))) {
            http_response_code(400);
            return;
        }
        $status = $movie_model->add_movie($json);
        switch ($status) {
            case false:
                http_response_code(409);
                break;
            case true:
                http_response_code(201);
                header("Location: /movies/$status");
                break;
            case 's':
                http_response_code(500);
                break;


        }
    }

    public function handle_delete($movie_id = NULL)
    {
        $movie_model = new MoviesModel();
        $operation_status = false;
        if (!is_null($movie_id)) {
            if (is_numeric($movie_id)) {
                $operation_status = $movie_model->delete_movie($movie_id);
            } else {
                http_response_code(400);
                return;
            }
        } else
            $operation_status = $movie_model->delete_movies();

        switch ($operation_status) {
            case true:
                http_response_code(200);
                break;
            case false:
                http_response_code(404);
                break;
            case 's':
                http_response_code(500);
                break;
        }
    }

    public function handle_put($movie_id = NULL)
    {
        $movie_model = new MoviesModel();
        $json = json_decode(file_get_contents('php://input'), True);

        if (is_null($movie_id)) {
            if (!array_key_exists("movies", $json)) {
                http_response_code(400);
                return;
            }
            $ids = array();
            if (!($movie_model->count_movies() == $json["movies"]))
                foreach ($json["movies"] as $index => $movie) {
                    if (!($json["movies"][$index] = $this->validate_input_data($movie))) {
                        http_response_code(400);
                        return;
                    }else array_push($ids,$movie["id"]);
                }

            if (count(array_unique($ids)) == count($ids))
                $operation_status = $movie_model->update_entire_collection($json);
            else {
                http_response_code(400);
                return;
            }

        } elseif (($json = $this->validate_input_data($json, false)))
            $operation_status = $movie_model->update_movie($json, $movie_id);


        else {
            http_response_code(400);
            return;
        }

        switch ($operation_status) {
            case true:
                http_response_code(200);
                break;
            case false:
                http_response_code(404);
                break;
            case 's':
                http_response_code(500);
                break;
        }
    }

}