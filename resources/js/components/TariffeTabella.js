import React , { useState, useEffect } from "react"
import PropTypes from 'prop-types'

import NuovaTariffaPopover from "./NuovaTariffaPopover"
import AxiosConfirmModal from "./AxiosConfirmModal";

import MUIDataTable from 'mui-datatables'
import AddIcon from '@material-ui/icons/Add';
import { IconButton } from "@material-ui/core";

import Tooltip from "@material-ui/core/Tooltip";
import { prezziFormatter } from "../_services/helpers";
import localization from "../_services/localization";

const TariffeTabella = ( { tariffe, url , onSuccess , ivaInclusa , iva, editable , reloadResource } ) => {
    
    const [tooltipAnchorEl, setTooltipAnchorEl] = useState()

    const [data, setData] = useState()
    
    const deleteRows = ( rowsIndexes ) => {
        const promises = rowsIndexes.data.map( i => {
            return axios.delete( url + "/" + data[i.dataIndex].id )
        })

        axios.all(promises)
            .then( reloadResource )
            .catch( () => {
                console.error("Errore nel cancellarlo ")
            })
    }

    useEffect(() => {
        
        const d = Object.values( tariffe ).map( t => {
            let tariffa = t
            tariffa.imponibile = typeof t.imponibile !== 'undefined' ? prezziFormatter(t.imponibile) : " - "
            tariffa.importo = typeof t.importo !== 'undefined' ? prezziFormatter(t.importo) : " - "

            return tariffa
        })

        setData(d)

    }, [])


    return <>
        {typeof tariffe !== 'undefined' && <MUIDataTable
            title="Tariffario"
            options={{
                elevation : 0 ,
                print : false,
                download : false,
                pagination : false,
                selectableRows : editable ? 'multiple' : 'none',
                isRowSelectable : i => data[i].slug !== "intero",
                customToolbar : () => <>

                    <Tooltip title="Aggiungi una tariffa" ><IconButton onClick={ ( e ) => setTooltipAnchorEl(e.currentTarget) } ><AddIcon /></IconButton></Tooltip>

                    { url && iva && <NuovaTariffaPopover url={url} anchorElement={ tooltipAnchorEl } onClose={ ( ) => setTooltipAnchorEl(null) } onSuccess={ onSuccess } ivaInclusa={ivaInclusa} tariffe={tariffe} />}

                </>,
                onRowsDelete : deleteRows,
                textLabels : { ... localization.it.MUIDatatableLabels }

            }}
            data={ data }
            columns={[
                { 
                    name: 'nome', 
                    label: 'Titolo',
                },
                { 
                    name: 'imponibile',
                    label: 'Imponibile',
                    options : {
                        display : ivaInclusa
                    }
                },
                { 
                    name: 'importo',
                    label: 'Importo',
                    options : {
                        display : !ivaInclusa
                    }
                }
            ]}
        />}
    </>
}

TariffeTabella.propTypes = {    
    editable: PropTypes.bool,
    iva : PropTypes.number.isRequired,
    ivaInclusa : PropTypes.bool,
    onSuccess : PropTypes.func,
    reloadResource : PropTypes.func,
    tariffe : PropTypes.object,
    url : PropTypes.string
}


TariffeTabella.defaultProps = {
    ivaInclusa: true,
    editable: true,
}

export default TariffeTabella