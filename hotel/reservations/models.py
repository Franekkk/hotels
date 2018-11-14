from django.db import models


class RoomType(models.Model):

    name = models.CharField(max_length=100)
    description = models.TextField(blank=True)

    capacity = models.IntegerField()

    photo = models.FileField(null=True, blank=True)

    min_arrival_time = models.TimeField()
    max_departure_time = models.TimeField()

    def __str__(self):
        return self.name


class Room(models.Model):
    name = models.CharField(max_length=100, blank=True)
    room_type = models.ForeignKey(RoomType, on_delete=models.CASCADE)

    floor = models.IntegerField()

    def __str__(self):
        return f'{self.name} - {self.room_type.name}'


class Guest(models.Model):
    email = models.EmailField(max_length=100)

    first_name = models.CharField(max_length=100)
    last_name = models.CharField(max_length=100)

    street = models.CharField(max_length=100)
    postal_code = models.CharField(max_length=10)
    city = models.CharField(max_length=100)
    country = models.CharField(max_length=100)

    def __str__(self):
        return self.email


class Reservation(models.Model):

    STATUS_CONFIRMED = 'confirmed'
    STATUS_CANCELLED = 'cancelled'

    STATUS_CHOICES = (
        (STATUS_CONFIRMED, 'Confirmed'),
        (STATUS_CANCELLED, 'Cancelled')
    )

    guest = models.ForeignKey(Guest, on_delete=models.CASCADE)
    room = models.ForeignKey(Room, on_delete=models.CASCADE)

    arrival_date = models.DateTimeField()
    departure_date = models.DateTimeField()

    status = models.CharField(choices=STATUS_CHOICES, max_length=10)
    reserved_at = models.DateTimeField()
