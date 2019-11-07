import React, {useState, useEffect } from "react"
import { Card, Form, Row, Col , Button, Spinner } from "react-bootstrap"
import PreLoaderWidget from "../components/Loader"

const Profilo = (props) => {

    const { match } = props

    const [utente, setUtente] = useState(false)

    const [resettingPassword, setResettingPassword] = useState(false)

    useEffect(() => {

        const source = axios.CancelToken.source()

        axios.get("/users/"+match.params.id, { cancelToken: source.token })
            .then( res => {
                setUtente(res.data)
            })
            .catch( error => {
                if ( axios.isCancel(error) )  return;
            })

        return () => source.cancel();

    }, [match.params.id] )

    const fieldValue = field =>( typeof utente[field] !== 'undefined' && utente[field] )? utente[field] : ""

    return (
        <React.Fragment>
            <Row>
                <Col lg="6">
                    <Card>
                        <Card.Body>
                            { !utente && <div className="py-5"><PreLoaderWidget /></div>}
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
                                <Form.Group as={Row} controlId="password">
                                    <Form.Label column md="3">Password</Form.Label>
                                    <Col md="8" >{ resettingPassword !== "success" && <Button onClick={ () => {
                                            setResettingPassword("resetting")
                                            axios.post("/password/email", { email: utente.email }, { baseURL: "" })
                                                .then(
                                                    res => { setResettingPassword("success") }
                                                ).catch( error => {
                                                    setResettingPassword(false)
                                                    window.alert(
                                                        error.response.message
                                                    )
                                                })
                                        }} >Reset della password { resettingPassword === "resetting" && <Spinner animation="border" variant="secondary" as="span" size="sm" role="status" /> }</Button>
                                    }
                                    { resettingPassword === "success" && <Button variant="outline-success" disabled>Link per il reset inviato.</Button>  }
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