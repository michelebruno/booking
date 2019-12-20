import React , { useState , useEffect } from 'react'
import { Card, Row, Col, Image, Badge, Button } from 'react-bootstrap'
import { Link  , Redirect} from "react-router-dom"
import BootstrapTable from 'react-bootstrap-table-next'

import { connect } from "react-redux"
import NuovaTariffaPopover from '../components/NuovaTariffaPopover'
import ProdottiCollegati from '../components/ProdottiCollegati'
import TariffeTabella from '../components/TariffeTabella'
import EditableField from '../components/EditableField'


const DealsScheda = ( { varianti,  location , match , history, ...props } ) => {

    let initialDeal = { willBeReloaded : true }


    const [deal, setDeal] = useState(initialDeal)

    const { titolo , descrizione , disponibili , iva, stato , codice } = deal || {}

    let varianti_disponibili = Object.assign({}, varianti)

    if ( varianti && deal && deal.tariffe ) { Object.keys(deal.tariffe).map( variante => {
            let keys = Object.keys(varianti) 
            let pos = keys.findIndex( ( value ) => value == variante ) 
            pos !== -1 && delete varianti_disponibili[keys[pos]]
        })
    }
    useEffect(() => {
        if ( ! deal || deal.willBeReloaded ) {

            const source = axios.CancelToken.source()
            let url = ( ( deal && deal._links ) && deal._links.self ) || location.pathname

            axios.get( url , { cancelToken : source.token })
                .then( res => setDeal(res.data))
                .catch( error => {
                    if ( axios.isCancel(error) ) return;
                    console.error(error)
                    return error
                } )

            return () => {
                source.cancel()
            };

        }
    }, [deal])

    const thumbnail = {
        url : 'http://www.turismo.bologna.it/wp-content/uploads/2019/09/una-inclinata-laltra-di-più-le-torri-asinelli-e-garisenda.jpg',
        alt : 'Vista qualsiasi di Bologna',
        title : 'Vista qualsiasi di Bologna'
    }


    let editableFieldProps = deal && deal._links && { url : deal._links.self , onSuccess : ( r ) => setDeal(r) } 
  
    if ( editableFieldProps && ( ! deal || deal.willBeReloaded || ['admin' , 'account_manager'].indexOf(props.currentUser.ruolo) === -1 ) ) {
        editableFieldProps.readOnly = true
    }
    
    
    return(
        <React.Fragment>
            {deal && deal.id && <>
            <Row>
                <Col xs="12" md="6">
                    <Card>
                        <Card.Body>

                            <div><span className="h1 text-red">{titolo}</span>   <span className="h3"><Badge variant="success" className="h3 ml-2 p-1 text-white" >Disponibilità: { disponibili }<i className="fas fa-edit" /></Badge></span  > </div>

                            <EditableField name="titolo" label="Titolo" initialValue={titolo} { ...editableFieldProps} />
                            <EditableField name="codice" label="Codice" initialValue={codice} { ...editableFieldProps}  textMutator={ str => str.toUpperCase() } />
                            <EditableField name="iva" label="IVA" initialValue={iva} append="%" type="number" step="1" max="100" min="0" { ...editableFieldProps } onSuccess={ d => { setDeal(d); history.replace(d._links.self) }} textMutator={ str => upperCase(str) } />
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
                <Col xs="12" md="6">
                    <Card>
                        <Card.Body>
                            <TariffeTabella tariffe={ deal.tariffe } url={ deal._links && deal._links.tariffe } onSuccess={ d => setDeal(d) }/* TODO editable */ /> 
                        </Card.Body>
                    </Card>
                </Col>
            </Row>
            <Card>
                <Card.Body>
                    <h2>Servizi collegati</h2>
                    { typeof deal.servizi !== 'undefined' && <ProdottiCollegati deal={ deal } onSuccess={ setDeal } />}
                </Card.Body>
            </Card></>}
        </React.Fragment>
    )
}

export default connect( state => { return { varianti : state.settings.varianti_tariffe , currentUser : state.currentUser } } )( DealsScheda );