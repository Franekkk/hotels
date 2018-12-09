from django.urls import path

from . import views


urlpatterns = [
    # /api/
    path('', views.rooms, name='rooms'),
    # /api/room/5
    path('room/<int:id>', views.room, name='room'),
    # /api/room/5/book
    path('room/<int:id>/book', views.book, name='book'),
    # /api/reservation/5
    path('reservation/<int:id>', views.reservation, name='reservation'),
    # /api/reservation/5/status
    path('reservation/<int:id>/status', views.reservation_status, name='reservation_status'),
    # /api/users/me
    path('users/me', views.user_logged_in, name='user_logged_in'),
]
