import React , { useState } from "react"

import { connect } from "react-redux"
import cellEditFactory from 'react-bootstrap-table2-editor';

import BootstrapTable from 'react-bootstrap-table-next'
import Button from "react-bootstrap/Button"
import NuovaTariffaPopover from "./NuovaTariffaPopover"
import AxiosConfirmModal from "./AxiosConfirmModal";

const TariffeTabella = ( { tariffe, varianti , url , onSuccess } ) => {
    
    const addTariffaRef = React.useRef(null) 

    const [showTariffeTooltip, setShowTariffeTooltip] = useState(false)

    let varianti_disponibili = Object.assign({}, varianti)

    if ( varianti && tariffe ) { Object.keys( tariffe).map( variante => {
            let keys = Object.keys(varianti) 
            let pos = keys.findIndex( ( value ) => value == variante ) 
            pos !== -1 && delete varianti_disponibili[keys[pos]]
        })
    }

    let cellEdit = cellEditFactory({
        mode: "dbclick",
        beforeSaveCell : (oldValue, newValue, row, column, done) => {
            axios.patch( url + "/" + row.id , { imponibile : newValue } )
                .then( res => {
                    onSuccess(res.data)
                    done(true)
                })
                .catch( error => done(false) )
            return { async: true };
          }
    }) 

    return <>
        { typeof varianti !== 'undefined' && <>
            { url && <NuovaTariffaPopover url={url} reference={addTariffaRef} show={ showTariffeTooltip } onClose={ ( ) => setShowTariffeTooltip(false) } onSuccess={ onSuccess } varianti={ Object.values(varianti_disponibili) } />}
            <div className="d-flex justify-content-between">
                <span className="h3">
                    Tariffario
                </span>
                { <strong title="Aggiungi una tariffa per il prodotto" className="text-muted align-self-center" ref={addTariffaRef} onClick={ () => setShowTariffeTooltip(!showTariffeTooltip) } >Nuovo</strong> }   
            </div>
        </> || <h3>Tariffario</h3>}
        {typeof tariffe !== 'undefined' && <BootstrapTable
            keyField="id"
            data={ Object.values( tariffe ) }
            columns={[
                { 
                    dataField: 'nome', 
                    text: 'Titolo',
                    editable: false
                },
                { 
                    dataField: 'imponibile',
                    text: 'Imponibile',
                    formatter: cell => typeof cell !== 'undefined' ? "€ " + cell : " - ",
                    editorStyle : { width : "5em" , margin: "0" } 
                },
                {
                    dataField: 'azioni',
                    text : "",
                    editable: false,
                    classes: "text-right",
                    formatter : ( cell , row ) => {
                        const Formatter = (props) => {

                            const [showAxios, setShowAxios] = useState(false)

                            return <>
                                <AxiosConfirmModal 
                                    method="delete" 
                                    show={showAxios} 
                                    onHide={() => setShowAxios(false) } url={ url + "/" + row.id } 
                                    onSuccess={ onSuccess }
                                    title="Sicuro di voler eliminare la tariffa?"  
                                    noDataIndication="Il tariffario è vuoto."
                                >
                                    Questa azione non è reversibile.
                                </AxiosConfirmModal>
                                <i title="Elimina tariffa." className="fas fa-minus-circle text-danger fa-2x" onClick={ () => setShowAxios(true) } />
                            </>
                        }
                        
                        return <Formatter />

                        }
                }
            ]}
            hover
            cellEdit={ cellEdit }
            bordered={ false }
        />}
    </>
}

export default connect( state => { return { varianti : state.settings.varianti_tariffe } } )( TariffeTabella )