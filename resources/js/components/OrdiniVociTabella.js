import React, { useState , useEffect } from 'react'
import PropTypes from 'prop-types'

import MUIDataTable from 'mui-datatables'

import { prezziFormatter } from '../_services/helpers';
import localization from '../_services/localization';
import PreLoaderWidget from './Loader';

const OrdiniVociTabella = ( { voci , ...props} ) => {

    const [data, setData] = useState()

    useEffect(() => {

        if ( ! voci ) {
            return setData([])
        }

        const x = voci.map( v => Object.assign( {}, v, {
            tickets : v.tickets ? v.tickets.map( ( v ) => v.token ).join(", ") : "",
            riscattati : typeof v.riscattati == "number" && v.tickets ? v.riscattati.toString() + " / " + v.tickets.length : "",
            costo_unitario : prezziFormatter(v.costo_unitario),
            totale : prezziFormatter(v.importo)
        }) )
        
        setData(x)
        
    }, [voci])


    return <MUIDataTable
        title="Articoli"
        data={ data } 
        options={ {
            elevation : 0, // il box-shadow
            print : false,
            download : false,
            pagination : false,
            selectableRows : 'none',
            setTableProps : () => {
                return { 
                    size : "small"
                }
            },
            textLabels : { ...localization.it.MUIDatatableLabels }
        }}
        columns={[
            { 
                name: 'codice', label: 'Cod. prodotto', 
            },
            {
                name: 'descrizione', label: 'Descrizione' 
            },
            {
                name: 'tickets', 
                label: 'Tickets associati', 
                options : {
                    filter: false
                }
            },
            { 
                name: 'riscattati', label: 'Riscattati' , 
                options : {
                    filter: false,
                }
            },
            { 
                name: 'tickets.scadenza', label: 'Scadenza', options : { filter : false } 
            },
            { 
                name: 'costo_unitario', label: 'Costo unitario', 
                options : {
                    filter: false,
                }
            },
            { 
                name: 'totale', label: 'Totale'
            }
        ] }
        { ...props }
    />
}

OrdiniVociTabella.propTypes = {
    voci : PropTypes.array.isRequired
}
export default OrdiniVociTabella