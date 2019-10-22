import React, { Component } from 'react'; 
import { Row, Col, Card } from 'react-bootstrap';
 
import Loader from '../components/Loader';


const Dashboard = ( props ) => {

    return (
        <React.Fragment>
            <div className="">
                { /* preloader */}
                { props.loading && <Loader />}

                <Row>
                    <Col lg={12}>
                        <Card>
                            <Card.Body>
                                Ciao, benvenuto nella dashboard
                            </Card.Body>
                        </Card>
                    </Col>
                </Row>
            </div>
        </React.Fragment>
    )

}


export default Dashboard;