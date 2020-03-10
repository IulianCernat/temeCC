import requests
import mysql.connector
import time


def get_movie(title):
    if title is "":
        return {"error": "Movie not found"}, None
    url = "http://www.omdbapi.com/"
    querystring = {"apikey": "e2ded80a", "r": "json", "t": title}
    response = requests.request("GET", url, params=querystring)
    if response.status_code == 200:
        data = response.json()
        print(data)
        if data["Response"] != 'False':
            return dict(title=data["Title"], year=data["Year"], runtime=data["Runtime"],
                        genre=data["Genre"], director=data["Director"], actors=data["Actors"],
                        language=data["Language"], awards=data["Awards"], imdb_rating=data["imdbRating"],
                        poster=data["Poster"],
                        plot=data["Plot"])

        else:
            return {"error": "Movie not found"}
    else:
        return {"error": "Something went wrong when accessing the API"}


mydb = mysql.connector.connect(
    host="localhost",
    user="root",
    password="hello",
    database="movies"

)

mycursor = mydb.cursor()
with open("movies.txt") as movie_list:
    for line in movie_list.readlines()[79:]:
        print(line)
        dummy_data = get_movie(line.strip())
        if "error" not in dummy_data:
            columns = ', '.join("`" + str(x) + "`" for x in dummy_data.keys())
            values = '%s, ' * len(dummy_data.keys())
            sql = f"INSERT INTO movies ({columns}) VALUES ({values[:-2]})"

            mycursor.execute(sql, tuple(dummy_data.values()))
            mydb.commit()
            print(mycursor.rowcount, "record inserted.")

        time.sleep(3)
mydb.close()
