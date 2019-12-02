import React from 'react';
import ReactDOM from 'react-dom';
import "./bootstrap"
import "./_services/errorInterceptor"
import App from './app';
import store from "./_store"
import { Provider } from "react-redux" 

ReactDOM.render(<Provider store={store}><App /></Provider>, document.getElementById('root'));
/* 
// If you want your app to work offline and load faster, you can change
// unregister() to register() below. Note this comes with some pitfalls.
// Learn more about service workers: http://bit.ly/CRA-PWA
serviceWorker.unregister();
 */