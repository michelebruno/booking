/* eslint-disable react/prop-types */
import React from 'react'

import { Card } from 'react-bootstrap'
import MUIDataTable from 'mui-datatables'

import FormFiltraggio from '../components/FormFiltraggio'

const Tickets = () => {
    return(
        <React.Fragment>
            <Card>
                <Card.Body>
                    <h1>Ticket</h1>
                    <FormFiltraggio />
                    <MUIDataTable
                        keyField="token"
                        columns={[
                            { name: 'token', label: 'Token'},
                            { name: 'ordine', label: 'Ordine'},
                            { name: 'stato', label: 'Stato'}, 
                            { name: 'acqi', label: 'Acquistato'},
                            { name: 'scadenza', label: 'Scadenza'},
                            { name: 'fornitore', label: 'Assegnatario'},
                        ]}
                        data={[
                            { token: 'TERX54', ordine: '2657', stato: 'Convalidato', fornitore: 'Ciccio Pasticcio', scadenza: '20-11-2019', acqi: '20-08-2019'  },
                            { token: 'GDFG654', ordine: '2657', stato: 'Libero', scadenza: '20-11-2019', acqi: '20-08-2019'  }
                        ]}
                    />
                </Card.Body>
            </Card>
        </React.Fragment>
    )
}

export default Tickets