import React, { useState , useEffect } from 'react'
import { connect } from 'react-redux'
import { Card } from 'react-bootstrap'

import BootstrapTable from "react-bootstrap-table-next"
import { Link } from 'react-router-dom'

const Clienti = () => {

    const [api, setApi] = useState()


    useEffect( () => {
        if ( api && ! api.willBeReloaded ) return;
        const source = axios.CancelToken.source()
        axios.get('/clienti', { cancelToken : source.token })
            .then( res => setApi( { clienti : res.data } ) )
            .catch( () => true )
        return () => {
            source.cancel()
        };
    }, [api])    
    
    if ( !api ) return "Loading";


    return <Card>
        <Card.Body>
            { api && api.clienti && <BootstrapTable 
                keyField="id"
                data={api.clienti}
                columns={[
                    {
                        dataField: "email",
                        text : "Email",
                        formatter: ( cell , row ) => {
                            return <Link to={ row._links.self }>{ cell }</Link>
                        }
                    }
                ]}
                condensed
                bordered={false}
                hover
                />}
        </Card.Body>
    </Card>

}


export default connect()(Clienti)