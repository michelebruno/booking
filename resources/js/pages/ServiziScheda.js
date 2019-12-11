import React , { useState , useEffect } from 'react'
import { Card, Row, Col, Badge } from 'react-bootstrap'
import PreLoaderWidget from '../components/Loader'
import { connect } from "react-redux"
import BootstrapTable from 'react-bootstrap-table-next'

import cellEditFactory from 'react-bootstrap-table2-editor';
import EditableField from '../components/EditableField';
import upperCase from 'upper-case';
import NuovaTariffaPopover from '../components/NuovaTariffaPopover';
import ProdottiCollegati from '../components/ProdottiCollegati'
import TariffeTabella from '../components/TariffeTabella'

const ServiziScheda = ( { location , varianti , ...props} ) => {
    
    const addTariffaRef = React.useRef(null)
    const [showTariffeTooltip, setShowTariffeTooltip] = useState(false)

    let initialServizio = false;

    if ( location && location.state && location.state.servizio ) {
        initialServizio = location.state.servizio
        initialServizio.willBeReloaded = true
    }

    const [servizio, setServizio] = useState(initialServizio)

    useEffect( () => {

        if ( ! servizio || servizio.willBeReloaded ) {

            const source = axios.CancelToken.source()

            let url = ( servizio && servizio._links && servizio._links.self ) || location.pathname
            axios.get(url, { cancelToken : source.token } )
                .then( res => setServizio(res.data) )
                .catch( error =>{
                    if ( axios.isCancel(error) ) return;
                })

            return () => {
                source.cancel()
            };
        }
    }, [ servizio ] )

    const { codice , titolo , descrizione , stato , tariffe , disponibili , iva } = servizio

    let disponibiliVariant = "success" 
    if ( disponibili < 10 ) disponibiliVariant = "danger"


    let editableFieldProps = servizio && servizio._links && { url : servizio._links.self , onSuccess : ( r ) => setServizio(r) }
  
    if ( ( location && location.state && typeof location.state.esercente !== 'undefined' && location.state.esercente.id == props.currentUser.id ) || ( typeof servizio.esercente !== 'undefined' && servizio.esercente.id == props.currentUser.id ) ) {
        editableFieldProps.readOnly = true
        cellEdit = undefined
    }
    
    if ( servizio ) return(
        <React.Fragment>
            <Row>
                <Col xs="12" xl="6" >
                    <Card>
                        <Card.Body> 
                            <div className="d-flex justify-content-between">
                                <div >
                                    <span className="h1" >{titolo}</span >
                                    <br />
                                    <span className="text-muted mr-1"><strong>Codice: </strong>{codice}</span>
                                    { stato == "privato" && <Badge variant="danger" >Privato</Badge>}
                                    { stato == "bozza" && <Badge variant="light" >Bozza</Badge>}
                                    </div>   
                                <div className="h3">
                                    <Badge variant={disponibiliVariant} className="h4 p-1 text-white align-items-center" >
                                        Disponibili: { disponibili } 
                                    </Badge>
                                </div  > 
                            </div>

                            <EditableField name="titolo" label="Titolo" initialValue={titolo} { ...editableFieldProps} />
                            <EditableField name="codice" label="Codice" initialValue={codice} { ...editableFieldProps} textMutator={ str => upperCase(str) } />
                            <EditableField name="iva" label="IVA" initialValue={iva} append="%" type="number" step="1" max="100" min="0" { ...editableFieldProps} textMutator={ str => upperCase(str) } />
                            <EditableField as="textarea" name="descrizione" label="Descrizione" initialValue={descrizione} { ...editableFieldProps }  />
                            <EditableField as="select" name="stato" label="Stato" initialValue={ stato } { ...editableFieldProps }  >
                                <option value="pubblico">Pubblico</option>
                                <option value="privato">Privato</option>
                                <option value="bozza">Bozza</option>
                            </EditableField>

                            <EditableField type="number" name="disponibili" label="Disponibili" initialValue={disponibili} { ...editableFieldProps}  />
                        </Card.Body>
                    </Card>

                </Col>
                <Col xs="12" xl="6">
                    <Card>
                        <Card.Body>
                            { servizio._links && tariffe && <TariffeTabella tariffe={tariffe} url={servizio._links.tariffe} onSuccess={d => setServizio(d)} />}
                        </Card.Body>
                    </Card>
                </Col>
                <div className="w-100" />
            </Row>
            <Card>
                <Card.Body>
                    <h2>Deas e esperienze collegate</h2>
                    <ProdottiCollegati servizio={ servizio } onSuccess={ setServizio } />
                </Card.Body>
            </Card>
        </React.Fragment>
    )
    else return <PreLoaderWidget />
}

export default connect( state => { return { varianti : state.settings.varianti_tariffe , currentUser : state.currentUser } })(ServiziScheda);