import requests
import yaml
import tweepy
import re
from aylienapiclient import textapi







with open("config.yaml", "r") as file:
    configs = yaml.load(file, Loader=yaml.FullLoader)


def get_movie(title):
    if title is "":
        return {"error": "Movie not found"}, None
    url = "http://www.omdbapi.com/"
    querystring = {"apikey": configs["OMDb_key"], "r": "json", "t": title}
    response = requests.request("GET", url, params=querystring)
    if response.status_code == 200:
        data = response.json()
        if data["Response"] is not False:
            separated_info = {"Poster": data["Poster"], "Plot": data["Plot"]}
            del data["Poster"]
            del data["Plot"]
            del data["Ratings"]
            del data["Response"]
            return separated_info, data
        else:
            return {"error": "Movie not found"}, None
    else:
        return {"error": "Something went wrong when accessing the API"}, None


def get_tweets(movie_title):
    api_auth = configs["tweepy"]
    auth = tweepy.OAuthHandler(api_auth["consumer_key"], api_auth["consumer_secret"])
    auth.set_access_token(api_auth["access_token"], api_auth["access_token_secret"])
    api = tweepy.API(auth, wait_on_rate_limit=True)

    tweets = tweepy.Cursor(api.search,
                           q=movie_title + " -filter:retweets",
                           lang="en",
                           tweet_mode="extended",
                           count=100).items(20)
    tweets = [re.sub(r"@\w*\s*", "", tweet.full_text) for tweet in tweets]
    tweets = [re.sub(r"http.*?[^\s]*", "", tweet) for tweet in tweets]
    tweets = [tweet for tweet in tweets if len(tweet) > 60]

    return {index: tweet for index, tweet in enumerate(tweets, 0)}


def get_text_polarity(text):
    api_auth = configs["aylien"]
    client = textapi.Client(api_auth["app_id"], api_auth["API_key"])
    sentiment = client.Sentiment({'text': text})
    if sentiment is None:
        return "could not do analysis"
    return sentiment["polarity"]
