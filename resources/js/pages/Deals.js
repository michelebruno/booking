/* eslint-disable react/prop-types */
import React , { useState  } from 'react'
import { Card , Button } from 'react-bootstrap'  
import { Link } from 'react-router-dom'
import AxiosConfirmModal from '../components/AxiosConfirmModal'
import { prezziFormatter } from '../_services/helpers'

import MUIDataTable from 'mui-datatables'
import useServerSideCollection from '../_services/useServerSideCollection'

const Deals = ( ) => {

    const [ collection ,  , setFilter , serverSideOptions , reloadApi ] = useServerSideCollection( "/deals" )

    const deals = collection && collection.data

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

            <AxiosConfirmModal url={ props.url } show={show} method="patch" onHide={() => { setShow(false); reloadApi() }} title="Conferma" >
                Sei sicuro di voler ripristinare questo servizio?
            </AxiosConfirmModal>
        </div>
    }

    const colonne = [
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

                        const row = deals[rowIndex]

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
    ]

    return(
        <React.Fragment>
            <Card>
                <Card.Body> 
                    { deals && <MUIDataTable
                        title="Deals" 
                        data={deals}
                        options={{
                            ...serverSideOptions(setFilter, colonne),
                            elevation : 0, // il box-shadow

                            print : false,
                            selectableRows: 'none',
                        }}
                        columns={ colonne }
                        />}
                </Card.Body>
            </Card>
        </React.Fragment> 
    )
}

export default Deals;