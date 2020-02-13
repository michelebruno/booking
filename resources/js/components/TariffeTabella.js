import React , { useState, useEffect } from "react"
import PropTypes from 'prop-types'

import NuovaTariffaPopover from "./NuovaTariffaPopover"
import AxiosConfirmModal from "./AxiosConfirmModal";

import MUIDataTable from 'mui-datatables'
import AddIcon from '@material-ui/icons/Add';
import { IconButton } from "@material-ui/core";

import Tooltip from "@material-ui/core/Tooltip";
import Helpers from "../_services/helpers";
import localization from "../_services/localization";

const TariffeTabella = ( { tariffe, url , onSuccess , ivaInclusa , iva, editable , reloadResource } ) => {
    
    const [tooltipAnchorEl, setTooltipAnchorEl] = useState()

    const [data, setData] = useState(Object.values(tariffe))
    
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


    return <>
        { typeof data !== "undefined" && <MUIDataTable
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
                        display : ivaInclusa,
                        customBodyRender : v => Helpers.prezzi.formatter(v)
                    }
                },
                { 
                    name: 'importo',
                    label: 'Importo',
                    options : {
                        display : !ivaInclusa,
                        customBodyRender : v => Helpers.prezzi.formatter(v)
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
    tariffe : PropTypes.object.isRequired,
    url : PropTypes.string
}


TariffeTabella.defaultProps = {
    ivaInclusa: true,
    editable: true,
}

export default TariffeTabella