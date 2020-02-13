/* eslint-disable react/prop-types */
import React , { useState , useEffect } from 'react'
import PropTypes from 'prop-types'

import { Card, Col } from 'react-bootstrap'
import { Row } from 'react-bootstrap'
import MUIDataTable from 'mui-datatables'

import PreLoaderWidget from '../components/Loader'
import AxiosConfirmModal from '../components/AxiosConfirmModal'

const ClientiScheda = ( { match } ) => {

    const [cliente, setCliente] = useState()
    const [permanentDeleteAxiosShow, setPermanentDeleteAxiosShow] = useState(false)
    
    useEffect(() => {
        if ( cliente && ! cliente.willBeReloaded ) return;
        const source = axios.CancelToken.source()

        let url = cliente ? cliente._links.self : '/clienti/' + match.params.cliente_id

        axios.get( url , { cancelToken : source.token } )
            .then( res => setCliente(res.data) )
            .catch ( e => e )

        return () => {
            source.cancel()
        };
    }, [cliente])

    if ( ! cliente ) return <PreLoaderWidget />

    const handleDeleteAccountPermanently = e => {
        e.preventDefault()
        setPermanentDeleteAxiosShow(true)
    }

    return <>
        <Row>
            <Col sm="12" md="6" lg="4">
                <Card>
                    <Card.Body>
                    </Card.Body>
                </Card> 
            </Col>
            <Col sm="12" md="6" lg="6">
                <Card>
                    <Card.Body>
                        <h2>Ordini</h2>
                        { cliente.ordini && <MUIDataTable
                            data={cliente.ordini}
                            columns={[
                                {
                                    name : "id",
                                    label : "Id"
                                },
                                {
                                    name : "importo",
                                    label : "Importo",
                                }
                            ]}
                            options={{
                                elevation : 0
                            }}
                            />}
                        
                    </Card.Body>
                </Card> 
            </Col>
            <Col sm="12" md="6" lg="4">
                <Card>
                    <Card.Body>
                        <h3>Azioni</h3>
                        <ul>
                            <li><a href="" onClick={ handleDeleteAccountPermanently }>Elimina account (inclusi dati personali)</a></li>
                        </ul>
                        <AxiosConfirmModal show={permanentDeleteAxiosShow} url={cliente._links.forceDelete} onHide={ () => setPermanentDeleteAxiosShow(false) } >Sei sicuro di voler cancellare definitivamente questo utente?</AxiosConfirmModal>
                    </Card.Body>
                </Card>
            </Col>

        </Row>
    </>
}

export default ClientiScheda