import React from 'react'
import PropTypes from 'prop-types'

import MUIDataTable from 'mui-datatables'

import { prezziFormatter } from '../_services/helpers';
import localization from '../_services/localization';

const OrdiniTransazioniTabella = ( { transazioni } ) => {

    const data = transazioni.map( t => {
        return Object.assign({} , t, {
            gateway : t.gateway,
            importo : prezziFormatter(t.importo),
            transazione_id : t.transazione_id,
            stato : t.stato
        })
    });

    return <MUIDataTable
        title="Transazioni"
        options={ {
            elevation : 0, // il box-shadow
            print : false,
            download : false,
            pagination : false,
            filter : false, 
            selectableRows : 'none',
            setTableProps : () => {
                return { 
                    size : "small"
                }
            },
            textLabels : { ... localization.it.MUIDatatableLabels }  
        }}
        columns={[
            {
                name: 'gateway', label: 'Gateway'
            }, 
            { 
                name: 'importo', label: 'Importo'
            },
            { 
                name: 'transazione_id', label: 'ID transizione'
            },
            {
                name: 'stato', label: "Stato"
            }
        ]}
        data={ data }
    />
}

OrdiniTransazioniTabella.propTypes = {
    transazioni : PropTypes.array.isRequired
}
export default OrdiniTransazioniTabella