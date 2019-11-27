import React , { useState } from 'react'
import { Card, Row, Col, Form, InputGroup, Table, Badge, Button, Overlay, Tooltip, Popover } from 'react-bootstrap'
import PreLoaderWidget from '../components/Loader'
import { connect } from "react-redux"
import BootstrapTable from 'react-bootstrap-table-next'

import cellEditFactory , { Type } from 'react-bootstrap-table2-editor';
import EditableField from '../components/EditableField';
import upperCase from 'upper-case';
import NuovaTariffaPopover from '../components/NuovaTariffaPopover';

const Scheda = ( { location , varianti , ...props} ) => {
    
    const addTariffaRef = React.useRef(null)
    const [showTariffeTooltip, setShowTariffeTooltip] = useState(false)

    let initialServizio = false;

    if ( location.state && location.state.servizio ) {
        initialServizio =  location.state.servizio
        initialServizio.willBeReloaded = true
    }

    const [servizio, setServizio] = useState(initialServizio)

    const { codice , titolo , descrizione , stato , tariffe , disponibili , iva } = servizio

    let disponibiliVariant = "success" 

    switch (disponibili) {
        case disponibili < 10:
            disponibiliVariant = "danger"
            break;
    
        default:
            break;
    }

    let varianti_disponibili = varianti

    if ( varianti ) { Object.keys(servizio.tariffe).map( variante => {
            let keys = Object.keys(varianti) 
            let pos = keys.findIndex( ( value ) => value == variante ) 
            pos !== -1 && delete varianti_disponibili[keys[pos]]
        })
    }
    const editableFieldProps = { url : servizio._links.self , onSuccess : ( r ) => setServizio(r) }
    
    if ( servizio ) return(
        <React.Fragment>
            <Row>
                <Col xs="12" xl="6" >
                    <Card>
                        <Card.Body> 
                            <div className="d-flex justify-content-between">
                                <div >
                                    <span className="h1" >{titolo}</span >
                                    <br />
                                    <span className="text-muted mr-1"><strong>Codice: </strong>{codice}</span>
                                    { stato == "privato" && <Badge variant="danger" >Privato</Badge>}
                                    { stato == "bozza" && <Badge variant="light" >Bozza</Badge>}
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
                <Col xs="12" xl="6">
                    <Card>
                        <Card.Body>
                            { typeof varianti_disponibili !== 'undefined' && <>
                                <NuovaTariffaPopover url={servizio._links.tariffe} reference={addTariffaRef} show={ showTariffeTooltip } onClose={ ( ) => setShowTariffeTooltip(false) } onSuccess={ d => setServizio(d) } varianti={ Object.values(varianti_disponibili) } />
                                <div className="d-flex justify-content-between">
                                    <span className="h3">
                                        Tariffario
                                    </span>
                                    <strong className="text-muted align-self-center" ref={addTariffaRef} onClick={ () => setShowTariffeTooltip(!showTariffeTooltip) } >Nuovo</strong>
                                </div>
                            </>}

                            <BootstrapTable
                                keyField="id"
                                data={ Object.values(tariffe) }
                                columns={[
                                    {
                                        dataField: 'nome', 
                                        text: 'Titolo'
                                    },
                                    { 
                                        dataField: 'imponibile', 
                                        text: 'Imponibile',
                                        formatter: cell => cell ? "€" + cell : " - ",
                                        editorStyle : { width : "5em" , margin: "0" },
                                    } 
                                ]}
                                hover
                                cellEdit={ cellEditFactory({
                                    mode: "dbclick",
                                    beforeSaveCell : (oldValue, newValue, row, column, done) => {
                                        axios.patch(servizio._links.tariffe + "/" + row.id , { imponibile : newValue } )
                                            .then( res => {
                                                setServizio(res.data)
                                                done(true)
                                            })
                                            .catch( error => done(false) )
                                        return { async: true };
                                      }
                                })} 
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

export default connect(state => { return { varianti : state.settings.varianti_tariffe_assoc } })(Scheda);