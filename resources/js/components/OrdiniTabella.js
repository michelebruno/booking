import React, { useState , useEffect } from 'react'

import BootstrapTable from 'react-bootstrap-table-next';
import paginationFactory from 'react-bootstrap-table2-paginator';

import PropTypes from 'prop-types'

import Helpers, { prezziFormatter } from '../_services/helpers';
import PreLoaderWidget from './Loader';
import { Button  } from 'react-bootstrap';
import { Link } from 'react-router-dom';

const OrdiniTabella = ( { url , defaultFilter } ) => {

    const [ api, setApi ] = useState()

    const [ filter, _setFilter ] = useState( defaultFilter )

    const setFilter = ( addFilter ) => {
        
        let n = Object.assign({}, filter, addFilter )
        _setFilter(n);
    }

    const ordini = ( api && api.data ) ? api.data : null

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

        return () => {
            source.cancel()
        };
    }

    useEffect( () => {

        if ( api && ! api.willBeReloaded ) return;

        return fetchAPI(filter)

    }, [ api ] )

    useEffect(() => {

        return fetchAPI(filter)
        
    }, [filter])

    if ( ! ordini ) return <PreLoaderWidget />

    // eslint-disable-next-line react/prop-types
    const FilterButton = ( { stato , children } ) => {
        let className = "";
        if ( filter && filter.stato && filter.stato === stato) {
            className = "font-weight-bold"
        }
        return <Button variant="link" className={ className } onClick={ () => setFilter( { stato } ) } >{children}</Button>;
    }

    return <>
        <div className="w-100 ">
            <small>
                <FilterButton stato={ "PAGATI" }>Pagati</FilterButton> |
                <FilterButton stato={ "APERTO" }>In attesa di pagamento</FilterButton> |
            </small>
        </div>
        <BootstrapTable 
            remote
            wrapperClasses="table-responsive"
            keyField="id"
            data={ ordini }
            columns={[
                {
                    dataField : "stato",
                    text : "",
                    formatter : cell => {

                        const stato = Helpers.ordini.stato(cell);

                        let className = "fas fa-circle ";

                        // eslint-disable-next-line react/prop-types
                        if (stato.waiting) {
                            className = "fas fa-spinner fa-spin "
                        }

                        // eslint-disable-next-line react/prop-types
                        return <i className={ className + stato.colorClass } title={stato.label} />
                    }
                },
                {
                    dataField: 'id',
                    text : "#"
                },
                {
                    dataField: 'cliente.email',
                    text : "Cliente"
                },
                {
                    dataField : "voci",
                    text : "Prodotti",
                    formatter : ( cell ) => {
                        if ( cell.length == 0 ) return "-";
                        return cell.map( v => { if ( v.descrizione && v.descrizione !== null ) return v.descrizione } )
                            .filter( v => { if ( v ) return true; })
                            .join(', ')
                    }
                },
                {
                    dataField : "data",
                    text : "Data"
                },
                {
                    dataField : "importo",
                    text : "Totale",
                    formatter : prezziFormatter
                },
                {
                    dataField : "_links",
                    text: "",
                    formatter : ( cell , row ) => <>
                        <Link to={ { pathname : cell.self , state : { ordine : row } }}>
                            <Button >
                                <i className="fa fa-edit" />
                            </Button>
                        </Link>
                    </>
                }
            ]}
            pagination={ paginationFactory( { page : api.current_page , sizePerPage : parseInt( api.per_page ) , totalSize : api.total }) }
            onTableChange={ ( a , b ) =>{
                if ( a == "pagination" ) {
                    setFilter( { page : b.page , per_page : b.sizePerPage} )
                }
            }}
            bordered={false}
            />
    </>
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