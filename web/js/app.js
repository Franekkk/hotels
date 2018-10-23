import React from 'react';
import ReactDOM from 'react-dom'
import {observable} from 'mobx';
import {observer, Provider} from 'mobx-react';
class AppStore {
    @observable bar;
    constructor(foo) {
        this.bar = foo;
    }
}
const store = new AppStore('Hello World 21!');
const App = observer(() => {
    return (
        <Provider store={store}>
        <div>
        <h1>{store.bar}</h1>
    </div>
    </Provider>
);
});
ReactDOM.render(<App/>, document.getElementById('app'));