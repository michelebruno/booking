import React from 'react'; 

import { connect } from "react-redux"
import Row from 'react-bootstrap/Row'
import Col from 'react-bootstrap/Col'
import Card from 'react-bootstrap/Card'

import Loader from '../components/Loader';


const Dashboard = ( { currentUser , ...props} ) => {
    

    return (
        <React.Fragment>
            <div className="">
                { /* preloader */}
                { props.loading && <Loader />}

                <Row>
                    <Col lg={6}>
                        <Card>
                            <Card.Body>
                                Ciao { currentUser.username }, benvenuto nella dashboard.<br />
                                Hai i permessi di { currentUser.ruolo }.
                            </Card.Body>
                        </Card>
                    </Col>
                </Row>
            </div>
        </React.Fragment>
    )

}


export default connect( state => {
    return {
        currentUser : state.currentUser
    }
} )(Dashboard);