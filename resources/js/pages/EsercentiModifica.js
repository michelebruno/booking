import React from 'react'
import { Form , InputGroup, Card, Row, Col } from 'react-bootstrap'
const Scheda = ( { id, ...props} ) => {
    
    return(
        <React.Fragment>
            <h1>Crea nuovo</h1>
            <Form>
                <Card>
                    <Card.Body>
                        <Form.Group tag="fieldset" className="mx-lg-3 mx-xl-5">
                            <legend>Dati di accesso</legend>
                            <Form.Group as={Row} controlId="email">
                                <Form.Label column sm="2">Email</Form.Label>
                                <Col sm="10" lg="4">
                                    <Form.Control name="email" placeholder="Email" />
                                </Col>
                            </Form.Group>
                            <Form.Group as={Row} controlId="username">
                                <Form.Label column sm="2">Username</Form.Label>
                                <Col sm="10" lg="4">
                                    <Form.Control name="username" placeholder="Username" />
                                </Col>
                            </Form.Group>
                            <Form.Group as={Row} controlId="password">
                                <Form.Label column sm="2">Password</Form.Label>
                                <Col sm="10" lg="4">
                                    <Form.Control type="password" name="password" placeholder="Password" />
                                </Col>
                            </Form.Group>
                        </Form.Group>
                    </Card.Body>
                </Card>
                <Card>
                    <Card.Body>
                        <Form.Group tag="fieldset" className="mx-lg-3 mx-xl-5">
                            <legend>Informazioni generali</legend>
                            <Form.Group as={Row} controlId="nome">
                                <Form.Label column sm="2">Nome</Form.Label>
                                <Col sm="10">
                                    <Form.Control name="nome" />
                                </Col>
                            </Form.Group>
                            <Form.Group as={Row}>
                                <Form.Label column sm="2">Indirizzo</Form.Label>
                                <Col>
                                    <Form.Group as={Row}>
                                        <Col className="mb-2" sm="10">
                                            <InputGroup>
                                                <InputGroup.Prepend>
                                                    <InputGroup.Text >Via</InputGroup.Text>
                                                </InputGroup.Prepend>
                                                <Form.Control name="via" />
                                            </InputGroup>
                                        </Col>
                                        <Col className="mb-2" sm="2">
                                            <Form.Control id="civico"  name="civico" placeholder="Civico" />
                                        </Col>
                                        <Col className="mb-2" sm="6">
                                            <Form.Control id="città" name="Città" placeholder="Città" />
                                        </Col>
                                        <Col className="mb-2" sm="3">
                                            <Form.Control id="provincia" name="provincia" placeholder="provincia" />
                                        </Col>
                                        <Col className="mb-2" sm="3">
                                            <Form.Control id="CAP" name="CAP" placeholder="CAP" />
                                        </Col>
                                    </Form.Group>
                                </Col>
                            </Form.Group>
                            
                            <Form.Group as={Row}>
                                <Form.Label column sm="2" htmlFor="oraridiapertura" >Orari di apertura</Form.Label>
                                <Col sm="10" >
                                    <Form.Control sm="10" id="oraridiapertura" name="oraridiapertura" />   
                                </Col>
                            </Form.Group>
                        </Form.Group>
                    </Card.Body>
                </Card>
                <Card>
                    <Card.Body>
                        <Form.Group tag="fieldset" className="mx-lg-3 mx-xl-5">
                            <legend>Dati di fatturazione</legend>
                            <Form.Group as={Row} controlId="ragionesociale">
                                <Form.Label column sm="2" >Ragione sociale</Form.Label>
                                <Col sm="10" >
                                    <Form.Control name="ragionesociale" />
                                </Col>
                            </Form.Group>
                            <Form.Group as={Row} controlId="sedelegale">
                                <Form.Label column sm="2" >Sede legale</Form.Label>
                                <Col sm="10">
                                    <Form.Control />
                                </Col>
                            </Form.Group>
                            <Form.Row>
                                <Col sm="6"> 
                                    <Form.Group as={Row} controlId="piva">
                                        <Form.Label column sm="4" >Partita IVA</Form.Label>
                                        <Col sm="8">
                                            <Form.Control name="piva" />
                                        </Col>
                                    </Form.Group>
                                </Col>
                                <Col sm="6">
                                    <Form.Group as={Row} controlId="cf">
                                        <Form.Label column sm="4" >Codice fiscale</Form.Label>
                                        <Col sm="8">
                                            <Form.Control name="cf" />
                                        </Col>
                                    </Form.Group>
                                </Col>
                            </Form.Row>
                            <Form.Row>
                                <Col sm="6">
                                    <Form.Group as={Row} controlId="pec">
                                        <Form.Label column sm="4" >PEC</Form.Label>
                                        <Col sm="8">
                                            <Form.Control name="pec" />
                                        </Col>
                                    </Form.Group>
                                </Col>
                                <Col sm="6">
                                    <Form.Group as={Row} controlId="SDI">
                                        <Form.Label column sm="4" >SDI</Form.Label>
                                        <Col sm="8">
                                            <Form.Control name="SDI" />
                                        </Col>
                                    </Form.Group>
                                </Col>
                            </Form.Row>
                        </Form.Group>
                    </Card.Body>
                </Card>
            </Form>
        </React.Fragment>
    )
}

export default Scheda;