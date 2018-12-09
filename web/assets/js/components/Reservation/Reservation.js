import React from 'react';
import ReservationAPI from "../../api/ReservationAPI";
import update from "immutability-helper";

export default class Reservation extends React.Component {
    constructor(props) {
        super(props);
        this.props = props

        this.state = {
            isLoaded: false,
            reservation: null,
        }
    }

    componentDidMount() {
        fetch(`${ReservationAPI.reservationUrl(this.props.match.params.id)}`)
            .then(response => {
                if (response.ok) {
                    return response.json()
                }
                throw Error(response.statusText);
            })
            .then((result) => ({
                isLoaded: true,
                reservation: result
            }))
            .catch((error) => ({
                isLoaded: true,
                error: error.message
            }))
            .then((newState) => this.setState(newState))


        this.interval = setInterval(() => {
            this.refreshStatus();
        }, 5000);
    }

    refreshStatus() {
        const reservation = this.state.reservation
        if (reservation.status) {
            clearInterval(this.interval)
        } else {
            fetch(`${ReservationAPI.statusUrl(this.props.match.params.id)}`)
                .then(response => {
                    if (response.ok) {
                        return response.json()
                    }
                    throw Error(response.statusText);
                })
                .then((result) => ({
                    isLoaded: true,
                    reservation: update(reservation, {
                        status: {$set: result.status}
                    })
                }))
                .catch((error) => ({
                    isLoaded: true,
                    error: error.message
                }))
                .then((newState) => {
                    console.log('new state:', newState)
                    return this.setState(newState)
                })
        }
    }

    render() {
        const {error, isLoaded, reservation} = this.state;
        if (error) {
            return <div>{error}</div>;
        } else if (!isLoaded) {
            return <div>Ładowanie danych...</div>;
        } else {
            console.log(reservation)
            return (
                <div>
                    <div className="py-5 text-center">
                        <h2>Identyfikator rezerwacji: {reservation.id}</h2>
                    </div>
                    <div className="my-3 p-3 bg-white rounded box-shadow">
                        <div className="row">
                            <div className="col-md-6">
                                <div className="room row text-muted pb-2 m-1">
                                    <main className="room_content p-2 pl-4">
                                        <h3>{reservation.room.name}</h3>
                                        <h5><i className="fa fa-map-marker"></i> {reservation.room.hotel.name}</h5>
                                        <ul className="facilities pt-3">
                                            <li className="text-success"><i className="fa fa-money-bill-alt"></i> Cena: {reservation.room.price} zł / noc</li>
                                            <li><i className="fa fa-bed"></i> {reservation.room.capacity} łóżka jednoosobowe</li>
                                        </ul>
                                    </main>
                                    <aside className="room_photo">
                                        <img src={reservation.room.photo}
                                             alt="" className="mr-2 rounded" width="200" height="200"
                                        />
                                    </aside>
                                </div>
                            </div>
                            <div className="col-md-3">
                                <h5>Rezerwacja na</h5>
                                {reservation.firstName} {reservation.lastName}<br/>
                                {reservation.email}<br/>
                                Liczba osób: {reservation.persons}<br/>
                                Zameldowanie: {reservation.checkinDate} {reservation.checkinTime}<br/>
                                Noclegów: {reservation.duration}<br/>
                                Uwagi: {reservation.comment}<br/>
                            </div>
                            <div className="col-md-3 text-center">
                                <h5>Status rezerwacji</h5>
                                {!reservation.status ? (
                                    <div>
                                        <i className="fa fa-sync-alt fa-spin fa-8x text-primary"></i><br/><br/>
                                        Oczekuje na weryfikację<br/>
                                    </div>
                                ) : (
                                    <div>
                                        <i className="fa fa-10x fa-check text-success"></i>
                                        Zatwierdzona<br/>
                                    </div>
                                )}
                            </div>
                        </div>
                    </div>
                </div>
            )
        }
    }
}