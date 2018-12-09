class Room:

    def __init__(self, room_json):
        self.room_json = room_json

    @property
    def id(self):
        return self.room_json['id']

    @property
    def name(self):
        return self.room_json['name']

    def to_dict(self):
        return {
            "id": self.id,
            "name": "Jakiś pokój trzyosobowy",
            "hotel": {
                "id": 4,
                "name": "Jakiś hotel Wrocław",
                "city": "Wrocław"
            },
            "price": 123,
            "capacity": 3,
            "availability": "2018-12-10"
        }










