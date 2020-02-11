/* eslint-disable react/display-name */
import React , { useState , useEffect } from "react"
import PropTypes from 'prop-types'

import BootstrapTable from "react-bootstrap-table-next"
import Button from 'react-bootstrap/Button';
import { Link } from "react-router-dom"

import AxiosConfirmModal from '../components/AxiosConfirmModal';


const ServiziTabella = ( { readOnly , url , ... props } ) => {
    
    const [api, setApi] = useState({ willBeReloaded : true , fetchedFrom: undefined, servizi : props.servizi })

    const [ fetchUrl, setFetchUrl ] = useState(url)

    const servizi = api.servizi || [];

    useEffect(() => {
        
        if ( api.willBeReloaded || api.fetchedFrom !== fetchUrl ) {

            const source = axios.CancelToken.source()

            axios.get(fetchUrl, { cancelToken : source.token })
                .then(response => setApi({ willBeReloaded : false , fetchedFrom : fetchUrl , servizi : response.data }))
    
            return () => {
                source.cancel()
            };

        }
    }, [ fetchUrl , api ] )

    let esercente = false

    const reloadApi = () => {
        let n = Object.assign({}, api, {willBeReloaded: true})
        return setApi(n)
    }

    const DeleteServizioButton = props => {
        const [show, setShow] = useState(false)

        return <div className={props.className}>
        
            <Button variant="danger" className="ml-1" onClick={ () => setShow(true) }>
                <i className="fas fa-trash" />
            </Button>

            <AxiosConfirmModal url={ props.url } show={show} method="delete" onHide={() => { setShow(false); reloadApi()}} title="Conferma" >
                Sei sicuro di cancellare questo servizio?
            </AxiosConfirmModal>
        </div>
    }
    const RestoreServizioButton = props => {
        const [show, setShow] = useState(false)

        return <div className={props.className}>
        
            <Button variant="danger" className="ml-1" onClick={ () => setShow(true) }>
                <i className="fas fa-undo" />
            </Button>

            <AxiosConfirmModal url={ props.url } show={show} method="patch" onHide={() => { setShow(false); reloadApi()}} title="Conferma" >
                Sei sicuro di voler ripristinare questo servizio?
            </AxiosConfirmModal>
        </div>
    }

    const actionButtons = ( cell , row ) => {
        return <>
            <Button as={ Link } to={ { pathname : row._links.self , state : { servizio : row , esercente : esercente }}} variant="success">
                { readOnly || row.cestinato ? <i className="fas fa-eye" /> : <i className="fas fa-edit" />   }
            </Button>
            { ! readOnly && !row.cestinato && <DeleteServizioButton url={ row._links.self } className="d-none d-md-inline-block" />}
            { ! readOnly && row.cestinato && <RestoreServizioButton url={ row._links.restore } method="patch" className="d-none d-md-inline-block" />}
        </>
    }

    return (
    <>
        <div className="d-flex align-content-between">

            <div>
                <Button className="mx-1" size="sm" variant="link" onClick={ () => setFetchUrl(url) }>Tutti</Button>|
                <Button className="mx-1" size="sm" variant="link" onClick={() => setFetchUrl(fetchUrl+"?only_trashed=true")}>Cestino</Button>
            </div>
        </div>
        <BootstrapTable 
            data={ servizi }
            keyField="id"
            columns={[
                {
                    dataField: 'stato',
                    text : "",
                    // eslint-disable-next-line react/display-name
                    formatter: ( _cell , row ) => {
                        if ( row.cestinato ) return <i className="fas fa-circle text-dark fa-lg" title="Cestinato" />

                        switch (row.stato) {
                            case "pubblico":
                                return <i className="fas fa-circle text-success fa-lg" title="Pubblico" />
                                // eslint-disable-next-line no-unreachable
                                break;
                        
                            case "privato":
                                return <i className="fas fa-circle text-secondary fa-lg" title="Privato" />
                                // eslint-disable-next-line no-unreachable
                                break;
                        }
                    }
                },
                {
                    dataField : 'codice',
                    text : 'Cod.'
                },
                {
                    dataField : 'titolo',
                    text : 'Titolo',
                    formatter: ( cell , row ) => <span title={ row.descrizione }>{cell}</span>
                },
                {
                    dataField : 'tariffe.intero.imponibile',
                    text : 'Imponibile (prezzo intero)',
                    formatter : ( cell ) => "€ " + cell || "-",
                    classes : "d-none d-md-table-cell",
                    headerClasses : "d-none d-md-table-cell",
                },
                {
                    dataField : 'iva',
                    text : 'IVA',
                    formatter : ( cell ) => cell + "%",
                    classes : "d-none d-md-table-cell",
                    headerClasses : "d-none d-md-table-cell",
                },
                {
                    dataField : 'disponibili',
                    text : 'Disponibilità',
                    classes : "d-none d-md-table-cell",
                    headerClasses : "d-none d-md-table-cell",
                },
                {
                    dataField : 'modifica',
                    text : '',
                    formatter : actionButtons
                }
            ] }
            bordered={ false }
            hover
            wrapperClasses="table-responsive"
            noDataIndication="Nessun servizio per questo esercente."
            />
        </>)
}
ServiziTabella.propTypes = {
    className : PropTypes.string,
    readOnly : PropTypes.bool,
    servizi : PropTypes.array,
    url : PropTypes.string
}
export default ServiziTabella