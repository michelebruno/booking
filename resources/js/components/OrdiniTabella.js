import React, { useState , useEffect } from 'react'

import PropTypes from 'prop-types'

import Helpers, { prezziFormatter } from '../_services/helpers';
import PreLoaderWidget from './Loader';
import IconButton from '@material-ui/core/IconButton';
import VisibilityIcon from '@material-ui/icons/Visibility'
import { Link } from 'react-router-dom';
import MUIDataTable from 'mui-datatables'
import localization from '../_services/localization';

const OrdiniTabella = ( { url , defaultFilter , ...props } ) => {

    const [ api, setApi ] = useState()

    const [ordini, setOrdini] = useState()

    const [ filter, _setFilter ] = useState( defaultFilter )

    const setFilter = ( addFilter ) => {
        let n = Object.assign( {}, filter, addFilter )
        _setFilter(n);
    }

    const fetchAPI = () => { 

        const source = axios.CancelToken.source()

        let fUrl = url + "?"

        let query = []

        for ( let d in filter ) {
            query.push( encodeURIComponent(d) + "=" + encodeURIComponent( filter[d] ) )
        }

        if ( query.length ) {
            fUrl += query.join("&");
        }

        axios.get( fUrl , { cancelToken : source.token })   
            .then( res => setApi( res.data ) )
            .catch( e => e )

        return source.cancel
    }

    useEffect( () => {

        if ( api && ! api.willBeReloaded ) return;

        return fetchAPI()

    }, [ api ] )

    useEffect( () => {

        return fetchAPI()
        
    }, [ filter ] )

    useEffect( () => {

        if ( ! api || ! api.data ) return

        const x = api.data.map( o => Object.assign({}, o, {
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

    }, [ api ])

    if ( ! ordini ) return <PreLoaderWidget />

    return <MUIDataTable 
            data={ ordini }
            onReloadApi={ setFilter }
            options = { {
                // BLOCCO di DEFAULT per il serverside
                serverSide : true,
                onChangePage : page => {
                    setFilter( { page : page + 1 } );
                },
                onChangeRowsPerPage : per_page => setFilter( { per_page : per_page , page : 1 } ),
                elevation : 0, // il box-shadow
                page : api.current_page - 1,
                count : api.total,
                selectableRows : 'none',
                textLabels : { ... localization.it.MUIDatatableLabels }
            }}
            columns={[
                {
                    name : "stato",
                    label : " ",
                    options : {
                        sort : false, 
                        filterOptions: {
                          names: ["Aperti", "Elaborati"]
                        },
                        filter: "Elaborati",
                        customBodyRender : cell => {

                            const stato = Helpers.ordini.stato(cell);

                            let className = "fas fa-circle ";

                            // eslint-disable-next-line react/prop-types
                            if (stato.waiting) {
                                className = "fas fa-spinner fa-spin "
                            }

                            // eslint-disable-next-line react/prop-types
                            return <i className={ className + stato.colorClass } title={stato.label} />
                        }
                    }
                },
                {
                    name: 'id',
                    label : "#",
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
                            <IconButton size="small" component={ Link } to={ { pathname : cell.self , state : { ordine : api.data[rowIndex] } }} >
                                <VisibilityIcon />
                            </IconButton>
                        </>
                    }
                }
            ]}
            { ...props}
            /> 
}

OrdiniTabella.propTypes = {
    defaultFilter : PropTypes.object ,
    url : PropTypes.string
}

OrdiniTabella.defaultProps = {
    url : "/ordini",
    defaultFilter : { stato : "PAGATI" }
}
export default OrdiniTabella;