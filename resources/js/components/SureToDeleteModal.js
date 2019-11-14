import React from 'react'
import Modal from 'react-bootstrap/Modal'
import Button from 'react-bootstrap/Button'
import errorHandler from '../_services/errorInterceptor'

const SureToDeleteModal = ( { title, show , onHide , onSuccess , deleteUrl, children , ...props } ) => {

    const onConfirm = ( ) => {
        if ( ! deleteUrl ) return // TODO dovrebbe dare un errore
        axios.eject(errorHandler).delete(deleteUrl)
            .then( response => {
                if ( response.status == 204) {
                    if ( typeof onSuccess == 'function') {
                        onSuccess()
                    }

                    onHide()
                }
            })
            .catch( error => window.alert( error.response.message ) )
    }

    return <Modal onHide={ onHide } show={ show }>
        { title && <Modal.Header closeButton>
            <Modal.Title>
                { title }
            </Modal.Title>
        </Modal.Header>}
        <Modal.Body>
            { children }
        </Modal.Body>
        <Modal.Footer>
            <Button variant="primary" onClick={ onConfirm } >Conferma</Button>
            <Button variant="light" onClick={onHide} >Annulla</Button>
        </Modal.Footer>
    </Modal >
}

export default SureToDeleteModal