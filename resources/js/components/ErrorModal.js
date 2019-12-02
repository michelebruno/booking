import React from "react"
import Modal from "react-bootstrap/Modal"
import PropTypes from "prop-types"
const ErrorModal = ( { onHide, response, ...props} ) => {

    const show = props.show ? props.show : true;

    return <Modal show={show} onHide={ onHide } centered className="border-danger " >
        <Modal.Header closeButton className="border-danger" >
            <Modal.Title className="">
                Errore { response.status } 
            </Modal.Title>
        </Modal.Header>
        <Modal.Body className=" border-danger ">
            { response.data.message }
        </Modal.Body>
    </Modal>
}

ErrorModal.propTypes = { 
    onHide: PropTypes.func.isRequired,
}

export default ErrorModal