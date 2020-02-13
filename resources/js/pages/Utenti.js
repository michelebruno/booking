/* eslint-disable react/prop-types */
import React, { useState , useEffect } from "react"
import { connect } from "react-redux"
import { Link } from "react-router-dom"

import Card from "react-bootstrap/Card"
import Button from "react-bootstrap/Button"
import ButtonToolbar from "react-bootstrap/ButtonToolbar"
import Modal  from "react-bootstrap/Modal"

import MUIDataTable from "mui-datatables"

import { setTopbarButtons , unsetTopbarButtons } from "../_actions"
import useServerSideCollection from '../_services/useServerSideCollection'
import UtenteForm from "../components/UtenteForm"
import PreLoaderWidget from "../components/Loader"

const Utenti = ( props ) => {

    const [ collection, serverSideOptions, { reload } ] = useServerSideCollection( "/users" )

    const utenti = collection && collection.data

    const [aggiungiModal, setAggiungiModal] = useState(false)

    const columns = [ 
        {
            name: 'email',
            label : 'Email'
        },
        {
            name: 'username',
            label: 'Username',
        },
        {
            name: 'ruolo',
            label: 'Tipo',
            classes : "d-none d-lg-table-cell",
            headerClasses : "d-none d-lg-table-cell"
        },
        {
            name: 'meta',
            label: ' ',
            options : {
                customBodyRenderer : ( _cell , { rowIndex } ) => {
                    
                    const row = utenti[rowIndex]

                    let url = ""
    
                    switch (row.ruolo) {
                        case "cliente":
                            url += "/clienti/"
                            break;
        
                        case "esercente":
                            url += "/esercenti/"
                            break;
                    
                        default:
                            url += "/utenti/"
                            break;
                    }
                    return <React.Fragment><Button size="sm" as={Link} to={{pathname: url+row.id, state: { utente : row}}} ><i className="fas fa-edit" /></Button></React.Fragment>
                }
            } 
        }
    ]

    props.setTopbarButtons( () => <ButtonToolbar className="d-inline-block align-self-center" >
        <Button onClick={() => setAggiungiModal(true)} >Aggiungi utente</Button>
    </ButtonToolbar>)

    useEffect(() => {
        return props.unsetTopbarButtons
    }, [])

    return(
        <Card>
            <Card.Body>
                <p>
                    <Modal show={aggiungiModal} onHide={() => setAggiungiModal(false)}>
                        <Modal.Header closeButton>
                            <Modal.Title>
                                Crea nuovo utente
                            </Modal.Title>
                        </Modal.Header>
                        <Modal.Body>
                            <UtenteForm onSuccess={ reload } />
                        </Modal.Body> 
                    </Modal>
                </p>

                { typeof utenti == "undefined" ? <PreLoaderWidget /> : 
                    <MUIDataTable
                        columns={columns}
                        data={utenti}
                        options={{
                            ...serverSideOptions(columns),
                        }}
                    />
                } 
            </Card.Body>
        </Card>
    )
}



export default connect( null, { setTopbarButtons, unsetTopbarButtons })( Utenti );