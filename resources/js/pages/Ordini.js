import React from 'react';
import { Row, Col, Card, Table } from 'react-bootstrap';
import faker from 'faker/locale/it'
import Loader from '../components/Loader';

const TabellaOrdini = ( props ) => {

    return(
        <React.Fragment>
            <Table hover>
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
                    { [0,1,2,3,4,5,6].map( (v, i) => {
                        return <tr key={v} >
                            <td>{ faker.random.number(45899) }</td>
                            <td>{faker.name.findName()} </td>
                            <td>Pranzo tipico a Bologna</td>
                            <td>20-09-2019</td>
                            <td>{ "€" + faker.commerce.price(16,150)}</td>
                        </tr>
                        
                    })}
                </tbody>
            </Table>
        </React.Fragment>
    )
}

export default ( props ) => {
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