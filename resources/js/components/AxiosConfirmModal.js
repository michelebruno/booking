import React from 'react'
import Modal from 'react-bootstrap/Modal'
import Button from 'react-bootstrap/Button'
import PropTypes from "prop-types"

const AxiosConfirmModal = ( { title, show , onHide , onSuccess , url, children, method , ...props } ) => {

    const onConfirm = ( ) => {
        if ( ! url ) return // TODO dovrebbe dare un errore ->aggiunto Proptype

        const source = axios.CancelToken.source()

        axios({
            url ,
            method,
            cancelToken : source.token
        })
            .then( response => {
                if ( typeof onSuccess == 'function') {
                    onSuccess( response )
                } else onHide()
                
            })
            .catch( error => {
                    if ( axios.isCancel(error) )  return;

                    if ( error.response  ) {
                        return window.alert(error.response.message)
                    }
                    
                    return console.error(error)
                }
            )

            return () => source.CancelToken()
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
            <Button variant="light" onClick={ onHide } >Annulla</Button>
        </Modal.Footer>
    </Modal >
}

AxiosConfirmModal.propTypes = {
    url : PropTypes.string.isRequired
}

export default AxiosConfirmModal