import React from 'react'
import { Card, Row, Col, Image, Table, Badge } from 'react-bootstrap'
import BootstrapTable from 'react-bootstrap-table-next'
import faker from 'faker/locale/it'


const Scheda = ( props ) => {

    const titolo = "Pranzo tipico dal Rosso",
    descrizione = "Mollit eiusmod veniam amet aliqua. Dolore ullamco deserunt laborum laborum ut in ullamco consequat dolore magna officia aliquip excepteur. Ea deserunt occaecat aute elit deserunt qui est commodo. Elit adipisicing adipisicing duis reprehenderit reprehenderit. Cillum aliquip irure est nisi proident ut aliqua labore qui laboris. Sint dolor reprehenderit nostrud non velit esse. Laborum nulla aute sint anim quis.",
    thumbnail = {
        url : 'http://www.turismo.bologna.it/wp-content/uploads/2019/09/una-inclinata-laltra-di-più-le-torri-asinelli-e-garisenda.jpg',
        alt : 'Vista qualsiasi di Bologna',
        title : 'Vista qualsiasi di Bologna'
    }

    const prezzi = [
        {
            id: 125,
            target: {
                slug: 'adulti',
                titolo: 'Adulti'
            },
            imponibile: {
                costo: '€12',
                valuta: 'EUR'
            },
            imposta: '22%',
            stato: 'Approvato'
        },
        {
            id: 1285,
            target: {
                slug: 'adulti',
                titolo: 'Adulti - pacchetto'
            },
            imponibile: {
                costo: '€10',
                valuta: 'EUR'
            },
            imposta: '22%',
            stato: 'Da approvare'
        }
    ]

    const dealscollegati = [
        {
            id: 2,
            titolo: 'Pranzo da Gino',
            disponibilità: 8,
            fornitore: {
                id: 8,
                nome: 'Gino il ristorante',
                links: {
                    self: '/esercenti/12',
                    frontend: 'www.turismo.bologna.it/gino...'
                }
            }
        },
        {
            id: 3,
            titolo: 'Pranzo da Gino',
            disponibilità: 6,
            fornitore: {
                id: 8,
                nome: 'Gino il ristorante',
                links: {
                    self: '/esercenti/12',
                    frontend: 'www.turismo.bologna.it/gino...'
                }
            }
        },
        {
            id: 4,
            titolo: 'Pranzo da Gino',
            disponibilità: 8,
            fornitore: {
                id: 8,
                nome: 'Gino il ristorante',
                links: {
                    self: '/esercenti/12',
                    frontend: 'www.turismo.bologna.it/gino...'
                }
            }
        }
    ]
    
    return(
        <React.Fragment>
            <Row>
                <Col md="6" >
                    <Card>
                        <Card.Body> 
                            <div><span className="h1">{titolo}</span>   <span className="h3"><Badge variant="success" className="h3 ml-2 p-1 text-white" >Disponibilità: 31 <i className="fas fa-edit" /></Badge></span  > </div>
                            <h4 className="text-muted">
                                Descrizione
                            </h4>
                            <p>{descrizione}</p>
                            <h4 className="text-muted">
                                Tariffario
                            </h4>
                            <BootstrapTable
                                keyField="id"
                                data={prezzi}
                                columns={[
                                    { dataField: 'stato', text: 'Stato' },
                                    { dataField: 'target.titolo', text: 'Titolo' },
                                    { dataField: 'imponibile.costo', text: 'Prezzo' }
                                ]}
                                hover
                                bordered={ false }
                            /> 
                        </Card.Body>
                    </Card>

                </Col>
                <Col md="6">
                    <Card>
                        <Card.Body>
                            <h2>Azioni richieste</h2>
                            <ul>
                                <li>Approva la descrizione</li>
                                <li>Inserisci le fatture di fine mese</li>
                            </ul>
                        </Card.Body>
                    </Card>
                </Col>
            </Row>
            <Card>
                <Card.Body>
                    <h2>Deas e esperienze collegate</h2>
                            <Table hover>
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nome</th> 
                                        <th>Prezzo (adulti)</th>
                                        <th>Disponibilità</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {
                                        [ 0, 1, 2, 3, 4, 5].map( ( value , index ) => {
                                            return(
                                                <tr key={index}>
                                                    <td>{ "S-" + (value*2*value) + "0-1" + value }</td>
                                                    <td>Pranzo da €12</td> 
                                                    <td>12</td>
                                                    <td>{ faker.random.number(28)}</td>
                                                </tr>
                                            )
                                        })
                                    }
                                </tbody>
                            </Table>
                </Card.Body>
            </Card>
        </React.Fragment>
    )
}

export default Scheda;