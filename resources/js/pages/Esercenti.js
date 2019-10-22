import React from 'react';

import Loader from '../components/Loader';

import { Row , Col , Table , Card, Pagination } from 'react-bootstrap';
import { Link } from 'react-router-dom'

const Esercenti = ( { id, ...props } ) => {
    return(
        <React.Fragment>
            { /* preloader */}
            {props.loading && <Loader />}
            <Row>
                <Col className="mb-2" xs="12" lg="3">
                    <div className="d-flex w-100 justify-content-between ">
                        <h5>Gruppi</h5>
                        <Link to='#'><h5 className=" text-muted">Nuovo</h5></Link>
                    </div>
                    <ul className="list-group">
                        <li className="list-group-item d-flex justify-content-between align-items-center">
                            Guide
                            <span className="badge badge-primary badge-pill">14</span>
                        </li>
                        <li className="list-group-item d-flex justify-content-between align-items-center">
                            Ristoranti
                            <span className="badge badge-primary badge-pill">2</span>
                        </li>
                        <li className="list-group-item d-flex justify-content-between align-items-center">
                            POS
                            <span className="badge badge-primary badge-pill">1</span>
                        </li>
                    </ul>
                </Col>
                <Col lg="9">
                    <Card>
                        <Card.Body>                                
                            <Table hover>
                                <thead>
                                    <tr>
                                        <th>Ragione sociale</th>
                                        <th>Email</th>
                                        <th>Username</th>
                                        <th>Ultima convalida</th>
                                    </tr>
                                </thead>
                                <tbody>
                                        <tr className="position-relative">
                                                <td><Link className="stretched-link" to="/esercenti/12"></Link>Osteria del nonno</td>
                                                <td>osteriadelnonno@example.com</td>
                                                <td>Osteria del Nonno</td>
                                                <td>2019-12-21 08:11:15</td>
                                        </tr>
                                        <tr className="position-relative">
                                                <td><Link className="stretched-link" to="/esercenti/13"></Link>Osteria del babbo</td>
                                                <td>osteriadelbabbo@example.com</td>
                                                <td>Osteria del babbo</td>
                                                <td>2019-12-21 08:11:15</td>
                                        </tr>
                                </tbody>
                            </Table>
                            
                        </Card.Body>
                    </Card>
                </Col>
            </Row>
        </React.Fragment>
    )

}


export default Esercenti;