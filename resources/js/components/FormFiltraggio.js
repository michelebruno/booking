import React from 'react'
import { Col, Form } from 'react-bootstrap'

const Filtraggio = ( props ) => {

    return(
        <Form>
            <Form.Row>
                <Form.Group as={Col} md="4"  controlId="data">
                    <Form.Label>Periodo</Form.Label>
                    <Form.Control as="select">
                        <option>Ultimo mese</option>
                    </Form.Control>
                </Form.Group>
                <Form.Group as={Col} md="4" controlId="stato">
                    <Form.Label>Stato</Form.Label>
                    <Form.Control as="select">
                        <option>Convalidato</option>
                        <option>Libero</option>
                    </Form.Control>
                </Form.Group>
            </Form.Row>
        </Form>
    )
}

export default Filtraggio;