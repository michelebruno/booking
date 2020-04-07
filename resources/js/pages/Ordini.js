/* eslint-disable react/prop-types */
import React, { useEffect } from 'react';
import { connect } from 'react-redux'

import { setTopbarButtons, unsetTopbarButtons } from '../_actions';

import Card from "@material-ui/core/Card"
import CardContent from "@material-ui/core/CardContent"
import Button from '@material-ui/core/Button'
import AddIcon from '@material-ui/icons/Add';

import OrdiniTabella from '../components/OrdiniTabella'
import { Link } from 'react-router-dom';


const Ordini = (/* { setTopbarButtons, unsetTopbarButtons } */) => {

    // eslint-disable-next-line no-unused-vars
    const TopbarButtons = () => {
        return <Button variant="contained" color="primary" startIcon={<AddIcon />} component={Link} to="/ordini/crea">Nuovo</Button>
    }

    // useEffect( () => {
    //     setTopbarButtons( TopbarButtons )
    //     return unsetTopbarButtons
    // }, [] )

    return (
        <React.Fragment>
            <Card>
                <CardContent>
                    <OrdiniTabella />
                </CardContent>
            </Card>
        </React.Fragment>

    )
}

export default connect(null, { setTopbarButtons, unsetTopbarButtons })(Ordini)