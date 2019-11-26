import React , { useState } from 'react'
import { Card, Row, Col, Form, InputGroup, Table, Badge, Button } from 'react-bootstrap'
import PreLoaderWidget from '../components/Loader'

import BootstrapTable from 'react-bootstrap-table-next'

import cellEditFactory , { Type } from 'react-bootstrap-table2-editor';
import EditableField from '../components/EditableField';
import upperCase from 'upper-case';

const Scheda = ( { location , ...props} ) => {

    let initialServizio = false;

    if ( location.state && location.state.servizio ) {
        initialServizio =  location.state.servizio
        initialServizio.willBeReloaded = true
    }

    const [servizio, setServizio] = useState(initialServizio)

    const { codice , titolo , descrizione , stato , tariffe , disponibili , iva } = servizio

    let tariffe_array = []
    
    Object.keys(tariffe).map( slug => {
        tariffe_array.push( tariffe[slug] )
    })

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

    let disponibiliVariant = "success" 

    switch (disponibili) {
        case disponibili < 10:
            disponibiliVariant = "danger"
            break;
    
        default:
            break;
    }

    const editableFieldProps = { url : servizio._links.self , onSuccess : ( r ) => setServizio(r) }
    
    if ( servizio ) return(
        <React.Fragment>
            <Row>
                <Col md="6" >
                    <Card>
                        <Card.Body> 
                            <div className="d-flex justify-content-between">
                                <div >
                                    <span className="h1" >{titolo}</span >
                                    <br />
                                    <span className="text-muted mr-1"><strong>Codice: </strong>{codice}</span>
                                    { stato == "privato" && <Badge size="sm" variant="danger" >{stato}</Badge>}
                                    { stato == "bozza" && <Badge size="sm" variant="light" >{stato}</Badge>}
                                    </div>   
                                <div className="h3">
                                    <Badge variant={disponibiliVariant} className="h4 p-1 text-white align-items-center" >
                                        Disponibili: { disponibili } 
                                        <Button className="fas fa-edit ml-1" variant="success" />
                                    </Badge>
                                </div  > 
                            </div>

                            <EditableField name="titolo" label="Titolo" initialValue={titolo} { ...editableFieldProps} />
                            <EditableField name="codice" label="Codice" initialValue={codice} { ...editableFieldProps} textMutator={ str => upperCase(str) } />
                            <EditableField name="iva" label="IVA" initialValue={iva} append="%" type="number" step="1" max="100" min="0" { ...editableFieldProps} textMutator={ str => upperCase(str) } />
                            <EditableField as="textarea" name="descrizione" label="Descrizione" initialValue={descrizione} { ...editableFieldProps }  />
                            <EditableField as="select" name="stato" label="Stato" initialValue={ stato } { ...editableFieldProps }  >
                                <option value="pubblico">Pubblico</option>
                                <option value="privato">Privato</option>
                                <option value="bozza">Bozza</option>
                            </EditableField>

                            <EditableField type="number" name="disponibili" label="Disponibili" initialValue={disponibili} { ...editableFieldProps}  />
                        </Card.Body>
                    </Card>

                </Col>
                <Col>
                    <Card>
                        <Card.Body>

                            <h3 className="text-muted">
                                Tariffario
                            </h3>

                            <BootstrapTable
                                keyField="id"
                                data={ tariffe_array }
                                columns={[
                                    {
                                        dataField: 'nome', 
                                        text: 'Titolo'
                                    },
                                    { 
                                        dataField: 'imponibile', 
                                        text: 'Prezzo',
                                        formatter: cell => cell ? "€" + cell : " - ",
                                        editorStyle : { width : "5em" },
                                        editCellClasses: "px-0"
                                    } 
                                ]}
                                hover
                                cellEdit={ cellEditFactory({ mode: "dbclick" })} 
                                bordered={ false }
                            /> 

                        </Card.Body>
                    </Card>
                </Col>
                <div className="w-100" />
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
                            </Table>
                </Card.Body>
            </Card>
        </React.Fragment>
    )
    else return <PreLoaderWidget />
}

export default Scheda;