/* eslint-disable react/prop-types */
import React, { useState, useEffect, useRef } from "react"
import { connect } from "react-redux"
import { Link } from "react-router-dom"

import Card from "react-bootstrap/Card"
import Button from "react-bootstrap/Button"
import ButtonToolbar from "react-bootstrap/ButtonToolbar"
import Modal from "react-bootstrap/Modal"
import Tooltip from "@material-ui/core/Tooltip"
import IconButton from "@material-ui/core/IconButton"
import AddIcon from '@material-ui/icons/Add';


import { setTopbarButtons, unsetTopbarButtons } from "../_actions"
import UtenteForm from "../components/UtenteForm"
import ServerDataTable from "../components/ServerDataTable"


const Utenti = ({ setTopbarButtons, unsetTopbarButtons }) => {

    const [aggiungiModal, setAggiungiModal] = useState(false)

    const tableRef = useRef()

    useEffect(() => {
        setTopbarButtons(() => <ButtonToolbar className="d-inline-block align-self-center" >
            <Button onClick={() => setAggiungiModal(true)} >Aggiungi utente</Button>
        </ButtonToolbar>)
        return unsetTopbarButtons
    }, [setTopbarButtons, unsetTopbarButtons])

    return (
        <Card>
            <Card.Body>
                <Modal show={aggiungiModal} onHide={() => setAggiungiModal(false)}>
                    <Modal.Header closeButton>
                        <Modal.Title>
                            Crea nuovo utente
                        </Modal.Title>
                    </Modal.Header>
                    <Modal.Body>
                        <UtenteForm onSuccess={() => tableRef.current.reload()} />
                    </Modal.Body>
                </Modal>
                <ServerDataTable
                    ref={tableRef}
                    url="/users"
                    columns={[
                        {
                            name: 'email',
                            label: 'Email',
                        },
                        {
                            name: 'username',
                            label: 'Username',
                        },
                        {
                            name: 'ruolo',
                            label: 'Tipo',
                            options: {
                                customBodyRender: cell => {
                                    switch (cell) {
                                        case "admin":
                                            return "Admin"
                                            break;
                                        case "account_manager":
                                            return "Account Manager"
                                            break;

                                        case "fornitore":
                                            return "Fornitore"
                                            break;

                                        default:
                                            return cell
                                            break;
                                    }
                                },
                            },
                        },
                        {
                            name: '_links',
                            label: ' ',
                            options: {
                                sort: false,
                                filter: false,
                                download: false,
                                print: false,
                                customBodyRender: (cell, { rowData }) => {

                                    let url = ""

                                    switch (cell) {
                                        case "cliente":
                                            url += "/clienti/"
                                            break;

                                        case "fornitore":
                                            url += "/fornitori/"
                                            break;

                                        default:
                                            url += "/utenti/"
                                            break;
                                    }

                                    return <Button size="sm" as={Link} to={{ pathname: cell.self }} ><i className="fas fa-edit" /></Button>
                                },
                            },
                        },
                    ]}
                    options={{
                        customToolbar: () => <Tooltip title="Crea nuovo utente">
                            <IconButton onClick={() => setAggiungiModal(true)} >
                                <AddIcon />
                            </IconButton>
                        </Tooltip>,
                    }}
                />
            </Card.Body>
        </Card >
    )
}



export default connect(null, { setTopbarButtons, unsetTopbarButtons })(Utenti);