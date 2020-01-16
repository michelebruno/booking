import React from 'react'
import Modal from 'react-bootstrap/Modal'
import Button from 'react-bootstrap/Button'
import PropTypes from "prop-types"

const AxiosConfirmModal = ( { title, show , onHide , onSuccess , url, method , data, ...props } ) => {

    const onConfirm = ( ) => {
        if ( ! url ) return // TODO dovrebbe dare un errore ->aggiunto Proptype

        const source = axios.CancelToken.source()

        let axiosData = {
            url ,
            method,
            cancelToken : source.token
        }

        if ( data ) {
            axiosData.data = data
        }

        axios(axiosData)
            .then( response => {
                if ( typeof onSuccess == 'function') {
                    onSuccess( response.data )
                } else onHide()
                
            })
            .catch( error => {
                    if ( axios.isCancel(error) )  return;

                    if ( error.response  ) {
                        return window.alert( error.response.data.message )
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
            { props.children }
        </Modal.Body>
        <Modal.Footer>
            <Button variant="primary" onClick={ onConfirm } >Conferma</Button>
            <Button variant="light" onClick={ onHide } >Annulla</Button>
        </Modal.Footer>
    </Modal>
}

AxiosConfirmModal.propTypes = {
    children : PropTypes.node,
    data : PropTypes.object,
    method: PropTypes.oneOf(['get', 'post', 'delete', 'put']),
    onHide : PropTypes.func.isRequired,
    onSuccess: PropTypes.func,
    show: PropTypes.bool.isRequired,
    title : PropTypes.node,
    url : PropTypes.string.isRequired
}

export default AxiosConfirmModal