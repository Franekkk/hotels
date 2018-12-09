import React from 'react';
import {Route, Switch} from 'react-router-dom'
import Room from './Room';
import RoomList from './List';

export default class Content extends React.Component {
    render() {
        return (
            <Switch>
                <Route exact path='/' component={RoomList}/>
                <Route path='/room/:id' component={Room}/>
                {/*<Route path='/room/:id/book' component={RoomBook}/>*/}
            </Switch>
        )
    }
}