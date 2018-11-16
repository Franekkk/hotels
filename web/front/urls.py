from django.urls import path

from . import views


urlpatterns = [
    path('', views.rooms, name='rooms'),
    path('book/<int:room_id>', views.book, name='book'),
    path('reservation/<str:reservation_id>', views.reservation, name='reservation'),
    path('login', views.login, name='login'),
]
