import React from "react"
import { Card , Form} from "react-bootstrap"

const UtentiCrea = props => {
    return(
        <Card>
            <Card.Body>
                <Form onSubmit={ e => e.preventDefault() }>
                    <Form.Group controlId="email">
                        <Form.Label>Email</Form.Label>
                        <Form.Control type="email" />
                    </Form.Group>
                    <Form.Group controlId="ruolo">
                        <Form.Label>Ruolo account</Form.Label>
                        <Form.Control as="select">
                            <option value="admin">Admin</option>
                            <option value="account_manager">Account manager</option>
                            <option value="esercente">Esercente</option>
                            <option value="cliente">Cliente</option>
                        </Form.Control>
                    </Form.Group>

                </Form>
            </Card.Body>
        </Card>
    )
}

export default UtentiCrea