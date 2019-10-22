import React, { useState } from 'react';

import { Row , Col , Table , Card, Nav, Button } from 'react-bootstrap';

const TabellaConvalide = React.lazy( () => import( '../components/TabellaConvalide' ) );

const EsercentiProfilo = ( props ) => {

    const [ tabAttivitàAperta, setTabAttivitàAperta] = useState("convalide");
    return(
        <React.Fragment>
            <div className="d-flex justify-content-between">
                <h1>Osteria del nonno</h1>
                <span>
                    <Button color="primary" size="sm" >
                        <i className="mdi"></i>
                        Modifica profilo
                    </Button>

                </span>
            </div>
            <Row>
                <Col xs="6" lg="4" xl="3">
                    <Card>
                        <Card.Body>
                            <Card.Title><h4>Informazioni generali</h4></Card.Title>
                            <Card.Text>
                                Via Sabotino 24/a<br/>
                                Bologna 40131<br/>
                                <a href="mailto:osteriadelnonno@example.com">osteriadelnonno@example.com</a><br/>
                                <strong>Orari di apertura:</strong><br/>
                            </Card.Text>
                        </Card.Body>
                    </Card>
                </Col>
                <Col xs="6" lg="4" xl="3">
                    <Card>
                        <Card.Body>
                            <Card.Title><h4>Dati di fatturazione</h4></Card.Title>
                            <Card.Text>
                                <span className="d-flex justify-content-between">
                                    <strong>Ragione sociale</strong><span>Ciccio pasticcio snc</span>
                                </span>
                                <span className="d-flex justify-content-between">
                                    <strong>Sede legale</strong><span>Ciccio pasticcio snc</span>
                                </span>
                                <span className="d-flex justify-content-between">
                                    <strong>P.IVA</strong><span>0123456789</span>
                                </span>
                                <span className="d-flex justify-content-between">
                                    <strong>C.F</strong><span>0123456789</span>
                                </span>
                                <span className="d-flex justify-content-between">
                                    <strong>SDI</strong><span>0125CE</span>
                                </span>
                                <span className="d-flex justify-content-between">
                                    <strong>PEC</strong><span>pec@pec.it</span>
                                </span>
                            </Card.Text>
                        </Card.Body>
                    </Card>
                </Col>
                <Col xs="6" lg="4" xl="3">
                    <Card>
                        <Card.Body>
                            <Card.Title><h4>Note</h4></Card.Title>
                            <Card.Text>
                                Note..
                            </Card.Text>
                        </Card.Body>
                    </Card>
                </Col>
                <Col>
                
                <Card>
                        <Card.Body>
                            <h2>Azioni richieste</h2>
                            <ul>
                                <li>Approva la descrizione</li>
                                <li>Inserisci le fatture di fine mese</li>
                            </ul>
                        </Card.Body>
                    </Card>
                </Col>
            </Row>
            <Row>
                <Col xs="12">
                    <Card>
                        <Card.Body>
                            <div className="d-flex justify-content-between">

                                <h2>Servizi</h2>
                                <span><Button variant="outline-secondary" size="sm">Aggiungi servizio</Button></span>
                            </div>
                            <Table hover>
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nome</th>
                                        <th>Deals associati</th>
                                        <th>Prezzo (adulti)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {
                                        [ 0, 1, 2, 3, 4, 5].map( ( value , index ) => {
                                            return(
                                                <tr key={index}>
                                                    <td>{ "S-0001" + value }</td>
                                                    <td>Pranzo da €12</td>
                                                    <td>Pranzo tipico a Bologna</td>
                                                    <td>12</td>
                                                </tr>

                                            )
                                        })
                                    }
                                </tbody>
                            </Table>
                        </Card.Body>
                    </Card>
                </Col>
            </Row>
            <Row>
                <Col xs="12">
                    <Card>
                        <Card.Header className="bg-white">
                            <h2>Attività</h2>
                            <Nav variant="tabs">
                                <Nav.Item onClick={ () => setTabAttivitàAperta("convalide")}>
                                    <Nav.Link active={ tabAttivitàAperta === "convalide" } >Convalide</Nav.Link>
                                </Nav.Item>
                                <Nav.Item onClick={ () => setTabAttivitàAperta("fatture")}>
                                    <Nav.Link  active={ tabAttivitàAperta === "fatture" } >Fatture</Nav.Link>
                                </Nav.Item>
                                <Nav.Item>
                                    <Nav.Link>Comunicazioni</Nav.Link>
                                </Nav.Item>
                            </Nav>

                        </Card.Header>
                        <Card.Body>
                        { tabAttivitàAperta === "convalide" && <React.Suspense><TabellaConvalide /></React.Suspense>}

                        </Card.Body>
                    </Card>
                </Col>
            </Row>
        </React.Fragment>
    )
}

export default EsercentiProfilo;