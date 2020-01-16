import React , { useState } from "react"
import PropTypes from 'prop-types'

import cellEditFactory from 'react-bootstrap-table2-editor';

import BootstrapTable from 'react-bootstrap-table-next'
import NuovaTariffaPopover from "./NuovaTariffaPopover"
import AxiosConfirmModal from "./AxiosConfirmModal";

const TariffeTabella = ( { tariffe, url , onSuccess , ivaInclusa , iva, editable } ) => {
    
    const addTariffaRef = React.useRef(null) 

    const [showTariffeTooltip, setShowTariffeTooltip] = useState(false)

    let cellEdit = cellEditFactory({
        mode: "dbclick",
        beforeSaveCell : (oldValue, newValue, row, column, done) => {
            let invia = ivaInclusa ? { importo : newValue } : { imponibile : newValue}
            axios.patch( url + "/" + row.id , invia )
                .then( res => {
                    onSuccess(res.data)
                    done(true)
                })
                .catch( () => done(false) )
            return { async: true };
          }
    })
    
    const prezziFormatter = new Intl.NumberFormat('en-US', { style : 'currency' , currency: 'EUR' } ).format

    return <>
    
            { url && iva && <NuovaTariffaPopover url={url} reference={addTariffaRef} show={ showTariffeTooltip } onClose={ ( ) => setShowTariffeTooltip(false) } onSuccess={ onSuccess } ivaInclusa={ivaInclusa} tariffe={tariffe} />}
            <div className="d-flex justify-content-between">
                <span className="h3">
                    Tariffario
                </span>
                { editable && <strong title="Aggiungi una tariffa per il prodotto" className="text-muted align-self-center" ref={addTariffaRef} onClick={ () => setShowTariffeTooltip(!showTariffeTooltip) } >Nuovo</strong> }   
            </div>
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
                    formatter: cell => typeof cell !== 'undefined' ? prezziFormatter(cell) : " - ",
                    editorStyle : { width : "5em" , margin: "0" } ,
                    editable,
                    hidden: ivaInclusa
                },
                { 
                    dataField: 'importo',
                    text: 'Importo',
                    formatter: cell => typeof cell !== 'undefined' ? prezziFormatter(cell) : " - ",
                    editorStyle : { width : "5em" , margin: "0" } ,
                    editable,
                    hidden: !ivaInclusa
                },
                {
                    dataField: 'azioni',
                    text : "",
                    editable: false,
                    hidden: !editable,
                    classes: "text-right",
                    // eslint-disable-next-line react/display-name
                    formatter : ( cell , row ) => {
                        const Formatter = () => {

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
                                { row.slug !== "intero" && editable && <i title="Elimina tariffa." className="fas fa-minus-circle text-danger fa-2x" onClick={ () => setShowAxios(true) } />}
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

TariffeTabella.propTypes = {    
    editable: PropTypes.bool,
    iva : PropTypes.number.isRequired,
    ivaInclusa : PropTypes.bool,
    onSuccess : PropTypes.func,
    tariffe : PropTypes.object,
    url : PropTypes.string
}


TariffeTabella.defaultProps = {
    ivaInclusa: true,
    editable: true,
}

export default TariffeTabella