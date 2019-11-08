import React, { useState } from "react"
import ReactDOM from "react-dom"
import ErrorModal from "../components/ErrorModal"

axios.interceptors.response.use( function( response ) {
    return response;
}, function (error) {
    if ( error.response ) {
        if (error.response.status !== 422 && error.response.status >= 400) {
            let DOMnode = document.getElementById('error-root') 
            const onHide = () => ReactDOM.unmountComponentAtNode(DOMnode)
            ReactDOM.render( <ErrorModal onHide={onHide} response={error.response} /> , DOMnode ); 
        } else console.warn("Questo errore del server non mi riguarda.")
    }
    
    return Promise.reject(error)
})