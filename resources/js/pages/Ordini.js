/* eslint-disable react/prop-types */
import React, { useEffect } from 'react';
import { connect } from 'react-redux'

import { setTopbarButtons , unsetTopbarButtons } from '../_actions';

import { Card, Button } from 'react-bootstrap'; 
import OrdiniTabella from '../components/OrdiniTabella'
import { Link } from 'react-router-dom';


const Ordini = ( props ) => {

    const TopbarButtons = () => {
        return <Button as={Link} to="/ordini/crea" >
            <i className="fas fa-plus mr-1" />Nuovo
        </Button>
    }

    useEffect( () => {
        props.setTopbarButtons(TopbarButtons)
        return props.unsetTopbarButtons
    }, [] )

    return(
        <React.Fragment>
            <Card>
                <Card.Body>
                    <h1>Ordini</h1>
                    <OrdiniTabella />
                </Card.Body>
            </Card>
        </React.Fragment>

    )
}

export default connect( null , { setTopbarButtons, unsetTopbarButtons } )( Ordini )