from flask import Flask, render_template, request, jsonify
import json
from utilites import *
from flask_restful import Resource, Api
import datetime
from flask import g
from rfc3339 import rfc3339
import time

app = Flask(__name__)

api = Api(app)


class WebserverDebug(Resource):
    def get(self):
        with open("logging.log") as log:
            return {"request": log.readlines()}



api.add_resource(WebserverDebug, '/metrics/')


@app.before_request
def start_timer():
    g.start = time.time()


@app.after_request
def log_request(response):
    if request.path == '/favicon.ico':
        return response
    if request.path == '/get_data':
        return response
    if request.path == '/get_tweets':
        return response

    now = time.time()
    duration = round(now - g.start, 2)
    dt = datetime.datetime.fromtimestamp(now)
    timestamp = rfc3339(dt, utc=True)

    ip = request.headers.get('X-Forwarded-For', request.remote_addr)
    host = request.host.split(':', 1)[0]
    args = dict(request.args)

    log_params = [
        ('method', request.method),
        ('path', request.path),
        ('status', response.status_code),
        ('duration', duration),
        ('time', timestamp),
        ('ip', ip),
        ('host', host),
        ('params', args)
    ]

    request_id = request.headers.get('X-Request-ID')
    if request_id:
        log_params.append(('request_id', request_id))

    parts = []
    for name, value in log_params:
        part = "{}={}".format(name, value)
        parts.append(part)
    line = " ".join(parts)

    app.logger.info(line)

    return response


@app.route('/', methods=["POST", "GET"])
def index():
    return render_template("index.html")


@app.route("/get_data/", methods=["POST", "GET"])
def get_data():
    if request.method == 'POST':
        data_set1, data_set2 = get_movie(request.form["content"])
        if "error" in data_set1:
            return json.dumps({"output": render_template('response.html', set1=data_set1)})
        data_set3 = get_text_polarity(data_set1["Plot"])
        return json.dumps({"output": render_template('response.html', set1=data_set1, set2=data_set2, set3=data_set3)})


@app.route("/get_related_tweets/", methods=["POST", "GET"])
def get_related_tweets():
    if request.method == 'POST':
        data_set1 = get_tweets(request.form["content"])
        data_set2 = [get_text_polarity(data_set1[tweet_index]) for tweet_index in data_set1]

        return json.dumps({"output": render_template('tweets.html', set1=data_set1, set2=data_set2)})


if __name__ == "__main__":
    import logging

    logging.basicConfig(filename="logging.log", level=logging.INFO)
    app.run(debug=True)
