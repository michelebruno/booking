/* eslint-disable react/prop-types */
import React, { useState , useEffect } from 'react';
import { connect } from 'react-redux'

import { setTopbarButtons , unsetTopbarButtons } from '../_actions'

import { PayPalButton } from 'react-paypal-button-v2'
import { Row , Col , Card, Button, ButtonGroup, Badge } from 'react-bootstrap';
import BootstrapTable from 'react-bootstrap-table-next';
import PreLoaderWidget from '../components/Loader';
import Helpers, { prezziFormatter } from '../_services/helpers';

const OrdiniScheda = ( { match , location } ) => {

    let initialState;

    if ( location && location.state && location.state.ordine ) {
        initialState = location.state.ordine
        initialState.willBeReloaded = true
    }

    const [ordine, setOrdine] = useState(initialState);

    const reloadApi = () => {
        let n = Object.assign({}, ordine, { willBeReloaded : true });
        setOrdine(n)
    }

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


    const stato = Helpers.ordini.stato(ordine.stato)

    return(
        <React.Fragment>
            <div className="d-flex justify-content-between">
                <div>
                <h1 className="d-inline-block">Ordine { ordine.id } </h1> <Badge className="h3" variant={ stato.variant } pill={true} >{ stato.label }</Badge> 

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
                                <strong>Stato</strong><span>{ stato.label } <i className={"fa fa-circle " + stato.colorClass } /></span>
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
                { ordine.dovuto > 0 && <Col>
                    <Card>
                        <Card.Body>
                            { ordine.meta && ordine.meta.paypal_approve_url && <PayPalButton 
                                amount={ ordine.dovuto }
                                createOrder={ () => {

                                    let url = new URL(ordine.meta.paypal_approve_url)
                                    return url.searchParams.get('token')

                                }}
                                shippingPreference="NO_SHIPPING"
                                onSuccess={( data ) => {
                                    axios.post(ordine._links.transazioni + "/paypal" , data.purchase_units[0].payments.captures[0] )
                                        .then( response => setOrdine(response.data) )
                                        .catch( console.error )
                                }}
                            />}
                        </Card.Body>
                    </Card>
                </Col>}
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
                                    {
                                        dataField: 'tickets', 
                                        text: 'Tickets associati', 
                                        formatter: (cell) => {
                                            if (!cell) {
                                                return "";
                                            }
                                            return cell.map( ( v ) => v.token ).join(", ")
                                        } 
                                    },
                                    { dataField: 'riscattati', text: 'Riscattati' , formatter : ( cell , row ) => cell.toString() + " / " + row.quantita },
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
                                    { dataField: 'importo', text: 'Importo', formatter: prezziFormatter},
                                    { dataField: 'transazione_id', text: 'ID transizione'},
                                    { dataField: 'stato', text: "Stato"}
                                ]}
                                data={ ordine.transazioni }
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