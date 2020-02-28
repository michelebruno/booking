/* eslint-disable react/prop-types */
import React, { useState, useEffect } from 'react'

import { connect } from "react-redux"

import { Redirect } from "react-router-dom"
import Row from 'react-bootstrap/Row'
import Col from 'react-bootstrap/Col'
import Form  from 'react-bootstrap/Form'
import Card from 'react-bootstrap/Card'
import Button  from 'react-bootstrap/Button'

import { setTopbarButtons, unsetTopbarButtons } from '../_actions';

import { showErrorsFeedback , isInvalid } from '../_services/formValidation'

const FormEsercente = ( { match, location, ...props} ) => {

    const [willBeReloaded, setWillBeReloaded] = useState(true)

    const [id, setId] = useState()
    const [email, setEmail] = useState("")
    const [username, setUsername] = useState("")
    const [indirizzoVia, setIndirizzoVia] = useState("")
    const [indirizzoCivico, setIndirizzoCivico] = useState("")
    const [indirizzocitta, setIndirizzocitta] = useState("")
    const [indirizzoProvincia, setIndirizzoProvincia] = useState("")
    const [indirizzoCAP, setIndirizzoCAP] = useState("")
    const [sedeLegaleVia, setSedeLegaleVia] = useState("")
    const [sedeLegaleCivico, setSedeLegaleCivico] = useState("")
    const [sedeLegalecitta, setSedeLegalecitta] = useState("")
    const [sedeLegaleProvincia, setSedeLegaleProvincia] = useState("")
    const [sedeLegaleCAP, setSedeLegaleCAP] = useState("")
    const [cf, setCf] = useState("")
    const [ragione_sociale, setRagione_sociale] = useState("") 
    const [piva, setPiva] = useState("")
    const [nome, setNome] = useState("")
    const [sdi, setSdi] = useState("")
    const [pec, setPec] = useState("")
    
    const [api, setApi] = useState({ status: false, data: null })

    const [redirect, setRedirect] = useState(false)

    const impostaInitial = initial => {

        if ( initial ) {

            setId(initial.id) 
            setEmail(initial.email ? initial.email : "" )
            setUsername(initial.username ? initial.username : "")
    
            if ( initial.indirizzo ) {
                setIndirizzoVia(initial.indirizzo.via ? initial.indirizzo.via : "")
                setIndirizzoCivico(initial.indirizzo.civico ? initial.indirizzo.civico : "")
                setIndirizzocitta(initial.indirizzo.citta ? initial.indirizzo.citta : "")
                setIndirizzoProvincia(initial.indirizzo.provincia ? initial.indirizzo.provincia : "")
                setIndirizzoCAP(initial.indirizzo.cap ? initial.indirizzo.cap : "")
            }
    
            if ( initial.sede_legale ) {
                setSedeLegaleVia(initial.sede_legale.via ? initial.sede_legale.via : "")
                setSedeLegaleCivico(initial.sede_legale.civico ? initial.sede_legale.civico : "")
                setSedeLegalecitta(initial.sede_legale.citta ? initial.sede_legale.citta : "")
                setSedeLegaleProvincia(initial.sede_legale.provincia ? initial.sede_legale.provincia : "")
                setSedeLegaleCAP(initial.sede_legale.cap ? initial.sede_legale.cap : "")
            }
    
            setCf(initial.cf ? initial.cf : "")
            setPiva(initial.piva ? initial.piva : "")
            setNome(initial.nome ? initial.nome : "")
            setSdi(initial.sdi ? initial.sdi : "")
            setPec(initial.pec ? initial.pec : "")
            setRagione_sociale(initial.ragione_sociale ? initial.ragione_sociale : "") 

        }
    }

    useEffect( () => {

        const get = url => {

            const source = axios.CancelToken.source()
    
            axios.get(url, { cancelToken : source.token })
                .then( response => {
                    setWillBeReloaded(false)
                    impostaInitial(response.data)
                })
                .catch( error => {

                    if ( axios.isCancel(error) )  return;

                })

            return () => source.cancel();

        }

        if ( location.state && location.state.esercente ) {
            impostaInitial( location.state.esercente )
        } else if ( props.esercente ) {
            impostaInitial( props.esercente )
        }

        if ( props.shouldBeReloaded ) {
            return get('/account')
        } else if ( match.params.id ) {
            return get( '/fornitori/' + match.params.id )
        }

    }, [location.state, match.params.id ] )
    
    useEffect( () => {
        if (api.status === "submit") {
            let method, url;
            if ( match.params.id || props.esercente || ( location.state && location.state.esercente )) {
                method = "put"
                url = "/fornitori/" + id
            } else {
                method = "post"
                url = "/fornitori"
            }

            const source = axios.CancelToken.source()

            axios({
                method : method,
                url : url, 
                data : anagrafica,
                cancelToken : source.token
            })
                .then( response => {
                    let to = props.isCurrentUser ? '/account' : "/fornitori/" + response.data.id 
                    setRedirect({to, state: { success : true } })
                })
                .catch( error =>
                    setApi({status: "error", errors : error.response.data.errors })
                )
            
            return () => source.cancel()
        }
        
        return () => {
            null
        };
    }, [api.status])

    useEffect(() => {
        props.setTopbarButtons( () => <Button onClick={handleSubmit} size="sm" >Salva</Button>)
        return () => {
            props.unsetTopbarButtons()
        };
    })
    const { errors } = api;
   
    const anagrafica = {
        email,
        username,
        indirizzo: {
            via: indirizzoVia,
            civico: indirizzoCivico,
            citta: indirizzocitta,
            provincia: indirizzoProvincia,
            cap: indirizzoCAP
        },
        sede_legale: {
            via: sedeLegaleVia,
            civico: sedeLegaleCivico,
            citta: sedeLegalecitta,
            provincia: sedeLegaleProvincia,
            cap: sedeLegaleCAP
        },
        nome,
        cf,
        piva,
        sdi,
        ragione_sociale, 
        pec
    }
    const handleSubmit = e => { e.preventDefault(); setApi({status: "submit", data: api.data}) }


    return(
        <React.Fragment>
            { redirect && <Redirect to={{pathname : redirect.to , state: redirect.state }} />}
            { ( ( willBeReloaded || ! props.shouldBeReloaded ) || email ) && <>
            <h1>Crea nuovo</h1>
            <Form onSubmit={ handleSubmit }>
                <Card>
                    <Card.Body>
                        <Form.Group tag="fieldset" className="mx-lg-3 mx-xl-5">
                            <legend>Dati di accesso</legend>
                            <Form.Group as={Row} controlId="email">
                                <Form.Label column sm="2">Email</Form.Label>
                                <Col sm="10" lg="4">
                                    <Form.Control required isInvalid={isInvalid(errors, "email" )} name="email" placeholder="Email" value={ email } onChange={ e => setEmail(e.target.value)} />
                                    { showErrorsFeedback(errors, "email") }
                                </Col>
                            </Form.Group>
                            <Form.Group as={Row} controlId="username">
                                <Form.Label column sm="2">Username</Form.Label>
                                <Col sm="10" lg="4">
                                    <Form.Control required isInvalid={isInvalid(errors, "username" )} name="username" placeholder="Username" value={ username } onChange={ e => setUsername(e.target.value)} />
                                    { showErrorsFeedback(errors, "username") }
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
                                <Form.Label column sm="2">Nome al pubblico</Form.Label>
                                <Col sm="10" lg="4">
                                    <Form.Control required isInvalid={isInvalid(errors, "nome" )} value={ nome } onChange={ e => setNome(e.target.value)} />
                                </Col>
                            </Form.Group>
                            <Form.Group as={Row}>
                                <Form.Label column sm="2">Indirizzo</Form.Label>
                                <Col>
                                    <Form.Group as={Row}>
                                        <Col className="mb-2" sm="8">
                                            <Form.Control placeholder="Via" isInvalid={isInvalid(errors, "indirizzo.via" )} name="via" value={ indirizzoVia } onChange={ e => setIndirizzoVia(e.target.value)} />
                                            { showErrorsFeedback(errors, "indirizzo.via") } 
                                        </Col>
                                        <Col className="mb-2" sm="2">
                                            <Form.Control isInvalid={isInvalid(errors, "indirizzo.civico" )} id="civico"  name="civico" placeholder="Civico" value={ indirizzoCivico } onChange={ e => setIndirizzoCivico(e.target.value)} />
                                            { showErrorsFeedback(errors, "indirizzo.civico") }
                                        </Col>
                                        <Col className="mb-2" sm="3">
                                            <Form.Control size="5" maxLength="5" isInvalid={isInvalid(errors, "indirizzo.cap" )} placeholder="CAP" value={ indirizzoCAP } onChange={ e => setIndirizzoCAP(e.target.value)} />
                                            { showErrorsFeedback(errors, "indirizzo.cap") }
                                        </Col>
                                        <Col className="mb-2" sm="4">
                                            <Form.Control isInvalid={isInvalid(errors, "indirizzo.citta" )} id="citta" name="citta" placeholder="citta" value={ indirizzocitta } onChange={ e => setIndirizzocitta(e.target.value)} />
                                            { showErrorsFeedback(errors, "indirizzo.citta") }
                                        </Col>
                                        <Col className="mb-2" sm="3">
                                            <Form.Control size="2" maxLength="2"  isInvalid={isInvalid(errors, "indirizzo.provincia" )} id="provincia" name="provincia" placeholder="provincia" value={ indirizzoProvincia } onChange={ e => setIndirizzoProvincia(e.target.value)} />
                                            { showErrorsFeedback(errors, "indirizzo.provincia") }
                                        </Col>
                                    </Form.Group>
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
                                    <Form.Control required isInvalid={isInvalid(errors, "ragione_sociale" )} value={ragione_sociale} onChange={e => setRagione_sociale(e.target.value)} />
                                    { showErrorsFeedback(errors, "ragione_sociale") }
                                </Col>
                            </Form.Group>
                            <Form.Group as={Row}>
                                <Form.Label column sm="2">Sede Legale</Form.Label>
                                <Col>
                                    <Form.Group as={Row}>
                                        <Col className="mb-2" sm="8">
                                            <Form.Control placeholder="Via" isInvalid={isInvalid(errors, "sede_legale.via" )} name="via" value={ sedeLegaleVia } onChange={ e => setSedeLegaleVia(e.target.value)} />
                                            { showErrorsFeedback(errors, "sede_legale.via") } 
                                        </Col>
                                        <Col className="mb-2" sm="2">
                                            <Form.Control isInvalid={isInvalid(errors, "sede_legale.civico" )} id="civico"  name="civico" placeholder="Civico" value={ sedeLegaleCivico } onChange={ e => setSedeLegaleCivico(e.target.value)} />
                                            { showErrorsFeedback(errors, "sede_legale.civico") }
                                        </Col>
                                        <Col className="mb-2" sm="3">
                                            <Form.Control size="5" maxLength="5" isInvalid={isInvalid(errors, "sede_legale.cap" )} placeholder="CAP" value={ sedeLegaleCAP } onChange={ e => setSedeLegaleCAP(e.target.value)} />
                                            { showErrorsFeedback(errors, "sede_legale.cap") }
                                        </Col>
                                        <Col className="mb-2" sm="4">
                                            <Form.Control isInvalid={isInvalid(errors, "sede_legale.citta" )} id="citta" name="citta" placeholder="citta" value={ sedeLegalecitta } onChange={ e => setSedeLegalecitta(e.target.value)} />
                                            { showErrorsFeedback(errors, "sede_legale.citta") }
                                        </Col>
                                        <Col className="mb-2" sm="3">
                                            <Form.Control size="2" maxLength="2"  isInvalid={isInvalid(errors, "sede_legale.provincia" )} id="provincia" name="provincia" placeholder="provincia" value={ sedeLegaleProvincia } onChange={ e => setSedeLegaleProvincia(e.target.value)} />
                                            { showErrorsFeedback(errors, "sede_legale.provincia") }
                                        </Col>
                                    </Form.Group>
                                </Col>
                            </Form.Group> 
                            <Form.Row>
                                <Col sm="6"> 
                                    <Form.Group as={Row} controlId="piva">
                                        <Form.Label column sm="4" >Partita IVA</Form.Label>
                                        <Col sm="8">
                                            <Form.Control size="11" maxLength="11"  required isInvalid={isInvalid(errors, "piva" )} value={piva} onChange={ e => setPiva(e.target.value)} />
                                            { showErrorsFeedback(errors, "piva") }
                                        </Col>
                                    </Form.Group>
                                </Col>
                                <Col sm="6">
                                    <Form.Group as={Row} controlId="cf">
                                        <Form.Label column sm="4" >Codice fiscale</Form.Label>
                                        <Col sm="8">
                                            <Form.Control size="16" maxLength="16"  required isInvalid={isInvalid(errors, "cf" )} value={cf} onChange={ e => setCf(e.target.value.toUpperCase())} />
                                            { showErrorsFeedback(errors, "cf") }
                                        </Col>
                                    </Form.Group>
                                </Col>
                            </Form.Row>
                            <Form.Row>
                                <Col sm="6">
                                    <Form.Group as={Row} controlId="pec">
                                        <Form.Label column sm="4" >PEC</Form.Label>
                                        <Col sm="8">
                                            <Form.Control isInvalid={isInvalid(errors, "pec" )} type="email" value={pec} onChange={ e => setPec(e.target.value)}/>
                                            { showErrorsFeedback(errors, "pec") }
                                        </Col>
                                    </Form.Group>
                                </Col>
                                <Col sm="6">
                                    <Form.Group as={Row} controlId="SDI">
                                        <Form.Label column sm="4" >SDI</Form.Label>
                                        <Col sm="8">
                                            <Form.Control isInvalid={isInvalid(errors, "sdi" )} type="text" maxLength="7" value={sdi} onChange={ e => setSdi( e.target.value.toUpperCase() )} />
                                            { showErrorsFeedback(errors, "sdi") }
                                        </Col>
                                    </Form.Group>
                                </Col>
                            </Form.Row>
                        </Form.Group>
                    </Card.Body>
                </Card>
                <Button type="submit" >Salva</Button>
            </Form></>}
        </React.Fragment>
    )
}

export default connect(null, { setTopbarButtons, unsetTopbarButtons } )(FormEsercente);