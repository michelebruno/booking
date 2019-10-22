import React from 'react'
import { Form , InputGroup, Card, Row, Col } from 'react-bootstrap'
import BoostrapTable from 'react-bootstrap-table-next'
const Scheda = ( { id, ...props} ) => {
    
    return(
        <React.Fragment>
            <h1>Crea nuovo</h1>
            <Form>
                <Card>
                    <Card.Body>
                        <Form.Group tag="fieldset" className="mx-lg-3 mx-xl-5">
                            <legend>Informazioni generali</legend>
                            <Form.Group as={Row} controlId="titolo">
                                <Form.Label column sm="2">Titolo</Form.Label>
                                <Col sm="10" lg="4">
                                    <Form.Control name="titolo" placeholder="titolo" />
                                </Col>
                            </Form.Group>
                            <Form.Group as={Row} controlId="descrizione">
                                <Form.Label column sm="2">Descrizione</Form.Label>
                                <Col sm="10" lg="4">
                                    <Form.Control type="textarea" name="descrizione" placeholder="descrizione"  />
                                </Col>
                            </Form.Group>
                            <Form.Group as={Row} controlId="prenotabile">
                                <Form.Label column sm="2"> </Form.Label>
                                <Col sm="10" lg="4">
                                    <Form.Switch name="prenotabile" label="prenotabile" />
                                </Col>
                            </Form.Group>
                        </Form.Group>
                    </Card.Body>
                </Card>
                <Card>
                    <Card.Body>
                        <Form.Group tag="fieldset" className="mx-lg-3 mx-xl-5">
                            <legend>Tariffario (tabella con campi modificabili)</legend>
                            <BoostrapTable keyField="id" 
                                data={ [
                                    { id: 12, target: 'Adulti', prezzo: { imponibile: '12' , imposta: '22'} }
                                ] }
                                columns={ [
                                    { dataField: "target", text: 'Target' },
                                    { dataField: "prezzo.imponibile", text: 'Imponibile' },
                                    { dataField: "prezzo.imposta", text: 'Imposta (%)', footer: 'Aggiungi...' },
                                    { dataField: 'none' , text: 'Prezzo al pubblico' }
                                ]}
                            />
                        </Form.Group>
                    </Card.Body>
                </Card>
            </Form>
        </React.Fragment>
    )
}

export default Scheda;