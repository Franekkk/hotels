from django.conf.urls import url
from django.urls import path

from . import views

urlpatterns = [
    # url(r'^', views.index, name='index'),
    path('', views.rooms, name='rooms'),
    path('room/<int:id>', views.room, name='room', ),
    path('book/<int:id>', views.book, name='book'),
    path('reservation/<str:reservation_id>', views.reservation, name='reservation'),
    path('login', views.login, name='login'),
]
