/* eslint-disable react/prop-types */
import React , { useState , useEffect } from 'react'
import { Card , Button } from 'react-bootstrap'  
import BootstrapTable from 'react-bootstrap-table-next'
import { Link } from 'react-router-dom'
import AxiosConfirmModal from '../components/AxiosConfirmModal'
import { prezziFormatter } from '../_services/helpers'
import PreLoaderWidget from '../components/Loader'

import MUIDataTable from 'mui-datatables'
import localization from '../_services/localization'

const Deals = ( ) => {

    const [api, setApi] = useState()
    const [ deals , setDeals ] = useState()

    const [ filter, _setFilter ] = useState()

    const setFilter = ( addFilter ) => {
        let n = Object.assign({}, filter, addFilter )
        _setFilter(n);
    }

    const loadDeals = ( ) => {

        const source = axios.CancelToken.source()

        let fUrl = "/deals" 

        let query = []

        for ( let d in filter ) {
            query.push( encodeURIComponent(d) + "=" + encodeURIComponent( filter[d] ) )
        }

        if ( query.length ) {
            fUrl += "?"  + query.join("&");
        }

        axios.get(fUrl, { cancelToken : source.token })
            .then( res => {
                setApi(res.data)
            })
            .catch( error => axios.isCancel(error) || console.error( error ))
        
        return () => {
            source.cancel()
        };
    }

    useEffect(() => {

        return loadDeals()

    }, [] )

    useEffect(() => {

        if ( ! api || ! api.data ) {
            return;
        }

        setDeals(api.data)

    }, [api] )
    
    const DeleteServizioButton = props => {
        const [show, setShow] = useState(false)

        return <div className={props.className}>
        
            <Button variant="danger" className="ml-1" onClick={ () => setShow(true) }>
                <i className="fas fa-trash" />
            </Button>

            <AxiosConfirmModal url={ props.url } show={show} method="delete" onHide={() => { setShow(false); loadDeals()}} title="Conferma" >
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

            <AxiosConfirmModal url={ props.url } show={show} method="patch" onHide={() => { setShow(false); loadDeals() }} title="Conferma" >
                Sei sicuro di voler ripristinare questo servizio?
            </AxiosConfirmModal>
        </div>
    }

    return(
        <React.Fragment>
            <Card>
                <Card.Body> 
                    { deals && <MUIDataTable
                        title="Deals" 
                        data={deals}
                        options={{
                            // BLOCCO di DEFAULT per il serverside
                            serverSide : true,
                            onChangePage : page => {
                                setFilter( { page : page + 1 } );
                            },
                            onChangeRowsPerPage : per_page => setFilter( { per_page : per_page , page : 1 } ),
                            elevation : 0, // il box-shadow
                            page : api.current_page - 1,
                            count : api.total,

                            print : false,
                            selectableRows: 'none',
                            textLabels: { ...localization.it.MUIDatatableLabels }
                        }}
                        columns={[
                            {
                                label: 'Cod.',
                                name: 'codice'
                            },
                            {
                                label: 'Titolo',
                                name: 'titolo'
                            },
                            {
                                label: 'Importo',
                                name: 'tariffe.intero.importo',
                                options: {
                                    customBodyRender : ( cell ) => cell ? prezziFormatter(cell) : "-"
                                }
                            },
                            {
                                label: 'Disponibiiltà',
                                name: 'disponibili'
                            },
                            {
                                label : " ",
                                name: "azioni",
                                options : {
                                    // eslint-disable-next-line react/display-name
                                    customBodyRender : ( _cell, { rowIndex } ) =>{
                                        const Buttons = ( ) => {
    
                                            const row = api.data[rowIndex]
    
                                            let url = row._links.self
                                            let state = { deal : row }
    
                                            
                                            return <>
                                                <Button as={ Link } to={ { pathname: row._links.self , state: state} } variant="primary" className="mr-1 d-md-inline-block" title="Accedi alla pagina del prodotto" ><i className="fas fa-edit"/></Button>
                                                { row.cestinato ? <RestoreServizioButton url={url} /> : <DeleteServizioButton url={ row._links.self } className="d-none d-md-inline-block" /> }
                                            </>
    
                                        }
    
                                        return <Buttons />
                                    }
                                }
                            }
                        ]}
                        />}
                </Card.Body>
            </Card>
        </React.Fragment> 
    )
}

export default Deals;