import React, { useState } from 'react';

import { Row , Col , Table , Card, Nav, Button, ButtonGroup, Badge } from 'react-bootstrap';
import BootstrapTable from 'react-bootstrap-table-next';

const TabellaConvalide = React.lazy( () => import( '../components/TabellaConvalide' ) );

const OrdiniScheda = ( props ) => {

    const [ tabAttivitàAperta, setTabAttivitàAperta] = useState("convalide");
    return(
        <React.Fragment>
            <div className="d-flex justify-content-between">
                <h1>Ordine #2657 <i title="Saldato" className="fa fa-circle text-success" /> </h1>
                <span className="d-table h-100 align-middle">
                    <ButtonGroup aria-label="Azioni rapide" className="align-middle">
                        <Button>Emetti ricevuta</Button>
                        <Button>Rimborsa</Button>
                    </ButtonGroup>
                </span>
            </div>
            <Row>
                <Col lg="9" /* Dati cliente */ >
                    <Card>
                        <Card.Body>
                            <div className="d-table"><span className="h1">Cliente </span><Badge pill variant="dark" className="align-middle">Privato</Badge></div>
                            <p>
                                <strong>Nome: </strong>Leonardo <strong>Cognome: </strong>Rossi <strong>Email: </strong> <a href="mailto:l.rossi95@example.com" >l.rossi95@example.com</a> 
                            </p>
                        </Card.Body>
                    </Card>
                </Col>
                <Col lg="3" /* Riepilogo ordine */  >
                    <Card>
                        <Card.Body>
                            <h2>Riepilogo ordine</h2>
                            <div className="d-flex justify-content-between ">
                                <strong>Stato</strong><span>Saldato <i className="fa fa-circle text-success" /></span>
                            </div>
                            <div className="d-flex justify-content-between">
                                <strong>Data</strong><span>21/12/2019</span>
                            </div>
                            <div className="d-flex justify-content-between">
                                <strong>Prodotti</strong><span>2</span>
                            </div>
                            <div className="d-flex justify-content-between">
                                <strong>Imponibile</strong><span>€24</span>
                            </div>
                            <div className="d-flex justify-content-between">
                                <strong>Imposta</strong><span>€6</span>
                            </div>
                            <div className="d-flex justify-content-between h4">
                                <strong>Totale</strong><span>€30</span>
                            </div>
                        </Card.Body>
                    </Card>
                </Col>
                <Col md="12">
                    <Card>
                        <Card.Body>
                            <h3>Articoli</h3>
                            <BootstrapTable
                                keyField="id"
                                columns={[
                                    { dataField: 'prodotto.id', text: 'Cod. prodotto'},
                                    { dataField: 'prodotto.titolo', text: 'Descrizione' },
                                    { dataField: 'tickets.token', text: 'Tickets associati' },
                                    { dataField: 'tickets.stato', text: 'Riscattati' },
                                    { dataField: 'tickets.scadenza', text: 'Scadenza' },
                                    { dataField: 'prodotto.costo', text: 'Costo unitario' },
                                    { dataField: 'qta', text: 'Quantità' },
                                    { dataField: 'costo', text: 'Totale'}
                                ]}
                                data={[
                                    { id: 'Ddsaffsd', prodotto: { id: 'DEAL-PTIPICO', titolo: 'Pranzo tipico', costo: '€15'}, tickets: { token: 'TERX54, GDFG654', stato: '1/2', scadenza: '24-03-2020'}, costo: '€30', qta: 2,}
                                ]}
                                bordered={false}
                                hover
                            />
                        </Card.Body>
                    </Card>
                </Col>
                <Col md="12" /* Transazioni */>
                    <Card>
                        <Card.Body>
                            <h3>Transazioni</h3>
                            <BootstrapTable
                                keyField="id"
                                columns={[
                                    { dataField: 'gateway', text: 'Gateway'}, 
                                    { dataField: 'importo', text: 'Importo'},
                                    { dataField: 'codice', text: 'ID transizione'},
                                    { dataField: 'descrizione', text: "Descrizione"}
                                ]}
                                data={[
                                    { id: '2', gateway: 'Paypal', importo: '€30', descrizione: "Saldato", codice: 'TE4325754XXV4325' },
                                    { id: '2d', gateway: 'Paypal', importo: '€0', descrizione: "Fallita", codice: 'TE4325754XXVytr5' }
                                ]}
                                bordered={false}
                                hover
                            />
                        </Card.Body>
                    </Card>
                </Col>
            </Row>
        </React.Fragment>
    )
}

export default OrdiniScheda;