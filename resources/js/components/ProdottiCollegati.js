import React , { useState } from "react"

import { Link } from "react-router-dom"
import BootstrapTable from 'react-bootstrap-table-next'
import Button from "react-bootstrap/Button"
import AxiosConfirmModal from "./AxiosConfirmModal"

const ProdottiCollegati = ( { servizio , deal , onSuccess } ) => {

    const prodotti = deal ? deal.servizi : ( servizio ? servizio.deals : [] )
 
    
    return typeof prodotti !== 'undefined' && <BootstrapTable 
    keyField="id"
    noDataIndication="Non ci sono prodotti collegati."
    data={prodotti}
    columns={[
        {
            text: 'Cod.',
            dataField: 'codice'
        },
        {
            text: 'Titolo',
            dataField: 'titolo'
        },
        {
            text: 'Imponibile',
            dataField: 'tariffe.intero.imponibile'
        },
        {
            text: 'Disponibiiltà',
            dataField: 'disponibili'
        },
        {
            text : "",
            dataField: "azioni",
            formatter : ( cell, row ) =>{
                const Buttons = ( props ) => {
                    const [showModal, setShowModal] = useState(false)

                    let url ;
                    let state

                    if ( deal && deal._links ) {
                        url = deal._links.servizi + "/" + row.codice
                        state = { servizio : row }
                    } else if ( servizio && row._links ) {
                        url = row._links.servizi + "/" + servizio.codice + "?from=servizio"
                        state = { deal : row }

                    }
                    
                    return <>
                        { <AxiosConfirmModal method="delete" show={showModal} url={ url } title="Sicuro di voler scollegare questi prodotti?" onSuccess={ onSuccess } onHide={ () => setShowModal(false)} >L'azione non è reversibile.</AxiosConfirmModal> }
                        <Button as={ Link } to={ { pathname: row._links.self , state: state} } variant="primary" className="mr-1" title="Accedi alla pagina del prodotto" ><i className="fas fa-edit" /></Button>
                        <Button onClick={ () => setShowModal(true)} variant="danger" className="mr-1" title="Scollega" ><i className="fas fa-unlink" /></Button>
                    </>

                }

                return <Buttons />
            }
        }
    ]}
    hover
    bordered={ false }
    /> || "Errore"
}

export default ProdottiCollegati