/* eslint-disable react/prop-types */
import React, { useState, useEffect } from 'react';
import { connect } from 'react-redux'

import { setTopbarButtons, unsetTopbarButtons } from '../_actions'

import { PayPalButton } from 'react-paypal-button-v2'
import { Button, ButtonGroup, Badge } from 'react-bootstrap';
import PreLoaderWidget from '../components/Loader';
import Helpers, { prezziFormatter } from '../_services/helpers';

import Grid from '@material-ui/core/Grid';

import Card from '@material-ui/core/Card';

import CardContent from '@material-ui/core/CardContent';
import OrdiniVociTabella from '../components/OrdiniVociTabella';
import OrdiniTransazioniTabella from '../components/OrdiniTransazioniTabella';


const OrdiniScheda = ({ match, location, history }) => {

    let initialState;

    if (location && location.state && location.state.ordine) {
        initialState = location.state.ordine
        initialState.willBeReloaded = true
    }

    const [ordine, setOrdine] = useState(initialState);

    useEffect(() => {

        if (history.location) {
            const state = Object.assign({}, { ...history.location.state }, { ordine });
            history.replace({ ...history.location, state })
        }

        if (ordine && !ordine.willBeReloaded) return;

        const source = axios.CancelToken.source();

        const url = ordine && ordine._links ? ordine._links.self : '/ordini/' + match.params.ordine_id

        axios.get(url, { cancelToken: source.token })
            .then(res => {
                setOrdine(res.data);
            })
            .catch(e => e)

        return source.cancel
    }, [match.params.ordine_id, ordine])

    if (!ordine) return <PreLoaderWidget />

    const stato = Helpers.ordini.stato(ordine.stato)

    return (
        <React.Fragment>
            <div className="d-flex justify-content-between">
                <div>
                    <h1 className="d-inline-block">Ordine {ordine.id} </h1> <Badge className="h3" variant={stato.variant} pill={true} >{stato.label}</Badge>
                </div>

                <span className="d-table h-100 align-middle">
                    <ButtonGroup aria-label="Azioni rapide" className="align-middle">
                        <Button>Emetti ricevuta</Button>
                        <Button>Rimborsa</Button>
                    </ButtonGroup>
                </span>
            </div>
            <Grid container spacing={2}>
                <Grid item lg={4} /* Dati cliente */ >
                    <Card style={{ position: "relative" }} >
                        {ordine.cliente && <CardContent >
                            <div className="d-table"><span className="h2">Cliente </span><Badge pill variant="dark" className="align-middle">Privato</Badge></div>
                            <div className="d-flex justify-content-between ">
                                <strong>Nome:</strong><span>{ordine.cliente.username} </span>
                            </div>
                            <div className="d-flex justify-content-between">
                                <strong>Email:</strong><span>{ordine.cliente.email}</span>
                            </div>
                            <div className="d-flex justify-content-between">
                                <strong>Cod. Fiscale:</strong><span>{ordine.cliente.cf}</span>
                            </div>
                            <div className="d-flex justify-content-between">
                                <strong>P. Iva:</strong><span>{ordine.cliente.piva}</span>
                            </div>
                        </CardContent>}
                        {!ordine.cliente && <PreLoaderWidget />}
                    </Card>
                </Grid>
                <Grid item lg={4} /* Riepilogo ordine */  >
                    <Card >
                        <CardContent>
                            <h2>Riepilogo ordine</h2>
                            <div className="d-flex justify-content-between ">
                                <strong>Stato</strong><span>{stato.label} <i className={"fa fa-circle " + stato.colorClass} /></span>
                            </div>
                            <div className="d-flex justify-content-between">
                                <strong>Data</strong><span>{ordine.data}</span>
                            </div>
                            <div className="d-flex justify-content-between">
                                <strong>Prodotti</strong><span>2</span>
                            </div>
                            <div className="d-flex justify-content-between">
                                <strong>Imponibile</strong><span>{prezziFormatter(ordine.imponibile)}</span>
                            </div>
                            <div className="d-flex justify-content-between">
                                <strong>Imposta</strong><span>{prezziFormatter(ordine.imposta)}</span>
                            </div>
                            <div className="d-flex justify-content-between h4">
                                <strong>Totale</strong><span>{prezziFormatter(ordine.importo)}</span>
                            </div>
                        </CardContent>
                    </Card>
                </Grid>
                {ordine.dovuto > 0 && <Grid item>
                    <Card >
                        <CardContent>
                            {ordine.meta && ordine.meta.paypal_approve_url && <PayPalButton
                                amount={ordine.dovuto}
                                createOrder={() => {

                                    let url = new URL(ordine.meta.paypal_approve_url)
                                    return url.searchParams.get('token')

                                }}
                                shippingPreference="NO_SHIPPING"
                                onSuccess={(data) => {
                                    axios.post(ordine._links.transazioni + "/paypal", data.purchase_units[0].payments.captures[0])
                                        .then(response => setOrdine(response.data))
                                        .catch(console.error)
                                }}
                            />}
                        </CardContent>
                    </Card>
                </Grid>}
                <div className="w-100" />
                <Grid item md={12}>
                    <Card >
                        <CardContent>
                            {ordine.voci ? <OrdiniVociTabella voci={ordine.voci} /> : <PreLoaderWidget />}
                        </CardContent>
                    </Card>
                </Grid>
                <Grid item md={12} /* Transazioni */ >
                    <Card  >
                        <CardContent>
                            {ordine.transazioni && <OrdiniTransazioniTabella transazioni={ordine.transazioni} />}
                        </CardContent>
                    </Card>
                </Grid>
            </Grid>
        </React.Fragment>
    )
}

export default connect(null, { setTopbarButtons, unsetTopbarButtons })(OrdiniScheda);