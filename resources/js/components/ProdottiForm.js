import React , { useState } from "react"

import PropTypes from 'prop-types'

import { Link } from 'react-router-dom'
import Alert from "react-bootstrap/Alert"
import Form from "react-bootstrap/Form"
import Button from "react-bootstrap/Button"
import InputGroup from "react-bootstrap/InputGroup"
import Row from "react-bootstrap/Row"
import Col from "react-bootstrap/Col"

import { showErrorsFeedback , isInvalid } from "../_services/formValidation";

const ProdottiForm = ( props ) => {
    const [api, setApi] = useState({ status : false })
    const [titolo, setTitolo] = useState("")
    const [customCod, setCustomCod] = useState(false);
    const [codice, setCodice] = useState("")
    const [descrizione, setDescrizione] = useState("")
    const [prezzo, setPrezzo] = useState( "" )
    const [prezzoII, setPrezzoII] = useState( "" )
    const [modIVAinc, setModIVAinc] = useState( false )
    const [IVA, setIVA] = useState(22)
    const [stato, setStato] = useState("pubblico")
    const [disponibili, setDisponibili] = useState(0)

 
    let data = {
        titolo,
        descrizione,
        stato,
        codice,
        codice_personalizzato : ! customCod,
        iva: IVA,
        disponibili
    }

    if ( modIVAinc ) { 
        data.tariffe = {
            intero: {
                importo: Number.parseFloat( prezzoII ).toFixed(2)
            }
        } 
    } else data.tariffe = {
        intero: {
            imponibile: Number.parseFloat( prezzo ).toFixed(2)
        }
    }

    const errors  = api.errors 

    const created = ( api.status == "Created" && api.data ) || false 
    
    const dynamicProps = field => { 
        let props = {}

        if (api.status === "OK" || api.status === "sending" || created ) {
            return { readOnly : true }
        }

        props.isInvalid = isInvalid(errors, field)

        return props
    }
    
    const source = axios.CancelToken.source()

    React.useEffect( () => source.cancel , [source.cancel] )

    const handleSubmit = ( e ) => {

        e.preventDefault()
        
        setApi({ status : "sending" })

        axios({
            method: 'post',
            url : props.url ? props.url : '/servizi' ,
            data,
            cancelToken: source.token
        })
            .then( res => {
                setApi({ status : "Created" , data : res.data })
                setCodice( res.data.codice )
                props.onSuccess()
            })
            .catch( error => {
                if ( axios.isCancel(error) ) return;

                if ( error.response ) {
                    if ( error.response.status == 422 ) setApi( { status: "invalid" , errors : error.response.data.errors} )

                }

            })
    }

    return <Form onSubmit={ handleSubmit } >
        { customCod && <Form.Group as={ Row } controlId="cod" >
            <Form.Label column xs="12" md="4">Codice</Form.Label>
            <Col xs="12" md="8"  >
                <Form.Control value={codice} onChange={ e => setCodice(e.target.value)} className="my-1" required  {...dynamicProps( 'codice' ) }/>
                { showErrorsFeedback( errors , 'codice' )}
            </Col>
        </Form.Group> }

        { !created && <Form.Group as={Row} controlId="customCod">
            <Col xs="8" md={ { offset : 4 , span : 8 }}>
                <Form.Check className={ "align-self-center" } type="switch" checked={!customCod} label="Assegna codice prodotto automaticamente" onChange={ () => setCustomCod(!customCod) } />
            </Col>
        </Form.Group> }

        <Form.Group as={ Row } controlId="titolo" >
            <Form.Label column xs="12" md="4">Titolo</Form.Label>
            <Col xs="12" md="8">
                <Form.Control value={titolo} onChange={ e => setTitolo(e.target.value) } required  {...dynamicProps( 'titolo' ) }/>
                { showErrorsFeedback( errors , 'titolo')}
            </Col>
        </Form.Group>

        <Form.Group as={ Row } controlId="descrizione" >
            <Form.Label column xs="12" md="4">Descrizione</Form.Label>
            <Col xs="12" md="8">
                <Form.Control as="textarea" value={descrizione} onChange={ e => setDescrizione(e.target.value) }  {...dynamicProps( 'descrizione' ) }/>
                { showErrorsFeedback( errors , 'descrizione' )}
            </Col>
        </Form.Group>

        <Form.Group as={ Row } controlId="stato" >
            <Form.Label column xs="12" md="4">Stato</Form.Label>
            <Col xs="12" md="8">
                <Form.Control as="select" value={stato} onChange={ e => setStato(e.target.value) } {...dynamicProps( 'stato' ) } >
                    <option value="pubblico">Pubblico</option>
                    <option value="privato">Privato</option>
                    <option value="bozza">Bozza</option>
                </Form.Control>                    
                
                { showErrorsFeedback( errors , 'stato' )}
            </Col>
        </Form.Group>

        <Form.Group as={ Row } controlId="disponibili" >
            <Form.Label column xs="12" md="4">Disponibili</Form.Label>
            <Col xs="12" md="8">
                <Form.Control type="number" value={disponibili} onChange={ e => setDisponibili(e.target.value) }  {...dynamicProps( 'disponibili' ) }/>
                { showErrorsFeedback( errors , 'disponibili' )}
            </Col>
        </Form.Group>

        <Form.Group as={ Row } controlId="prezzo" required >
            <Form.Label column xs="12" md="4">Tariffa base
                <Form.Group controlId="modIvainc">
                    <Form.Check size="sm" className={ " my-1" } type="switch" label={ <span className="text-muted" >Iva { modIVAinc ? "inclusa" : "esclusa" }</span>} checked={!modIVAinc} onChange={ () => { setPrezzoII(""); setPrezzo(""); setModIVAinc(!modIVAinc)} } />
                </Form.Group>
            </Form.Label>
            <Col xs="12" md="8">
                <InputGroup>
                    <InputGroup.Prepend>
                        <InputGroup.Text>€</InputGroup.Text>
                    </InputGroup.Prepend>
                    { modIVAinc ? 
                        <Form.Control value={ prezzoII } onChange={ e => setPrezzoII( e.target.value ) }  {...dynamicProps( 'prezzo' ) }/> 
                        : <Form.Control value={ prezzo } onChange={ e => setPrezzo( e.target.value ) }  {...dynamicProps( 'prezzo' ) }/> }
                </InputGroup>
                { showErrorsFeedback( errors , 'prezzo' )}
                    { modIVAinc ? <Form.Text className="text-muted">€ { ( prezzoII / ( 1 + IVA / 100 )  ).toFixed(2) || 0 } iva esclusa </Form.Text> : <Form.Text className="text-muted">€ { ( prezzo * ( 1 + IVA / 100 ) ).toFixed(2) || 0 } iva inclusa </Form.Text> }
            </Col>
        </Form.Group>

        <Form.Group as={ Row } controlId="prezzoiva" required >
            <Form.Label column xs="12" md="4">Iva</Form.Label>
            <Col xs="12" md="8">
                <InputGroup>
                    <Form.Control value={IVA} onChange={ e => setIVA(e.target.value) } type="number" step="1" max="100" min="0" {...dynamicProps( 'iva' ) }/>
                    <InputGroup.Append>
                        <InputGroup.Text>%</InputGroup.Text>
                    </InputGroup.Append>
                </InputGroup>
                { showErrorsFeedback( errors , 'iva')}
            </Col>
        </Form.Group>
        { created && <Alert variant="success" >Prodotto creato con codice <Link to={ { pathname : created._links.self } } >{codice}</Link>.</Alert>}
        <Button type="submit" { ...{ disabled: api.status === "sending" || api.status === "Created" }} >Invia</Button>
    </Form>
}
ProdottiForm.propTypes = {
    url : PropTypes.string,
    onSuccess : PropTypes.func
}
export default ProdottiForm;