import React from 'react';
import {Route, Switch} from 'react-router-dom'
import ContentEvents from './Event/ContentEvents';
import ContentReservations from "./Reservation/Content";
import ContentRooms from "./Room/Content";

export default class Content extends React.Component {
    render() {
        return (
            <main className="container">
                <Switch>
                    <Route path='/reservation' component={ContentReservations}/>
                    <Route path='/' component={ContentRooms}/>
                    {/*<Route path='/room' component={ContentEvents}/>*/}
                    {/*<Route exact path='/app/users' component={Registration}/>*/}
                </Switch>
            </main>
        );
    }
};