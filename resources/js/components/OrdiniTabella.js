import React, { useState , useEffect } from 'react'

import BootstrapTable from 'react-bootstrap-table-next';
import paginationFactory from 'react-bootstrap-table2-paginator';

import Helpers, { prezziFormatter } from '../_services/helpers';
import PreLoaderWidget from './Loader';
import { Button  } from 'react-bootstrap';
import { Link } from 'react-router-dom';

const OrdiniTabella = ( { url } ) => {

    const [api, setApi ] = useState()


    const ordini = ( api && api.data ) ? api.data : null

    const fetchAPI = ( data ) => { 
        const source = axios.CancelToken.source()

        let fUrl = url + "?"

        let q = []

        const x = Object.assign({}, data) 

        for ( let d in x ) {
            q.push( encodeURIComponent(d) + "=" + encodeURIComponent(data[d]) )
        }

        if (q.length) {
            fUrl += q.join("&");
        }

        axios.get( fUrl , { cancelToken : source.token })   
            .then( res => setApi( res.data ) )
            .catch( e => e )

        
        return () => {
            source.cancel()
        };
    }

    useEffect(() => {
        if ( api && ! api.willBeReloaded ) return;

        return fetchAPI()
    }, [api] )

    if ( ! ordini ) return <PreLoaderWidget />


    return <>
        <div className="w-100 ">
            <small>
                <Button variant="link" >Tutti</Button>
            </small>
        </div>
        <BootstrapTable 
            remote
            keyField="id"
            data={ ordini }
            columns={[
                {
                    dataField : "stato",
                    text : "",
                    formatter : cell => {
                        let stato = Helpers.ordini.stato(cell);
                        return <i className={"fas fa-circle " + stato.colorClass } title={stato.label} />
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
                        if (cell.length == 0) return "-";
                        return cell.map( v => { if ( v.descrizione && v.descrizione !== null ) return v.descrizione } )
                            .filter( v => { if ( v ) return true; })
                            .join(', ')
                    }
                },
                {
                    dataField : "data",
                    text : "data"
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
                    fetchAPI({ page : b.page , per_page : b.sizePerPage});
                }
            }}
            bordered={false}
            />
    </>
}


OrdiniTabella.defaultProps = {
    url : "/ordini"
}
export default OrdiniTabella;