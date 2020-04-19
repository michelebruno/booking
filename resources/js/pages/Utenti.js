/* eslint-disable react/prop-types */
import React, {useEffect, useRef, useState} from "react"
import {connect} from "react-redux"
import {Link} from "react-router-dom"

import Card from "@material-ui/core/Card"
import CardContent from "@material-ui/core/CardContent"

import Button from "@material-ui/core/Button"

import Modal from "react-bootstrap/Modal"
import Tooltip from "@material-ui/core/Tooltip"
import IconButton from "@material-ui/core/IconButton"
import EditIcon from '@material-ui/icons/Edit';
import PersonAddIcon from '@material-ui/icons/PersonAdd';


import {setTopbarButtons, unsetTopbarButtons} from "../_actions"
import UtenteForm from "../components/UtenteForm"
import ServerDataTable from "../components/ServerDataTable"
import Helpers from "../_services/helpers"


const Utenti = ({ setTopbarButtons, unsetTopbarButtons }) => {

    const [aggiungiModal, setAggiungiModal] = useState(false)

    const tableRef = useRef()

    useEffect(() => {
        setTopbarButtons(() =>
            <Button variant="contained" color="primary" size="small" onClick={() => setAggiungiModal(true)} startIcon={<PersonAddIcon />} >Aggiungi utente</Button>,
        )
        return unsetTopbarButtons
    }, [setTopbarButtons, unsetTopbarButtons])

    return (
        <Card>
            <CardContent>
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
                                /**
                                 * TODO fare in modo che nella query il ruolo sia inviato come raw e non come label
                                 */
                                filter: false,
                                customBodyRender: Helpers.utenti.ruoli.getLabel,
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
                                customBodyRender: (cell) => <IconButton color="primary" component={Link} to={{ pathname: cell.self }} ><EditIcon /></IconButton>,
                            },
                        },
                    ]}
                    options={{
                        customToolbar: () => <Tooltip title="Crea nuovo utente">
                            <IconButton onClick={() => setAggiungiModal(true)} >
                                <PersonAddIcon />
                            </IconButton>
                        </Tooltip>,
                    }}
                />
            </CardContent>
        </Card >
    )
}



export default connect(null, { setTopbarButtons, unsetTopbarButtons })(Utenti);
