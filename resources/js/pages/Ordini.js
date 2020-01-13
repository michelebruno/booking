/* eslint-disable react/prop-types */
import React , { useState , useEffect } from 'react';
import { Card, Table } from 'react-bootstrap'; 
import Loader from '../components/Loader';

const TabellaOrdini = ( ) => {

    const [api, setApi] = useState()

    const ordini = ( api && api.ordini ) || undefined;

    useEffect(() => {
        if ( api && ! api.willBeReloaded ) return;

        const source = axios.CancelToken.source()

        axios.get('/ordini', { cancelToken : source.token })   
            .then( res => setApi({ ordini : res.data }) )
            .catch()
        
        return () => {
            source.cancel()
        };
    }, [api])

    return(
        <React.Fragment>
            { ordini && <Table hover responsive>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Cliente</th>
                        <th>Prodotti</th>
                        <th>Data</th>
                        <th>Totale</th>
                    </tr>
                </thead>
                <tbody>
                    { ordini.map( (o) => {
                        return <tr key={o.id} >
                            <td>{ o.id }</td>
                            <td>{ o.cliente.email } </td>
                            <td className="text-truncate">{ o.voci.map( v => { return v.descrizione }) } </td>
                            <td>{ o.data }</td>
                            <td>€ { o.importo }</td>
                        </tr>
                        
                    })}
                </tbody>
            </Table>}
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