import React from 'react'
import BootstrapTable from 'react-bootstrap-table-next'
import { Card } from 'react-bootstrap'

import FormFiltraggio from '../components/FormFiltraggio'

const Tickets = props => {
    return(
        <React.Fragment>
            <Card>
                <Card.Body>
                    <h1>Ticket</h1>
                    <FormFiltraggio />
                    <BootstrapTable
                        keyField="token"
                        columns={[
                            { dataField: 'token', text: 'Token'},
                            { dataField: 'ordine', text: 'Ordine'},
                            { dataField: 'stato', text: 'Stato'}, 
                            { dataField: 'acqi', text: 'Acquistato'},
                            { dataField: 'scadenza', text: 'Scadenza'},
                            { dataField: 'fornitore', text: 'Assegnatario'},
                        ]}
                        data={[
                            { token: 'TERX54', ordine: '2657', stato: 'Convalidato', fornitore: 'Ciccio Pasticcio', scadenza: '20-11-2019', acqi: '20-08-2019'  },
                            { token: 'GDFG654', ordine: '2657', stato: 'Libero', scadenza: '20-11-2019', acqi: '20-08-2019'  }
                        ]}
                        bordered={false}
                        hover
                    />
                </Card.Body>
            </Card>
        </React.Fragment>
    )
}

export default Tickets