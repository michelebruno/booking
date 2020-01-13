/* eslint-disable react/prop-types */
import React , { useState , useEffect } from 'react'
import { connect } from 'react-redux'

import { Card, Row, Col, Badge, Button } from 'react-bootstrap'

import { setTopbarButtons , unsetTopbarButtons } from '../_actions';

import AxiosConfirmModal from '../components/AxiosConfirmModal';
import ProdottiCollegati from '../components/ProdottiCollegati'
import TariffeTabella from '../components/TariffeTabella'
import EditableField from '../components/EditableField'


const DealsScheda = ( { varianti,  location , history, ...props } ) => {

    let initialDeal = { willBeReloaded : true }

    const [deal, setDeal] = useState(initialDeal)

    const { titolo , descrizione , disponibili , iva, stato , codice } = deal || {}

    const reloadDeal = () => {
        let n = Object.assign({}, deal, {willBeReloaded: true})
        return setDeal(n)
    }

        
    const DeleteDealButton = props => {
        const [show, setShow] = useState(false)

        return <div className={props.className}>
        
            <Button size="sm" variant="danger" className="ml-1" onClick={ () => setShow(true) }>
                <i className="fas fa-trash" />{ props.children }
            </Button>

            <AxiosConfirmModal url={ deal._links.self } show={show} method="delete" onHide={() => { setShow(false); reloadDeal()}} title="Conferma" >
                Sei sicuro di cancellare questo deal?

                ATTENZIONE! I ticket associati a questi deal non saranno più riscattabili dai clienti. Se non vuoi che sia più vendibile impostalo come privato.
            </AxiosConfirmModal>
        </div>
    }


    const RestoreDealButton = props => {
        const [show, setShow] = useState(false)

        return <div className={props.className}>
        
            <Button size="sm" variant="primary" className="ml-1" onClick={ () => setShow(true) }>
                <i className="fas fa-trash-restore mr-1" /> Ripristina deal
            </Button>

            <AxiosConfirmModal url={ deal._links.restore } show={show} method="patch" onHide={() => { setShow(false); reloadDeal() }} title="Conferma" >
                Sei sicuro di voler ripristinare questo deal?
            </AxiosConfirmModal>
        </div>
    }
    const TastiDeal = ( ) => {
        if ( ! deal || ! deal._links ) return null;

        return deal.cestinato ? <RestoreDealButton></RestoreDealButton> : <DeleteDealButton><span className="ml-2 d-none d-md-inline">Elimina deal</span></DeleteDealButton>

    }

    useEffect(() => {
        props.setTopbarButtons( TastiDeal )
        return () => {
            props.unsetTopbarButtons()
        };
    }, [deal] )


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

    let editableFieldProps = deal && deal._links && { url : deal._links.self , onSuccess : ( r ) => setDeal(r) } 
  
    if ( editableFieldProps && ( ! deal || deal.willBeReloaded || deal.cestinato || ['admin' , 'account_manager'].indexOf(props.currentUser.ruolo) === -1 ) ) {
        editableFieldProps.readOnly = true
    }
    
    
    return(
        <React.Fragment>
            {deal && deal.id && <>
            <Row>
                <Col xs="12" md="6">
                    <Card>
                        <Card.Body>

                            <div>
                                <span className="h1 text-red">{titolo}</span>   
                                <span className="h3">
                                    { !deal.cestinato && <Badge variant="success" className="h3 ml-2 p-1 text-white" >Disponibilità: { disponibili }</Badge>}
                                    { deal.cestinato && <Badge variant="dark" className="h3 ml-2 p-1 text-white" >Cestinato</Badge>}
                                </span> 
                            </div>

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
                            <TariffeTabella tariffe={ deal.tariffe } url={ deal._links && deal._links.tariffe } iva={iva} onSuccess={ d => setDeal(d) } editable={ !deal.cestinato } /> 
                        </Card.Body>
                    </Card>
                </Col>
            </Row>
            <Card>
                <Card.Body>
                    <h2>Servizi collegati</h2>
                    { typeof deal.servizi !== 'undefined' && <ProdottiCollegati deal={ deal } onSuccess={ setDeal } editable={!deal.cestinato} />}
                </Card.Body>
            </Card></>}
        </React.Fragment>
    )
}

export default connect( state => { return { varianti : state.settings.varianti_tariffe , currentUser : state.currentUser } } , { setTopbarButtons, unsetTopbarButtons } )( DealsScheda );