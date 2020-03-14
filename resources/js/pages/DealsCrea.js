/* eslint-disable react/prop-types */
import React from 'react'
import { Card } from 'react-bootstrap'

import ProdottoForm from '../components/ProdottiForm'
const CreaDeals = () => {
    return (
        <React.Fragment>
            <h1>Crea nuovo</h1>
            <Card>
                <Card.Body>
                    <ProdottoForm url="/deals" md={6} />
                </Card.Body>
            </Card>
        </React.Fragment>
    )
}

export default CreaDeals;