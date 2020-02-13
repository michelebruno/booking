/* eslint-disable react/prop-types */
import React , { useState , useEffect } from "react"

import { Link } from "react-router-dom"
import Button from "react-bootstrap/Button"
import AxiosConfirmModal from "./AxiosConfirmModal"

import AsyncSelect from 'react-select/async'
import MUIDataTable from "mui-datatables"
import Helpers from "../_services/helpers"
import { Tooltip, IconButton, Popover } from "@material-ui/core"
import AddIcon from '@material-ui/icons/Add'

import ProdottiAsyncSelect from "./ProdottiAsyncSelect"

const ProdottiCollegati = ( { servizio , deal , onSuccess, editable , title } ) => {

    const self = deal || servizio

    const [data, setData] = useState()
 
    const [ selectedDealToAdd , setSelectedDealToAdd ] = useState("")

    const [ showAddAxios , setShowAddAxios ] = useState(false)

    const isDeal = Boolean(deal)

    const isServizio = Boolean(servizio)

    const [ prodotti, setProdotti ] = useState()

    useEffect(() => {

        if ( ! prodotti ) {

            if ( deal && deal.servizi ) {
                setProdotti( deal.servizi )
            } else if ( servizio && servizio.deals ) {
                setProdotti( servizio.deals )
            }

            return;
        }

        console.log("Mappo")
        const x = prodotti.map( prodotto => {
            prodotto.tariffe.intero.importo = Helpers.prezzi.formatter(prodotto.tariffe.intero.importo )
            prodotto.tariffe.intero.imponibile = Helpers.prezzi.formatter(prodotto.tariffe.intero.imponibile )
            return prodotto
        })
        setData( x ) 
    }, [ servizio , deal , prodotti ])
 

    return typeof prodotti !== "undefined" && typeof data !== "undefined" ? <div className="prodotti-collegati" >
        
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

        { editable && <div className="pos-relative">
            <AsyncSelect 
                className="w-25 p-2 right-0"
                placeholder="Aggiungi un prodotto"
                getOptionLabel={ a => a.condensato } 
                getOptionValue={ a => a.codice } 
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

        </div>}

        <MUIDataTable 
            title={title}
            data={data}
            columns={ [
                {
                    label: 'Cod.',
                    name: 'codice'
                },
                {
                    label: 'Titolo',
                    name: 'titolo'
                },
                {
                    label: 'Esercente',
                    name: 'esercente.nome',
                    options : {
                        display :  isDeal,
                        viewColumns :  isDeal,
                    }
                },
                {
                    label: 'Imponibile',
                    name: 'tariffe.intero.imponibile',
                    options : {
                        display : ! isDeal
                    }
                },
                {
                    label: 'Importo',
                    name: 'tariffe.intero.importo',
                    options : {
                        display : isDeal
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
                        customBodyRender :  ( _cell, { rowIndex } ) =>{
                            
                            const row = prodotti[rowIndex]

                            if ( ! row ) return;
                            
                            const Buttons = ( ) => {
                                const [ showModal, setShowModal ] = useState(false)
            
                                let url ;
                                let state
            
                                if ( isDeal && deal._links ) {
                                    url = deal._links.servizi + "/" + row.codice
                                    state = { servizio : row }
                                } else if ( isServizio && row._links ) {
                                    url = row._links.servizi + "/" + servizio.codice + "?from=servizio"
                                    state = { deal : row }
                                }
                                
                                return <>
                                    { <AxiosConfirmModal method="delete" show={showModal} url={ url } title="Sicuro di voler scollegare questi prodotti?" onSuccess={ onSuccess } onHide={ () => setShowModal(false)} >L&#39azione non è reversibile.</AxiosConfirmModal> }
                                    <Button as={ Link } to={ { pathname: row._links.self , state } } variant="primary" className="mr-1" title="Accedi alla pagina del prodotto" ><i className="fas fa-edit" /></Button>
                                    { editable && <Button onClick={ () => setShowModal(true)} variant="danger" className="mr-1" title="Scollega" ><i className="fas fa-unlink" /></Button>}
                                </>
            
                            }
            
                            return <Buttons />
                        }
                    }
                }
            ]}
            options={{
                elevation : 0
            }}
            />

    </div> : "No prodotti"
}
ProdottiCollegati.defaultProps = {
    editable: true
}

export default ProdottiCollegati