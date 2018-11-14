from django.contrib import admin

from reservations.models import Reservation, Guest, RoomType, Room


@admin.register(Reservation)
class ReservationAdmin(admin.ModelAdmin):
    list_display = ('guest', 'room', 'arrival_date', 'departure_date', 'reserved_at')


@admin.register(Guest)
class GuestAdmin(admin.ModelAdmin):
    list_display = ('email', 'first_name', 'last_name', 'city', 'country')


@admin.register(Room)
class RoomAdmin(admin.ModelAdmin):
    list_display = ('name', 'room_type', 'floor')


@admin.register(RoomType)
class RoomTypeAdmin(admin.ModelAdmin):
    list_display = ('name', 'photo', 'capacity')

    def capacity(self, obj):
        return f'<img src="{obj.photo.url}"/>'
    capacity.allow_tags = True
