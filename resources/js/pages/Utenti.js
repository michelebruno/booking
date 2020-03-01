/* eslint-disable react/prop-types */
import React, { useState, useEffect } from "react"
import { connect } from "react-redux"
import { Link } from "react-router-dom"

import Card from "react-bootstrap/Card"
import Button from "react-bootstrap/Button"
import ButtonToolbar from "react-bootstrap/ButtonToolbar"
import Modal from "react-bootstrap/Modal"
import Tooltip from "@material-ui/core/Tooltip"
import IconButton from "@material-ui/core/IconButton"
import AddIcon from '@material-ui/icons/Add';

import MUIDataTable from "mui-datatables"

import { setTopbarButtons, unsetTopbarButtons } from "../_actions"
import useServerSideCollection from '../_services/useServerSideCollection'
import UtenteForm from "../components/UtenteForm"
import PreLoaderWidget from "../components/Loader"


const Utenti = ({ setTopbarButtons, unsetTopbarButtons }) => {

    const [collection, serverSideOptions, { reload, getSortDirectionByName }] = useServerSideCollection("/users")

    const [aggiungiModal, setAggiungiModal] = useState(false)

    const utenti = collection && collection.data

    const columns = [
        {
            name: 'email',
            label: 'Email',
            options: {
                sortDirection: getSortDirectionByName('email')
            }
        },
        {
            name: 'username',
            label: 'Username',
            options: {
                sortDirection: getSortDirectionByName('username')
            },
        },
        {
            name: 'ruolo',
            label: 'Tipo',
            options: {
                sortDirection: getSortDirectionByName('ruolo')
            },
        },
        {
            name: 'meta',
            label: ' ',
            options: {
                sort: false,
                filter: false,
                download: false,
                print: false,
                customBodyRenderer: (_cell, { rowIndex }) => {

                    const row = utenti[rowIndex]

                    let url = ""

                    switch (row.ruolo) {
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

                    return <Button size="sm" as={Link} to={{ pathname: url + row.id, state: { utente: row } }} ><i className="fas fa-edit" /></Button>
                }
            }
        }
    ]

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
                        <UtenteForm onSuccess={reload} />
                    </Modal.Body>
                </Modal>

                {typeof utenti == "undefined" ? <PreLoaderWidget /> :
                    <MUIDataTable
                        columns={columns}
                        data={utenti}
                        options={{
                            ...serverSideOptions(columns),
                            customToolbar: () => <Tooltip title="Crea nuovo utente">
                                <IconButton onClick={() => setAggiungiModal(true)} >
                                    <AddIcon />
                                </IconButton>
                            </Tooltip>
                        }}
                    />
                }
            </Card.Body>
        </Card>
    )
}



export default connect(null, { setTopbarButtons, unsetTopbarButtons })(Utenti);