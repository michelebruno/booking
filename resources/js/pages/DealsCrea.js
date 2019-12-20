import React , { useState } from 'react'
import { Form , Card, Row, Col , Button } from 'react-bootstrap'
import { Redirect } from 'react-router-dom'

import ProdottoForm from '../components/ProdottiForm'
const CreaDeals = ( props ) => { 
 
    
    return(
        <React.Fragment>
            <h1>Crea nuovo</h1>
            <Card>
                <Card.Body>
                    <ProdottoForm url="/deals" />
                </Card.Body>
            </Card> 
        </React.Fragment>
    )
}

export default CreaDeals;