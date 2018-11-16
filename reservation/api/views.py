from django.shortcuts import render
from django.http import HttpResponse


def rooms(request):
    return HttpResponse("Rooms list")


def room(request, id):
    return HttpResponse("Room #%s." % id)


def book(request, id):
    return HttpResponse("Book the room #%s." % id)


def reservation(request, id):
    return HttpResponse("Reservation #%s" % id)


def reservation_status(request, id):
    return HttpResponse("Status of reservation #%s" % id)
