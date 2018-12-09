from django.shortcuts import render
from django.views.decorators.csrf import csrf_exempt
# from rest_framework.decorators import action


def index(request):
    return render(request, "index.html")


def rooms(request):
    return render(request, 'index.html')


def room(request, id):
    return render(request, 'index.html')


@csrf_exempt
# @action(methods=['post'])
def book(request, id):
    return render(request, 'index.html')


def reservation(request, reservation_id):
    return render(request, 'index.html')
    # try:
    #     status = request.GET['status'] == '1'
    # except KeyError:
    #     status = False
    # return render(request, 'reservation.html', {'reservation_id': reservation_id, 'status': status})


def login(request):
    return render(request, 'login.html')
