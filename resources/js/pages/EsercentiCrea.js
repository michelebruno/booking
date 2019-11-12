import React, { useState, useEffect } from 'react'

import { Redirect } from "react-router-dom"
import Row from 'react-bootstrap/Row'
import Col from 'react-bootstrap/Col'
import Form  from 'react-bootstrap/Form'
import InputGroup from 'react-bootstrap/InputGroup'
import Card from 'react-bootstrap/Card'
import Button  from 'react-bootstrap/Button'

import { showErrorsFeedback , isInvalid } from '../_services/formValidation'

const FormEsercente = ( { id, match, ...props} ) => {
    
    const [initial, setInitial] = useState( match.params.id ? false : null )

    const [email, setEmail] = useState("")
    const [username, setUsername] = useState("")
    const [indirizzoVia, setIndirizzoVia] = useState("")
    const [indirizzoCivico, setIndirizzoCivico] = useState("")
    const [indirizzoCittà, setIndirizzoCittà] = useState("")
    const [indirizzoProvincia, setIndirizzoProvincia] = useState("")
    const [indirizzoCAP, setIndirizzoCAP] = useState("")
    const [cf, setCf] = useState("")
    const [ragione_sociale, setRagione_sociale] = useState("")
    const [sede_legale, setSede_legale] = useState("")
    const [piva, setPiva] = useState("")
    const [nome, setNome] = useState("")
    const [sdi, setSdi] = useState("")
    const [pec, setPec] = useState("")

    window.initial = initial

    useEffect(() => {

        const source = axios.CancelToken.source()
        if ( ! match.params.id ) return;
        axios.get("/esercenti/" + match.params.id, { cancelToken : source.token })
            .then( response => {
                setInitial(response.data)

                const { data } = response
                
                setEmail(data.email)
                setUsername(data.username)
                setIndirizzoVia(data.indirizzo.via)
                setIndirizzoCivico(data.indirizzo.civico)
                setIndirizzoCittà(data.indirizzo.città)
                setIndirizzoProvincia(data.indirizzo.provincia)
                setIndirizzoCAP(data.indirizzo.cap)
                setCf(data.cf)
                setPiva(data.piva)
                setNome(data.nome)
                setSdi(data.sdi)
                setPec(data.pec)
                setRagione_sociale(data.ragione_sociale)
                setSede_legale(data.sede_legale)
                
            })
            .catch( error => {
                if ( axios.isCancel(error) )  return;
            })
        return () => source.cancel();

    }, [match.params])
    
    const [api, setApi] = useState({status: false, data:null})

    const [redirect, setRedirect] = useState(false)

    const { errors } = api;
   
    const anagrafica = {
        email,
        username,
        indirizzo: {
            via: indirizzoVia,
            civico: indirizzoCivico,
            città: indirizzoCittà,
            provincia: indirizzoProvincia,
            CAP: indirizzoCAP
        },
        nome,
        cf,
        piva,
        sdi,
        ragione_sociale,
        sede_legale,
        pec
    }

    useEffect( () => {
        if (api.status === "submit") {
            let method, url;
            if ( match.params.id ) {
                method = "put"
                url = "/esercenti/" + match.params.id
            } else {
                method = "post"
                url = "/esercenti"
            }

            axios({
                method : method,
                url : url, 
                data : anagrafica
            })
                .then( response => {
                    setRedirect("/esercenti/" + response.data.id)
                })
                .catch( error =>
                    setApi({status: "error", errors : error.response.data.errors })
                )
        }
        
        return () => {
            null
        };
    }, [api.status])

    return(
        <React.Fragment>
            { redirect && <Redirect to={redirect} />}
            { initial !== false && <>
            <h1>Crea nuovo</h1>
            <Form onSubmit={  e => { e.preventDefault(); setApi({status: "submit", data: api.data})}}>
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
                                            <InputGroup>
                                                <InputGroup.Prepend>
                                                    <InputGroup.Text >Via</InputGroup.Text>
                                                </InputGroup.Prepend>
                                                <Form.Control isInvalid={isInvalid(errors, "indirizzo.via" )} name="via" value={ indirizzoVia } onChange={ e => setIndirizzoVia(e.target.value)} />
                                                { showErrorsFeedback(errors, "indirizzo.via") } 
                                            </InputGroup>
                                        </Col>
                                        <Col className="mb-2" sm="2">
                                            <Form.Control isInvalid={isInvalid(errors, "indirizzo.civico" )} id="civico"  name="civico" placeholder="Civico" value={ indirizzoCivico } onChange={ e => setIndirizzoCivico(e.target.value)} />
                                            { showErrorsFeedback(errors, "indirizzo.civico") }
                                        </Col>
                                        <Col className="mb-2" sm="3">
                                            <Form.Control size="5" maxLength="5" isInvalid={isInvalid(errors, "indirizzo.cap" )} placeholder="CAP" value={ indirizzoCAP } onChange={ e => setIndirizzoCAP(e.target.value)} />
                                            { showErrorsFeedback(errors, "email") }
                                        </Col>
                                        <Col className="mb-2" sm="4">
                                            <Form.Control isInvalid={isInvalid(errors, "indirizzo.città" )} id="città" name="Città" placeholder="Città" value={ indirizzoCittà } onChange={ e => setIndirizzoCittà(e.target.value)} />
                                            { showErrorsFeedback(errors, "indirizzo") }
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
                            <Form.Group as={Row} controlId="sedelegale">
                                <Form.Label column sm="2" >Sede legale</Form.Label>
                                <Col sm="10">
                                    <Form.Control required isInvalid={isInvalid(errors, "sede_legale" )} value={sede_legale} onChange={ e => setSede_legale(e.target.value)} />
                                    { showErrorsFeedback(errors, "sede_legale") }
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
                                            <Form.Control size="16" maxLength="16"  required isInvalid={isInvalid(errors, "cf" )} value={cf} onChange={ e => setCf(e.target.value)} />
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
                                            <Form.Control isInvalid={isInvalid(errors, "pec" )} value={pec} onChange={ e => setPec(e.target.value)}/>
                                            { showErrorsFeedback(errors, "pec") }
                                        </Col>
                                    </Form.Group>
                                </Col>
                                <Col sm="6">
                                    <Form.Group as={Row} controlId="SDI">
                                        <Form.Label column sm="4" >SDI</Form.Label>
                                        <Col sm="8">
                                            <Form.Control isInvalid={isInvalid(errors, "sdi" )} value={sdi} onChange={ e => setSdi(e.target.value)} />
                                            { showErrorsFeedback(errors, "sdi") }
                                        </Col>
                                    </Form.Group>
                                </Col>
                            </Form.Row>
                        </Form.Group>
                    </Card.Body>
                </Card>
                <Button type="submit" >Invia</Button>
            </Form></>}
        </React.Fragment>
    )
}

export default FormEsercente;