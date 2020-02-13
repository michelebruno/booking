/* eslint-disable react/prop-types */
import React, { useState , useEffect } from 'react'
import { Link } from 'react-router-dom';

import IconButton from '@material-ui/core/IconButton';
import VisibilityIcon from '@material-ui/icons/Visibility'
import MUIDataTable from 'mui-datatables';

import PreLoaderWidget from './Loader';
import useServerSideCollection from '../_services/useServerSideCollection';
import Helpers, { prezziFormatter } from '../_services/helpers';

const OrdiniTabella = ( { url , defaultFilter } ) => {

    const [ ordini, setOrdini ] = useState()  

    const [ collection, serverSideOptions, { filter }  ] = useServerSideCollection( url , defaultFilter )

    useEffect( () => {

        if ( ! collection || ! collection.data ) return

        const x = collection.data.map( o => Object.assign({}, o, {
            importo : prezziFormatter(o.importo),
            imponibile : prezziFormatter(o.imponibile),
            imposta : prezziFormatter(o.imposta),
            voci : o.voci.length == 0 ? "-" : o.voci.map( v => {
                    if ( v.descrizione && v.descrizione !== null ) return v.descrizione } 
                )
                .filter( v => { if ( v ) return true; })
                .join(', '),
            })
        )

        setOrdini(x)

    }, [ collection ] )

    if ( ! ordini ) return <PreLoaderWidget />

    const colonne = [
        {
            name : "stato",
            label : " ",
            options : {
                sort : false, 
                filterList : filter.stato || "Pagati",
                filterOptions: {
                  names: ["Aperti", "Pagati"]
                },
                customBodyRender : cell => {

                    const stato = Helpers.ordini.stato(cell);

                    let className = "fas fa-circle ";

                    // eslint-disable-next-line react/prop-types
                    if (stato.waiting) {
                        className = "fas fa-spinner fa-spin "
                    }

                    // eslint-disable-next-line react/prop-types
                    return <i className={ className + stato.colorClass } />
                }
            }
        },
        {
            name: 'id',
            label : "#",
            _filterName : "id",
            options : {
                sort : false,
                filter : false,
            }
        },
        {
            name: 'cliente.email',
            label : "Cliente",
            options : {
                sort : false,
                filter : false,
            }
        },
        {
            name : "voci",
            label : "Prodotti",
            options : {
                sort : false,
            }
        },
        {
            name : "data",
            label : "Data",
            options : {
                sort : false,
                filter : false,
            }
        },
        {
            name : "importo",
            label : "Totale",
            options :{ 
                sort : false
            }
        },
        {
            name : "_links",
            label: " ",
            options : {
                sort : false, 
                filter : false,
                download : false,
                print : false,
                customBodyRender : ( cell , { rowIndex } ) => <>
                    <IconButton size="small" component={ Link } to={ { pathname : cell.self , state : { ordine : collection.data[rowIndex] } }} >
                        <VisibilityIcon />
                    </IconButton>
                </>
            }
        }
    ]

    return <MUIDataTable
        data={ordini}
        columns={colonne}
        options={{
            ...serverSideOptions( colonne , { errorMessage : collection.error || undefined} ),
            page : collection.current_page - 1,
            count : collection.total,
            selectableRows : 'none',
            print : false,
            search : false,
            filter : [
                ["Elaborati"]
            ],
        }}
        />

}

OrdiniTabella.defaultProps = {
    url : "/ordini",
    defaultFilter : { stato : "Pagati" }
}
export default OrdiniTabella;