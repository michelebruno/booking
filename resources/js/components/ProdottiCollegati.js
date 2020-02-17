/* eslint-disable react/prop-types */
import React , { useState , useEffect } from "react"
import { Link } from "react-router-dom"

import MUIDataTable from "mui-datatables"
import AsyncSelect from 'react-select/async'
import { Tooltip, IconButton, Popover } from "@material-ui/core"
import AddIcon from '@material-ui/icons/Add'
import LinkOffIcon from '@material-ui/icons/LinkOff'
import EditIcon from '@material-ui/icons/Edit'
import VisibilityIcon from '@material-ui/icons/Visibility'
import Button from "@material-ui/core/Button"


import AxiosConfirmModal from "./AxiosConfirmModal"
import ProdottiAsyncSelect from "./ProdottiAsyncSelect"
import Helpers from "../_services/helpers"
import localization from "../_services/localization"

const ProdottiCollegati = ( { servizio , deal , onSuccess, editable , title } ) => {

    const self = deal || servizio
 
    const [ selectedDealToAdd , setSelectedDealToAdd ] = useState("")

    const [ showAddAxios , setShowAddAxios ] = useState(false)

    const fromDeal = Boolean(deal)

    const fromServizio = Boolean(servizio)

    const prodotti = ( deal && deal.servizi ) ? deal.servizi : servizio.deals 

    return <div className="prodotti-collegati" >
        
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
            data={ prodotti }
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
                        display : fromDeal,
                        viewColumns : fromDeal,
                    }
                },
                {
                    label: 'Imponibile',
                    name: 'tariffe.intero.imponibile',
                    options : {
                        display : ! fromDeal,
                        customBodyRender : v => Helpers.prezzi.formatter(v)
                    }
                },
                {
                    label: 'Importo',
                    name: 'tariffe.intero.importo',
                    options : {
                        display : fromDeal,
                        customBodyRender : v => Helpers.prezzi.formatter(v)
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
                        download : false,
                        customBodyRender :  ( _cell, { rowIndex } ) =>{
                            
                            const row = prodotti[rowIndex]

                            if ( ! row ) return;
                            
                            const Buttons = ( ) => {
                                const [ showModal, setShowModal ] = useState(false)
            
                                let url ;
                                let state
            
                                if ( fromDeal && deal._links ) {
                                    url = deal._links.servizi + "/" + row.codice
                                    state = { servizio : row }
                                } else if ( fromServizio && row._links ) {
                                    url = row._links.servizi + "/" + servizio.codice + "?from=servizio"
                                    state = { deal : row }
                                }
                                
                                return <>
                                    { <AxiosConfirmModal method="delete" show={showModal} url={ url } title="Sicuro di voler scollegare questi prodotti?" onSuccess={ onSuccess } onHide={ () => setShowModal(false)} >L&#39;azione non è reversibile.</AxiosConfirmModal> }

                                    <IconButton component={ Link } to={ { pathname: row._links.self , state } } variant="contained" color="primary" className="mr-1" title="Accedi alla pagina del prodotto" >
                                        { editable ? <EditIcon /> : <VisibilityIcon /> }
                                    </IconButton>

                                    { editable && <IconButton onClick={ () => setShowModal(true) } variant="" className="mr-1" title="Scollega" ><LinkOffIcon /></IconButton>}
                                </>
            
                            }
            
                            return <Buttons />
                        }
                    }
                }
            ]}
            options={{
                print : false,
                textLabels: {
                    ...localization.it.MUIDatatableLabels
                },
                selectableRows : editable ? "multiple" : "none",
                elevation : 0
            }}
            />

    </div> 
}
ProdottiCollegati.defaultProps = {
    editable: true
}

export default ProdottiCollegati