import React from "react"
import ReactDOM from "react-dom"
import ErrorModal from "../components/ErrorModal"

const errorHandler = axios.interceptors.response.use( function( response ) {
    return response;
}, function (error) {
    if ( error.response ) {
        if (error.response.status !== 422 && error.response.status >= 400) {
            let DOMnode = document.getElementById('error-root') 
            const onHide = () => ReactDOM.unmountComponentAtNode(DOMnode)
            ReactDOM.render( <ErrorModal onHide={onHide} response={error.response} /> , DOMnode ); 
        } 

    } else {
        // Non di axios
        // ? Lasciare vuoto perché poi la promessa viene rigettata sotto
    }
    
    return Promise.reject(error)
})

export default errorHandler;
