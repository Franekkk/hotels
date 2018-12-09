import React from 'react';
import {Route, Switch} from 'react-router-dom'
import Reservation from './Reservation';

export default class Content extends React.Component {
    render() {
        return (
            <Switch>
                <Route path='/reservation/:id' component={Reservation}/>
            </Switch>
        )
    }
}