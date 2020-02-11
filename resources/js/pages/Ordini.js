/* eslint-disable react/prop-types */
import React, { useEffect } from 'react';
import { connect } from 'react-redux'

import { setTopbarButtons , unsetTopbarButtons } from '../_actions';

import { Card } from 'react-bootstrap';
import Button from '@material-ui/core/Button'
import AddIcon from '@material-ui/icons/Add';

import OrdiniTabella from '../components/OrdiniTabella'
import { Link } from 'react-router-dom';


const Ordini = ( props ) => {

    const TopbarButtons = () => {
        return <Button variant="contained" color="primary" startIcon={ <AddIcon /> } component={Link} to="/ordini/crea">Nuovo</Button>
    }

    useEffect( () => {
        props.setTopbarButtons( TopbarButtons )
        return props.unsetTopbarButtons
    }, [] )

    return(
        <React.Fragment>
            <Card>
                <Card.Body>
                    <OrdiniTabella />
                </Card.Body>
            </Card>
        </React.Fragment>

    )
}

export default connect( null , { setTopbarButtons, unsetTopbarButtons } )( Ordini )