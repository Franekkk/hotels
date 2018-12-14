import React from 'react';
import RoomAPI from "../../api/RoomAPI";

export default class List extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            error: null,
            isLoaded: false,
            rooms: []
        };
    }

    componentDidMount() {
        fetch(RoomAPI.roomListUrl())
            .then(res => res.json())
            .then(
                (result) => {
                    this.setState({
                        isLoaded: true,
                        rooms: result
                    });
                },
                // Note: it's important to handle errors here
                // instead of a catch() block so that we don't swallow
                // exceptions from actual bugs in components.
                (error) => {
                    this.setState({
                        isLoaded: true,
                        error
                    });
                }
            )
    }

    render() {
        const {error, isLoaded, rooms} = this.state;
        if (error) {
            return <div>Error: {error.message}</div>;
        } else if (!isLoaded) {
            return <div>Ładowanie danych...</div>;
        } else {
            const availabeFrom = (date) => {
                const date_ = new Date(`${date}Z`);
                return date_.getTime() > new Date().getTime()
                    ? date_.toLocaleDateString('pl-PL')
                    : 'zaraz'
            }
            return (
                <div className="my-3 p-3 bg-white rounded box-shadow">
                    <h5 className="border-bottom border-gray pb-2 mb-0">Dostępne pokoje </h5>
                    {rooms.map(room => (
                        <div className="room row text-muted border-bottom border-gray pb-2 m-1">
                            <main className="room_content p-2 pl-4">
                                <h3>{room.name}</h3>
                                <h5><i className="fa fa-map-marker"></i> {room.hotel.name}</h5>
                                <ul className="facilities pt-3">
                                    <li className="text-success"><i className="fa fa-money-bill-alt"></i> Cena: {room.price} zł / noc
                                    </li>
                                    <li><i className="fa fa-bed"></i> {room.capacity} łóżka jednoosobowe</li>
                                    <li><i className="fa fa-clock"></i> Dostępny od {availabeFrom(room.availability)}</li>
                                </ul>
                            </main>
                            <aside className="room_photo">
                                <img src={room.photo}
                                     alt="" className="mr-2 rounded" width="200" height="200"
                                />
                            </aside>
                            <aside className="room_actions p-2">
                                <div className="pull-md-right">
                                    <a href={`/room/${room.id}`} className="btn btn-success btn-outline-success">Zarezerwuj nocleg</a>
                                </div>
                            </aside>
                        </div>
                    ))}
                </div>
            );
        }
    }
}