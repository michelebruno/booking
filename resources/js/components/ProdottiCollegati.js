import React , { useState } from "react"

import { Link } from "react-router-dom"
import BootstrapTable from 'react-bootstrap-table-next'
import Button from "react-bootstrap/Button"
import Row from "react-bootstrap/Row"
import Col from "react-bootstrap/Col"
import AxiosConfirmModal from "./AxiosConfirmModal"

import AsyncSelect from 'react-select/async'

const ProdottiCollegati = ( { servizio , deal , onSuccess, aggiungiText } ) => {

    const self = deal || servizio

    const prodotti = deal ? deal.servizi : ( servizio ? servizio.deals : [] )
 
    const [selectedDealToAdd, setSelectedDealToAdd] = useState("")
    const [showAddAxios, setShowAddAxios] = useState(false)

    return typeof prodotti !== 'undefined' && <div className="prodotti-collegati" >
        
        { selectedDealToAdd && <AxiosConfirmModal 
            onSuccess={ d =>{ 
                onSuccess(d) ; 
                setShowAddAxios(false)
                setSelectedDealToAdd("")
            } } 
            onHide={ () => { setSelectedDealToAdd(""); setShowAddAxios(false) }}
            method="post" 
            data={ { servizio : selectedDealToAdd ? ( deal ? selectedDealToAdd.codice : self.codice ) : null } } 
            show={showAddAxios} url={ deal ? self._links.servizi : selectedDealToAdd._links.servizi + "?from=servizio" } 
            title="Confermi di voler aggiungere questo prodotto?"
            >
            { selectedDealToAdd.titolo }
        </AxiosConfirmModal>
        }

        <div className="pos-relative">
            <AsyncSelect 
                className="w-25 p-2 right-0"
                placeholder="Aggiungi un prodotto"
                getOptionLabel={ a => a.condensato } 
                getOptionValue={ a => a.codice } 
                cacheOptions
                defaultOptions
                value={ selectedDealToAdd } 
                onChange={ (v) => {
                    setSelectedDealToAdd(v); 
                    setShowAddAxios(true)
                } }
                loadOptions={
                    ( inputValue , callback ) => {
                        let url = servizio ? '/deals?notAttachedToServizi=' + self.id + '&s=' + inputValue  : "/servizi?notAttachedToDeals=" + self.id + "&s=" + inputValue
                        axios.get( encodeURI( url ) )
                            .then( res => callback(res.data) )
                    }
                }
            />

        </div>
        <BootstrapTable 
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
            />

    </div> || ""
}

export default ProdottiCollegati