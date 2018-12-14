import React from 'react';
import RoomAPI from "../../api/RoomAPI";
import update from 'immutability-helper';

export default class Room extends React.Component {
    constructor(props) {
        super(props);
        this.props = props

        this.handleChange = this.handleChange.bind(this);
        this.handleSubmit = this.handleSubmit.bind(this);

        this.state = {
            isLoaded: false,
            room: null,
            form: {
                first_name: null,
                last_name: null,
                email: null,
                persons: null,
                checkin_date: null,
                checkin_time: null,
                duration: null,
                comment: null,
            }
        }
    }

    componentDidMount() {
        fetch(`${RoomAPI.roomUrl(this.props.match.params.id)}`)
            .then (response => {
                if (response.ok) {
                   return response.json()
                }
                throw Error(response.statusText);
            })
            .then((result) => ({
                isLoaded: true,
                room: result
            }))
            .catch((error) => ({
                isLoaded: true,
                error: error.message
            }))
            .then((newState) => this.setState(newState))
    }

    handleChange(event) {
        let prop = {};
        prop[event.target.id] = event.target.value

        if (event.target.id == "checkin_time") {
            prop[event.target.id] += ":00";
        }

        this.setState((state) => update(state, {
            form: {$merge: prop}
        }));
    }

    handleSubmit(event) {
        event.preventDefault();

        // const data = this.state.form;
        var data = new FormData();
        data.append( "json", JSON.stringify( this.state.form ) );
        console.log(data)
        fetch(RoomAPI.bookUrl(this.props.match.params.id), {
            method: 'POST',
            // headers: {'Content-Type':'application/x-www-form-urlencoded'},
            body: data,
            // headers: {'Content-Type':'application/json'},
            // body: JSON.stringify(data),
        })
        .then(response => response.json())
        .then(result => {
            if (result.id) {
                this.props.history.push(`/reservation/${result.id}`);
            }
        })
    }

    render() {
        const {error, isLoaded, room} = this.state;
        if (error) {
            return <div>{error}</div>;
        } else if (!isLoaded) {
            return <div>Ładowanie danych...</div>;
        } else {
            const availableDate = new Date(`${room.availability}Z`)
            const today = new Date()
            const availabeFrom = availableDate.getTime() > today.getTime()
                ? availableDate.toLocaleDateString('pl-PL')
                : 'zaraz'
            return (
                <div>
                    <div className="py-5 text-center">
                        <h2>Rezerwuj wybrany pokój</h2>
                    </div>
                    <form className="needs-validation" onSubmit={this.handleSubmit} method="POST">
                        <div className="my-3 p-3 bg-white rounded box-shadow">
                            <div className="row">
                                <div className="col-md-6">
                                    <div className="room row text-muted pb-2 m-1">
                                        <main className="room_content p-2 pl-4">
                                            <h3>{room.name}</h3>
                                            <h5><i className="fa fa-map-marker"></i> {room.hotel.name}</h5>
                                            <ul className="facilities pt-3">
                                                <li className="text-success"><i className="fa fa-money-bill-alt"></i> Cena: {room.price} zł / noc
                                                </li>
                                                <li><i className="fa fa-bed"></i> {room.capacity} łóżka jednoosobowe</li>
                                                <li><i className="fa fa-clock"></i> Dostępny od {availabeFrom}</li>
                                            </ul>
                                        </main>
                                        <aside className="room_photo">
                                            <img src={room.photo}
                                                 alt="" className="mr-2 rounded" width="200" height="200"
                                            />
                                        </aside>
                                    </div>
                                </div>
                                <div className="col-md-6">
                                    <div className="row">
                                        <div className="col-md-4 mb-3">
                                            <label htmlFor="first_name">Imię:</label>
                                            <input type="text" className="form-control" id="first_name" placeholder="" required
                                                onChange={this.handleChange}
                                                value={this.state.form.first_name}
                                            />
                                            <div className="invalid-feedback">
                                                Wpisz poprawne imię.
                                            </div>
                                        </div>
                                        <div className="col-md-4 mb-3">
                                            <label htmlFor="last_name">Nazwisko:</label>
                                            <input type="text" className="form-control" id="last_name" placeholder="" required
                                                onChange={this.handleChange}
                                                value={this.state.form.last_name}
                                            />
                                            <div className="invalid-feedback">
                                                Wpisz poprawne nazwisko.
                                            </div>
                                        </div>
                                        <div className="col-md-4 mb-3">
                                            <label htmlFor="email">Email:</label>
                                            <input type="email" className="form-control" id="email" placeholder="" required
                                                onChange={this.handleChange}
                                                value={this.state.form.email}
                                            />
                                            <div className="invalid-feedback">
                                                Wpisz poprawny adres email.
                                            </div>
                                        </div>
                                    </div>
                                    <div className="row">
                                        <div className="col-md-2 mb-2">
                                            <label htmlFor="persons">Osób:</label>
                                            <input type="number" className="form-control" id="persons" placeholder="2 os." min="1" max="3" required
                                                onChange={this.handleChange}
                                                value={this.state.form.persons}
                                            />
                                            <div className="invalid-feedback">
                                                Podaj poprawną ilość osób
                                            </div>
                                        </div>
                                        <div className="col-md-4 mb-4">
                                            <label htmlFor="checkin_date">Zameldowanie</label>
                                            <input type="date" className="form-control" id="checkin_date" min={today.toISOString().substring(0,10)} required
                                                onChange={this.handleChange}
                                                value={this.state.form.checkin_date}
                                            />
                                            <div className="invalid-feedback">
                                                Wpisz poprawną datę.
                                            </div>
                                        </div>
                                        <div className="col-md-3 mb-3">
                                            <label htmlFor="checkin_time">Godzina</label>
                                            <input type="time" className="form-control" id="checkin_time" required
                                                onChange={this.handleChange}
                                                value={this.state.form.checkin_time}
                                            />
                                            <div className="invalid-feedback">
                                                Wpisz poprawną godzinę.
                                            </div>
                                        </div>
                                        <div className="col-md-3 mb-3">
                                            <label htmlFor="duration">Noclegów:</label>
                                            <input type="number" className="form-control" id="duration" placeholder="2 noclegi" min="1" max="3" required
                                                onChange={this.handleChange}
                                                value={this.state.form.duration}
                                            />
                                            <div className="invalid-feedback">
                                                Podaj poprawną ilość noclegów
                                            </div>
                                        </div>
                                    </div>
                                    <div className="row">
                                        <div className="col-md-12 mb-3">
                                            <label htmlFor="comment">Uwagi</label>
                                            <textarea placeholder="np. Czy jest dostępny garaż?" className="form-control" id="comment"
                                                      onChange={this.handleChange}
                                                      value={this.state.form.comment}
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div className="text-center">
                                <input type="submit" value="Rezerwuj" className="btn btn-success btn-outline-success btn-lg" />
                                {/*<a href="/reservation/gI46AdIM1gEaLY" className="btn btn-success btn-outline-success btn-lg">Rezerwuj</a>*/}
                            </div>
                        </div>
                    </form>
                </div>
            )
        }
    }
}