/* eslint-disable react/prop-types */
import React , { useState  } from 'react'
import { Link } from 'react-router-dom'

import { Card , Button } from 'react-bootstrap'  
import MUIDataTable from 'mui-datatables'

import AxiosConfirmModal from '../components/AxiosConfirmModal'
import { prezziFormatter } from '../_services/helpers'
import useServerSideCollection from '../_services/useServerSideCollection'
 
const Deals = ( ) => {

    const [ collection, serverSideOptions , { reload : reloadApi , getSordDirectionByName } ] = useServerSideCollection( "/deals" )

    const deals = collection && collection.data

    const colonne = [
        {
            label: 'Cod.',
            name: 'codice',
            options : {
                filter : false,
                sortDirection : getSordDirectionByName("codice")
            }
        },
        {
            label: 'Titolo',
            name: 'titolo',
            options : {
                filter : false,
                sortDirection : getSordDirectionByName("titolo")
            }
        },
        {
            label: 'Importo',
            name: 'tariffe.intero.importo',
            options: {
                customBodyRender : ( cell ) => cell ? prezziFormatter(cell) : "-",
                sort : false,
            }
        },
        {
            label: 'Imponibile',
            name: 'tariffe.intero.imponibile',
            options: {
                customBodyRender : ( cell ) => cell ? prezziFormatter(cell) : "-",
                sort : false,
                display : false,
            }
        },
        {
            label: 'Disponibiiltà',
            name: 'disponibili', 
            options: {
                sortDirection : getSordDirectionByName("disponibili")
            }
        },
        {
            label : " ",
            name: "azioni",
            options : {
                download : false ,
                print : false ,
                filter : false,
                customBodyRender : ( _cell, { rowIndex } ) =>{

                    if ( ! collection || ! collection.data ) {
                        return;
                    }
                    const row = collection.data[rowIndex]
                    

                    let url = row._links && row._links.self
                    let state = { deal : row }

                    return url && <>
                        <Button as={ Link } to={ { pathname: url , state: state} } variant="primary" className="mr-1 d-md-inline-block" title="Accedi alla pagina del prodotto" ><i className="fas fa-edit"/></Button>
                        { row.cestinato ? <RestoreServizioButton url={url} /> : <DeleteServizioButton url={ url } className="d-none d-md-inline-block" /> }
                    </>

                }
            }
        }
    ]

    const DeleteServizioButton = props => {
        const [show, setShow] = useState(false)

        return <div className={props.className}>
        
            <Button variant="danger" className="ml-1" onClick={ () => setShow(true) }>
                <i className="fas fa-trash" />
            </Button>

            <AxiosConfirmModal url={ props.url } show={show} method="delete" onHide={() => { setShow(false); reloadApi() }} title="Conferma" >
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

            <AxiosConfirmModal url={ props.url } show={show} method="patch" onHide={() => { setShow(false); reloadApi() }} title="Conferma" >
                Sei sicuro di voler ripristinare questo servizio?
            </AxiosConfirmModal>
        </div>
    }
    
    return(
        <React.Fragment>
            <Card>
                <Card.Body> 
                    { typeof deals !== "undefined" && <MUIDataTable
                        title="Deals" 
                        data={deals}
                        columns={ colonne }
                        options={{
                            ...serverSideOptions(colonne),
                            elevation : 0, // il box-shadow
                            filter : false,
                            print : false,
                            selectableRows: 'none'
                        }}
                        />}
                </Card.Body>
            </Card>
        </React.Fragment> 
    )
}

export default Deals;