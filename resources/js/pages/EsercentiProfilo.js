import React, { useState , useEffect } from 'react';

import { Row , Col , Table , Card, Nav, Button } from 'react-bootstrap';
import PreLoaderWidget from '../components/Loader';
import { Link } from "react-router-dom"

const TabellaConvalide = React.lazy( () => import( '../components/TabellaConvalide' ) );

const EsercentiProfilo = ( props ) => {
    const [api, setApi] = useState({ status : "loading", data: null })

    useEffect(() => {

        setApi( { status : "loading" , data: null})

        const source = axios.CancelToken.source()

        axios.get("/esercenti/"+ props.match.params.id, { cancelToken: source.token })
            .then( res => {
                setApi({ status : res.statusText , esercente: res.data})
            })
            .catch( error => {
                if ( axios.isCancel(error) )  return;
            })

        return () => source.cancel();

    }, [props.match.params])

    const esercente = api.status == "OK" ? api.esercente : false;

    const [ tabAttivitàAperta, setTabAttivitàAperta] = useState("convalide");

    return(
        <>
            { api.status === "loading" && <div className="p-5" ><PreLoaderWidget /></div>}
            { esercente && <><div className="d-flex justify-content-between">
                <h1>{ esercente.nome }</h1>
                <span>
                    <Button as={Link} to={ "/esercenti/" + esercente.id + "/modifica"} color="primary" size="sm" >
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
                                { esercente.indirizzo && <>
                                    { esercente.indirizzo.via && <>
                                        { esercente.indirizzo.via + " "} 
                                        { esercente.indirizzo.civico && esercente.indirizzo.civico }<br/>
                                    </>}
                                    { esercente.indirizzo.città &&  esercente.indirizzo.città } 
                                    { esercente.indirizzo.provincia &&  " (" + esercente.indirizzo.provincia + ")" }
                                    { esercente.indirizzo.cap &&  " - " + esercente.indirizzo.cap + " " }<br />
                                </> }
                                <a href={"mailto:" + esercente.email}>{esercente.email}</a><br/>
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
                                    <strong>Ragione sociale</strong><span>{ esercente.meta.ragione_sociale }</span>
                                </span>
                                <span className="d-flex justify-content-between">
                                    <strong>Sede legale</strong><span>Ciccio pasticcio snc</span>
                                </span>
                                <span className="d-flex justify-content-between">
                                    <strong>P.IVA</strong><span>{ esercente.piva }</span>
                                </span>
                                <span className="d-flex justify-content-between">
                                    <strong>C.F</strong><span>{ esercente.cf }</span>
                                </span>
                                <span className="d-flex justify-content-between">
                                    <strong>SDI</strong><span>{ esercente.sdi ? esercente.sdi : " - "}</span>
                                </span>
                                <span className="d-flex justify-content-between">
                                    <strong>PEC</strong><span>{ esercente.pec ? esercente.pec : " - " }</span>
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
            </Row></>
            }
        </>
    )
}

export default EsercentiProfilo;