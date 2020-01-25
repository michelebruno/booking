/* eslint-disable react/prop-types */
import React, { useState , useEffect } from 'react';
import { connect } from 'react-redux'

import { setTopbarButtons , unsetTopbarButtons } from '../_actions'

import { PayPalButton } from 'react-paypal-button-v2'
import { Row , Col , Card, Button, ButtonGroup, Badge } from 'react-bootstrap';
import BootstrapTable from 'react-bootstrap-table-next';
import PreLoaderWidget from '../components/Loader';
import { prezziFormatter } from '../_services/helpers';

const OrdiniScheda = ( { match , location } ) => {

    let i;

    if ( location && location.state && location.state.ordine ) {
        i = location.state.ordine
        i.willBeReloaded = true
    }

    const [ordine, setOrdine] = useState(i);

    useEffect(() => {
        if ( ordine && ! ordine.willBeReloaded ) return;

        const s = axios.CancelToken.source();

        const url = ordine ? ordine._links.self : '/ordini/' + match.params.ordine_id

        axios.get( url , { cancelToken : s.token } )
            .then( res => setOrdine(res.data) )
            .catch( e => e )
        return () => {
            s.cancel()
        };
    }, [ordine])

    if ( ! ordine ) return <PreLoaderWidget />

    if ( ordine && ordine.stato ) {
        switch (ordine.stato) {
            case "pending":
                i = {
                    label : "In attesa di pagamento.",
                    variant : "warning",
                    colorClass : "text-warning"
                }
                break;

            case "completo":
                i = {
                    label : "Completo",
                    variant : "success",
                    colorClass : "text-success"
                }
                break;
        
            default:
                i = {
                    label : ordine.stato,
                    variant : ordine.stato,
                    colorClass : ordine.stato
                }
                break;
        }
    }

    return(
        <React.Fragment>
            <div className="d-flex justify-content-between">
                <div>
                <h1 className="d-inline-block">Ordine { ordine.id } </h1> <Badge className="h3" variant={i.variant } pill={true} >{ i.label }</Badge> 

                </div>
                <span className="d-table h-100 align-middle">
                    <ButtonGroup aria-label="Azioni rapide" className="align-middle">
                        <Button>Emetti ricevuta</Button>
                        <Button>Rimborsa</Button>
                    </ButtonGroup>
                </span>
            </div>
            <Row>
                <Col lg="4" /* Dati cliente */ >
                    <Card>
                        <Card.Body>
                            <div className="d-table"><span className="h2">Cliente </span><Badge pill variant="dark" className="align-middle">Privato</Badge></div>
                            <div className="d-flex justify-content-between ">
                                <strong>Nome:</strong><span>{ ordine.cliente.username } </span>
                            </div>
                            <div className="d-flex justify-content-between">
                                <strong>Email:</strong><span>{ ordine.cliente.email }</span>
                            </div>
                            <div className="d-flex justify-content-between">
                                <strong>Cod. Fiscale:</strong><span>{ ordine.cliente.cf }</span>
                            </div>
                            <div className="d-flex justify-content-between">
                                <strong>P. Iva:</strong><span>{ ordine.cliente.piva }</span>
                            </div>
                        </Card.Body>
                    </Card>
                </Col>
                <Col lg="3" /* Riepilogo ordine */  >
                    <Card>
                        <Card.Body>
                            <h2>Riepilogo ordine</h2>
                            <div className="d-flex justify-content-between ">
                                <strong>Stato</strong><span>{ i.label } <i className={"fa fa-circle " + i.colorClass } /></span>
                            </div>
                            <div className="d-flex justify-content-between">
                                <strong>Data</strong><span>{ ordine.data }</span>
                            </div>
                            <div className="d-flex justify-content-between">
                                <strong>Prodotti</strong><span>2</span>
                            </div>
                            <div className="d-flex justify-content-between">
                                <strong>Imponibile</strong><span>{ prezziFormatter(ordine.imponibile) }</span>
                            </div>
                            <div className="d-flex justify-content-between">
                                <strong>Imposta</strong><span>{ prezziFormatter(ordine.imposta) }</span>
                            </div>
                            <div className="d-flex justify-content-between h4">
                                <strong>Totale</strong><span>{ prezziFormatter(ordine.importo) }</span>
                            </div>
                        </Card.Body>
                    </Card>
                </Col>
                <Col>
                    <Card>
                        <Card.Body>
                            { ordine.meta && ordine.meta.paypal_approve_url && <PayPalButton 
                                amount={ ordine.importo }
                                createOrder={ ( a, b) => {
                                    console.log(a,b)
                                    let url = new URL(ordine.meta.paypal_approve_url)
                                    return url.searchParams.get('token')

                                }}
                                shippingPreference="NO_SHIPPING"
                                onSuccess={console.warn}
                            />}
                        </Card.Body>
                    </Card>
                </Col>
                <div className="w-100" />
                <Col md="12">
                    <Card>
                        <Card.Body>
                            <h3>Articoli</h3>
                            <BootstrapTable
                                keyField="id"
                                columns={[
                                    { dataField: 'codice', text: 'Cod. prodotto'},
                                    { dataField: 'descrizione', text: 'Descrizione' },
                                    { dataField: 'tickets.token', text: 'Tickets associati' },
                                    { dataField: 'tickets.stato', text: 'Riscattati' },
                                    { dataField: 'tickets.scadenza', text: 'Scadenza' },
                                    { dataField: 'costo_unitario', text: 'Costo unitario', formatter: prezziFormatter },
                                    { dataField: 'quantita', text: 'Quantità' },
                                    { dataField: 'importo', text: 'Totale', formatter: prezziFormatter}
                                ]}
                                data={ordine.voci}
                                bordered={false}
                                hover
                                responsive
                            />
                        </Card.Body>
                    </Card>
                </Col>
                <Col md="12" /* Transazioni */ >
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
                                    { id: '2', gateway: 'Paypal', importo: '€30', descrizione: "Saldato", codice: 'TE43257d54XXV4325' },
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

export default connect(null, { setTopbarButtons , unsetTopbarButtons } )(OrdiniScheda);