import React, {useState, useEffect } from "react"
import { Card, Form, Row, Col } from "react-bootstrap"
import PreLoaderWidget from "../components/Loader"

const Profilo = (props) => {

    const { match } = props

    const [utente, setUtente] = useState(false)

    useEffect(() => {
        axios.get("/users/"+match.params.id)
            .then( res => {
                setUtente(res.data)
            })
            
    }, [match.params.id] )

    const fieldValue = field =>( typeof utente[field] !== 'undefined' && utente[field] )? utente[field] : ""

    return (
        <React.Fragment>
            <Row>
                <Col lg="6">
                    <Card>
                        <Card.Body>
                            { !utente && <React.Fragment><br/><br/><PreLoaderWidget /><br/><br/></React.Fragment>}
                            { utente && <Form> 
                                <Form.Group as={Row} controlId="email">
                                    <Form.Label column md="3">Email</Form.Label>
                                    <Col md="8" >
                                        <Form.Control plaintext readOnly value={fieldValue("email")} />
                                    </Col>
                                </Form.Group>
                                <Form.Group as={Row} controlId="username">
                                    <Form.Label column md="3">Username</Form.Label>
                                    <Col md="8" >
                                        <Form.Control plaintext readOnly value={fieldValue("username")} />
                                    </Col>
                                </Form.Group>
                                <Form.Group as={Row} controlId="nome">
                                    <Form.Label column md="3">Nome</Form.Label>
                                    <Col md="8" >
                                        <Form.Control plaintext readOnly value={fieldValue("nome")} />
                                    </Col>
                                </Form.Group>
                                <Form.Group as={Row} controlId="cognome">
                                    <Form.Label column md="3">Cognome</Form.Label>
                                    <Col md="8" >
                                        <Form.Control plaintext readOnly value={fieldValue("cognome")} />
                                    </Col>
                                </Form.Group>
                                <Form.Group as={Row} controlId="ruolo">
                                    <Form.Label column md="3">Ruolo</Form.Label>
                                    <Col md="8" >
                                        { false && <Form.Control as="select" plaintext readOnly value={fieldValue("ruolo")} >
                                            <option value="admin" defaultValue>Admin</option>
                                        </Form.Control>}
                                        { true && <Form.Control plaintext readOnly value={fieldValue("ruolo")} />}
                                    </Col>
                                </Form.Group>
                            </Form> }
                        </Card.Body>
                    </Card>
                </Col>

            </Row>
        </React.Fragment>
    )
    
}

export default Profilo;