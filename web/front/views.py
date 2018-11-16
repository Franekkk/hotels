from django.shortcuts import render


def rooms(request):
    return render(request, 'rooms.html')


def book(request, room_id):
    return render(request, 'book.html', {'room_id': room_id})


def reservation(request, reservation_id):
    try:
        status = request.GET['status'] == '1'
    except KeyError:
        status = False

    return render(request, 'reservation.html', {'reservation_id': reservation_id, 'status': status})


def login(request):
    return render(request, 'login.html')
