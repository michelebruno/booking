/* eslint-disable react/prop-types */
import React, { useState, useEffect } from 'react';

import { connect } from 'react-redux'

import Row from 'react-bootstrap/Row';
import Col from 'react-bootstrap/Col';
import Button from "@material-ui/core/Button"

import EditIcon from '@material-ui/icons/Edit';

import ButtonToolbar from 'react-bootstrap/ButtonToolbar';
import Alert from 'react-bootstrap/Alert';
import Form from 'react-bootstrap/Form';
import Badge from 'react-bootstrap/Badge';
import Modal from 'react-bootstrap/Modal';
import PreLoaderWidget from '../components/Loader';
import { Link } from "react-router-dom"
import { setTopbarButtons, unsetTopbarButtons } from '../_actions';
import AxiosConfirmModal from '../components/AxiosConfirmModal';
import EditableField from '../components/EditableField';
import ServiziTabella from '../components/ServiziTabella';

import Card from '@material-ui/core/Card';
import CardContent from '@material-ui/core/CardContent';

const ServizioForm = React.lazy(() => import('../components/ServizioForm'));

const EsercentiProfilo = ({ location, match, isCurrentUser, ...props }) => {

    const [tabAttivitàAperta, setTabAttivitàAperta] = useState("convalide");

    const [servizioModal, setServizioModal] = useState(false)

    let initialApiState = { status: "loading" }

    if (location.state && location.state.esercente) initialApiState = { willBeReloaded: true, status: "OK", esercente: location.state.esercente }
    else if (props.esercente) initialApiState = { willBeReloaded: true, status: "OK", esercente: props.esercente }

    const [api, setApi] = useState(initialApiState)

    const { esercente } = api

    const TastiProfiloEsercente = ({ className }) => {

        const [showModal, setShowModal] = useState(false)

        const deleteProfile = () => {
            setShowModal(true)
        }

        const { abilitato } = esercente ? esercente : false

        return <>
            {!(api.willBeReloaded || api.status === "loading") && esercente && <ButtonToolbar className="d-inline-block">

                <Form className={className} >
                    {esercente._links &&
                        <Button component={Link} to={{ pathname: esercente._links.edit, state: { esercente } }} color="primary" variant="contained" size="small" startIcon={<EditIcon />} >
                            Modifica
                        </Button>
                    }
                    {!isCurrentUser && <Form.Check type="switch" className="d-inline-block mx-3" name="abilitato" id="abilitato" label={esercente.abilitato ? "Abilitato" : "Disabilitato"} onChange={deleteProfile} checked={esercente.abilitato} />}
                </Form>


                <AxiosConfirmModal
                    method={abilitato ? "delete" : "patch"}
                    show={showModal}
                    onHide={() => setShowModal(false)}
                    url={abilitato ? esercente._links.delete : esercente._links.restore}
                    onSuccess={(res) => { setShowModal(false); return setApi({ willBeReloaded: false, status: res.statusText, esercente: res.data }); }}
                    title="Conferma" >
                    Sei sicuro di voler modificare?<br />
                        Essere abilitati permette di :
                        <ul>
                        <li>Loggarsi</li>
                        <li>Effettuare convalide</li>
                        <li>Inserire fatture</li>
                    </ul>
                </AxiosConfirmModal>

            </ButtonToolbar>}


        </>
    }

    const reloadApi = () => {
        let n = Object.assign({}, api, { willBeReloaded: true });
        return setApi(n)
    }

    useEffect(() => {

        if (api.status === "loading" || api.willBeReloaded) {

            let id = match.params.id || (api.status == "OK" && api.esercente.id)

            const source = axios.CancelToken.source()

            axios.get("/fornitori/" + id, { cancelToken: source.token })
                .then(res => {
                    return setApi({ willBeReloaded: false, status: res.statusText, esercente: res.data })
                })
                .catch(error => {
                    if (axios.isCancel(error)) return;
                    // TODO gli altri errori per cui bisognerebbe cambiare pagina
                    if (error.response) {
                        // è un errore del server
                    } else {
                        // è un eccezione di javascript
                    }

                })

            return () => {
                source.cancel()
            }

        }

    }, [api, match.params.id])

    useEffect(() => {
        props.setTopbarButtons(TastiProfiloEsercente)
        return () => {
            props.unsetTopbarButtons()
        };
    })
    return (<>
        {api.status === "loading" && <div className="p-5" ><PreLoaderWidget /></div>}

        {api.status !== "loading" && esercente && <>

            <div className="d-flex justify-content-between">

                <h1>{esercente.nome} {!esercente.abilitato && <Badge variant="primary" pill >Disabilitato</Badge>} </h1>
                {isCurrentUser && <span className="d-md-none align-self-center">
                    <Button component={Link} to={{ pathname: '/account/modifica', state: { esercente } }} color="primary" variant="contained" size="small" startIcon={EditIcon} >
                        Modifica profilo
                    </Button>
                </span>}
            </div>
            <div className="w-100" />
            {!isCurrentUser && <span className="d-md-none m-2">
                <TastiProfiloEsercente />
            </span>}

            <Row>
                {location.state && location.state.success && <Col lg="4">
                    <Alert variant="success" >I dati sono stati aggiornati</Alert>
                </Col>}
                <div className="w-100"></div>

                <Col xs="12" lg="4" xl="3">
                    <Card>
                        <CardContent>
                            <strong>Email</strong><br />
                            {esercente.email}<br /><br />
                            <strong>Username</strong><br />
                            {esercente.username}<br /><br />
                            {esercente.indirizzo && <>
                                <strong>Indirizzo</strong><br />
                                {esercente.indirizzo.via && <>
                                    {esercente.indirizzo.via + " "}
                                    {esercente.indirizzo.civico && esercente.indirizzo.civico}<br />
                                </>}
                                {esercente.indirizzo.citta && esercente.indirizzo.citta}
                                {esercente.indirizzo.provincia && " (" + esercente.indirizzo.provincia + ")"}
                                {esercente.indirizzo.cap && " - " + esercente.indirizzo.cap + " "}<br />
                            </>}
                        </CardContent>
                    </Card>
                </Col>
                <Col xs="12" lg="4" xl="3">
                    <Card>
                        <CardContent>
                            <h4>Dati di fatturazione</h4>

                            {esercente.ragione_sociale && <span className="d-flex justify-content-between">
                                <strong>Ragione sociale</strong><span>{esercente.ragione_sociale}</span>
                            </span>}
                            {esercente.sede_legale && <>
                                <strong>Sede Legale</strong><br />
                                {esercente.sede_legale.via && <>
                                    {esercente.sede_legale.via + " "}
                                    {esercente.sede_legale.civico && esercente.sede_legale.civico}<br />
                                </>}
                                {esercente.sede_legale.citta && esercente.sede_legale.citta}
                                {esercente.sede_legale.provincia && " (" + esercente.sede_legale.provincia + ")"}
                                {esercente.sede_legale.cap && " - " + esercente.sede_legale.cap + " "}<br />
                            </>}
                            <span className="d-flex justify-content-between">
                                <strong>P.IVA</strong><span>{esercente.piva}</span>
                            </span>
                            <span className="d-flex justify-content-between">
                                <strong>C.F</strong><span>{esercente.cf}</span>
                            </span>
                            {esercente.sdi && <span className="d-flex justify-content-between">
                                <strong>SDI</strong><span>{esercente.sdi ? esercente.sdi : " - "}</span>
                            </span>}
                            {esercente.pec && <span className="d-flex justify-content-between">
                                <strong>PEC</strong><span>{esercente.pec ? esercente.pec : " - "}</span>
                            </span>}
                        </CardContent>
                    </Card>
                </Col>
                {!isCurrentUser && esercente.note && <Col xs="6" lg="4" xl="3">
                    <Card>
                        <CardContent>
                            <h4 className="d-inline-block">Note</h4>
                            <small className="text-muted"> Non visibile all&apos;esercente</small>
                            <EditableField name="note" noLabel initialValue={esercente.note} url={esercente._links.self + "/note"} method="patch" as="textarea" />
                        </CardContent>
                    </Card>
                </Col>}
                <Col xs="12" lg="4" xl="3">
                    <Card>
                        <CardContent>
                            <h2>Azioni richieste</h2>
                            <ul>
                                <li>Approva la descrizione</li>
                                <li>Inserisci le fatture di fine mese</li>
                            </ul>
                        </CardContent>
                    </Card>
                </Col>
            </Row>
            <Row>
                <Col xs="12">
                    <Card>
                        <CardContent>
                            <div className="d-flex justify-content-between">

                                <h2></h2>

                                <span>
                                    {!isCurrentUser && <Button variant="outlined" color="default" size="small" onClick={() => setServizioModal({ isNew: true })} >Aggiungi una fornitura</Button>}
                                </span>

                                {servizioModal !== false && <Modal show={true} onHide={() => setServizioModal(false)} >
                                    <Modal.Header closeButton>
                                        <Modal.Title>Aggiungi una nuova fornitura</Modal.Title>
                                    </Modal.Header>
                                    <Modal.Body>
                                        <React.Suspense>
                                            <ServizioForm {...servizioModal} url={esercente._links.forniture} onSuccess={() => {
                                                setTimeout(() => {
                                                    reloadApi()
                                                    setServizioModal(false)
                                                }, 3000);
                                            }} />
                                        </React.Suspense>
                                    </Modal.Body>
                                </Modal>}
                            </div>
                            {esercente.forniture && <ServiziTabella title="Forniture" servizi={esercente.forniture} url={esercente._links.forniture} esercente={esercente} editable={!isCurrentUser} />}
                        </CardContent>
                    </Card>
                </Col>
            </Row></>
        }
    </>
    )
}

export default connect(null, { setTopbarButtons, unsetTopbarButtons })(EsercentiProfilo);