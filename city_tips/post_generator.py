import random
import json

list = {}
cities = ["London", "Paris", "Istanbul", "Rome", "Amsterdam", "Barcelona", "Prague", "Vienna", "Milan", "Athens", "Berlin", "Moscow", "Venice", "Madrid", "Dublin", "Florence"]
n = 0
for city in cities:
    list[city] = []
    for i in range(100):
        list[city].append({
            "city": city,
            "author": "michajo6",
            "title": n,
            "text": n,
            "date": "6. 9. 2022 \u2502 16:19",
            "id": n
        })
        n += 1

jsonString = json.dumps(list)
with open("city_tips.json", "w") as jsonFile:
    jsonFile.write(jsonString)  