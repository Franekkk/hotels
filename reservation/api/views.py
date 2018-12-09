import json
from rest_framework.decorators import api_view
from rest_framework.decorators import parser_classes
from rest_framework.parsers import JSONParser

from django.shortcuts import render
from django.http import HttpResponse, JsonResponse
from django.views.decorators.csrf import csrf_exempt

roomsList = [
    {
        "id": 3,
        "name": "Jakiś pokój jednoosobowy",
        "hotel": {
            "id": 3,
            "name": "Jakiś hotel ZZZ w Krakowie",
            "city": "Kraków"
        },
        "price": 123,
        "capacity": 1,
        "availability": "2018-11-10",
        "photo": "/static/img/room1.jpg"
    },
    {
        "id": 4,
        "name": "Jakiś pokój dwuosobowy",
        "hotel": {
            "id": 3,
            "name": "Jakiś hotel AA w Krakowie",
            "city": "Kraków"
        },
        "price": 321,
        "capacity": 2,
        "availability": "2019-01-10",
        "photo": "/static/img/room2.jpg"
    },
    {
        "id": 5,
        "name": "Jakiś pokój trzyosobowy",
        "hotel": {
            "id": 4,
            "name": "Jakiś hotel Wrocław",
            "city": "Wrocław"
        },
        "price": 123,
        "capacity": 3,
        "availability": "2018-12-10",
        "photo": "/static/img/room3.jpg"
    }
]


def rooms(request):
    return JsonResponse({"rooms": roomsList})


def room(request, id):
    # room = ApiResponse.room;
    # return JsonResponse(Room(room))

    room_ = None
    for r in roomsList:
        if r.get('id') == id:
            room_ = r
            break

    return JsonResponse(room_ or {"message": "There is no such room"}, status=(200 if room_ else 404))


@csrf_exempt
@api_view(['POST'])
# @parser_classes((JSONParser, ))
def book(request, id):
    # TODO
    # create reservation payload with {reservation_uuid, room_uuid, ...params}
    # needed a map of: reservation_uuid -> hotel_id cached somewhere here, żeby później móc odpytać status rezerwacji z konkretnego hotelu
    # the command of reservation must be send to the queue with payload {reservation_uuid, room_uuid, ...params}
    # worker powinien mieć scacheowaną mapę room_uuid -> hotel_id, żeby po odczytaniu message z rabbita wysłać request do konkretnego hotelu
    # konkretny hotel powinien utworzyć model reserwacji i zapisać go w swojej bazie, udawać że coś procesuje przez 5 sek, i zupdateować status
    # potem jak user będzie odpytywać status rezerwacji, to service "reservation" bedzie pytal tego hotelu czy juz ogarnął rezerwację,
    # , a jezeli reservation service nie bedzie wiedzial ktory hotel odpytac, to przeleci po wszystkich i sobie zapisze


    return JsonResponse({'received data': request.data})
    # return JsonResponse({
    #     "status": "success",
    #     "roomId": id,
    #     "reservationId": "gI46AdIM1gEaLY"
    # })


def reservation(request, id):
    return JsonResponse({
        "id": id,
        "room": {
            "id": id,
            "name": "Jakiś pokój trzyosobowy",
            "hotel": {
                "id": 4,
                "name": "Jakiś hotel Wrocław",
                "city": "Wrocław"
            },
            "price": 123,
            "capacity": 3,
            "availability": "2018-12-10"
        },
        "firstName": "Piotr",
        "lastName": "Rozmarynowski",
        "email": "piotr@venstudio.pl",
        "persons": 2,
        "checkInDate": "2018-12-15",
        "checkInTime": "15:30",
        "duration": 2,
        "notes": None,
        "status": True
    })


def reservation_status(request, id):
    return JsonResponse({
        "status": True
    })


def user_logged_in(request):
    return JsonResponse({
        "email": "test@test.pl"
    })
