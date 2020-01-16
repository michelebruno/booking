/* eslint-disable react/prop-types */
import React , { useState , useEffect } from 'react';
import { Card, Button } from 'react-bootstrap'; 
import Loader from '../components/Loader';
import BootstrapTable from 'react-bootstrap-table-next';
import PreLoaderWidget from '../components/Loader';
import { prezziFormatter } from '../_services/helpers';

import paginationFactory from 'react-bootstrap-table2-paginator';
import { Link } from 'react-router-dom';
const TabellaOrdini = ( ) => {

    const [api, setApi] = useState()

    const ordini = ( api && api.data ) || undefined;


    const fetchAPI = ( data ) => { 
        const source = axios.CancelToken.source()

        let url = "/ordini?"

        let q = []

        for ( let d in data ) {
            q.push( encodeURIComponent(d) + "=" + encodeURIComponent(data[d]) )
        }

        if (q.length) {
            url += q.join("&");
        }

        axios.get( url , { cancelToken : source.token })   
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

    if ( ! api ) return <PreLoaderWidget />

    return(
        <React.Fragment>
            <BootstrapTable 
                remote
                keyField="id"
                data={ ordini }
                columns={[
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
            
        </React.Fragment>
    )
}

const Ordini = ( props ) => {
    return(
        <React.Fragment>
            <div className="position-relative">
                { /* preloader */}
                { props.loading && <Loader />} 
                <Card>
                    <Card.Body>
                        <h1>Ordini</h1>
                        <TabellaOrdini />
                    </Card.Body>
                </Card>
            </div>
        </React.Fragment>

    )
}

export default Ordini 