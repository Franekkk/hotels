import React from 'react';
import {Link} from "react-router-dom";
import RoomAPI from "../api/RoomAPI";

export default class Navbar extends React.Component {
    state = {
        loggedUser: null,
    };
    //
    // componentDidMount() {
    //     fetch(`${RoomAPI.domain()}/api/users/me`, {
    //         headers: new Headers({'Content-Type': 'application/json'}),
    //     })
    //         .then(res => res.json())
    //         .then(
    //             (user) => ({
    //                 loggedUser: user,
    //             }),
    //             () => ({
    //                 loggedUser: null
    //             })
    //         )
    //         .then((newState) => this.setState(newState))
    // }

    render() {
        return (
            <nav className="navbar navbar-expand-lg navbar-light bg-light container">
                <a className="navbar-brand" href="/">
                    <img src="/static/img/logo.png" width="30" height="30" className="d-inline-block align-top" alt="" />
                        HotelApp
                </a>
                <button className="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span className="navbar-toggler-icon"></span>
                </button>

                <div className="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul className="navbar-nav mr-auto">
                        <li className={window.location.pathname === '/' ? "nav-item active" : "nav-item"}>
                            <Link to='/' className="nav-link" >Pokoje <span
                                className="sr-only">(current)</span>
                            </Link>
                        </li>
                        <li className="nav-item">
                            <a className="nav-link" href="/login">Zaloguj</a>
                        </li>
                    </ul>
                    <form className="form-inline my-2 my-lg-0">
                        <input className="form-control mr-sm-2" type="search" placeholder="Szukaj" aria-label="Szukaj" />
                            <button className="btn btn-outline-success my-2 my-sm-0" type="submit">Szukaj</button>
                    </form>
                </div>
            </nav>
        )
    }
}