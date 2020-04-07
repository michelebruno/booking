/* eslint-disable react/prop-types */
import React, { useState, useEffect, useRef } from 'react'
import { connect } from 'react-redux'
import { Link } from 'react-router-dom'

import { setTopbarButtons, unsetTopbarButtons } from '../_actions';

import Card from "@material-ui/core/Card"
import CardContent from "@material-ui/core/CardContent"
import Button from "@material-ui/core/Button"
import IconButton from "@material-ui/core/IconButton"

import EditIcon from "@material-ui/icons/Edit"
import DeleteIcon from "@material-ui/icons/Delete"
import UndoIcon from "@material-ui/icons/Undo"

import { prezziFormatter } from '../_services/helpers'
import ServerDataTable from '../components/ServerDataTable'
import AxiosConfirmModal from '../components/AxiosConfirmModal';

const DialogDealForm = React.lazy(() => import("../components/DialogDealForm"))

const Deals = ({ setTopbarButtons, unsetTopbarButtons }) => {

    /**
     * * React Hooks
     */

    const tableRef = useRef()

    useEffect(() => {
        setTopbarButtons(() => {
            const DealTopbarButtons = () => {

                const [modal, setModal] = useState(false)

                return <>
                    <React.Suspense>
                        <DialogDealForm
                            onClose={() => setModal(false)}
                            open={modal}
                            onSuccess={tableRef.current.reload}
                        />
                    </React.Suspense>
                    <Button
                        variant="contained"
                        color="primary"
                        onClick={() => setModal(true)}
                    >
                        Nuovo
                    </Button>
                </>
            }
            return <DealTopbarButtons />
        })
        return unsetTopbarButtons
    }, [setTopbarButtons, unsetTopbarButtons])

    const DeleteServizioButton = props => {
        const [show, setShow] = useState(false)

        return <div className={props.className}>

            <IconButton color="secondary" className="ml-1" onClick={() => setShow(true)}>
                <DeleteIcon />
            </IconButton>

            <AxiosConfirmModal url={props.url} show={show} method="delete" onHide={() => { setShow(false); tableRef.current.reload() }} title="Conferma" >
                Sei sicuro di cancellare questo fornitura?
            </AxiosConfirmModal>
        </div>
    }

    const RestoreServizioButton = props => {
        const [show, setShow] = useState(false)

        return <div className={props.className}>

            <Button color="danger" className="ml-1" onClick={() => setShow(true)}>
                <UndoIcon />
            </Button>

            <AxiosConfirmModal url={props.url} show={show} method="patch" onHide={() => { setShow(false); tableRef.current.reload() }} title="Conferma" >
                Sei sicuro di voler ripristinare questa fornitura?
            </AxiosConfirmModal>
        </div>
    }

    return (
        <React.Fragment>
            <Card>
                <CardContent>
                    <ServerDataTable
                        ref={tableRef}
                        title="Deals"
                        url="/deals"
                        columns={[
                            {
                                name: 'stato',
                                label: "Stato",
                                options: {
                                    display: "excluded",
                                    filterOptions: ["Pubblico", "Privato", "Cestinato"],

                                },
                            },
                            {
                                label: 'Cod.',
                                name: 'codice',
                                options: {
                                    filter: false,
                                },
                            },
                            {
                                label: 'Titolo',
                                name: 'titolo',
                                options: {
                                    filter: false,
                                },
                            },
                            {
                                label: 'Importo',
                                name: 'tariffe.intero.importo',
                                options: {
                                    customBodyRender: (cell) => cell ? prezziFormatter(cell) : "-",
                                    sort: false,
                                    filter: false,
                                },
                            },
                            {
                                label: 'Imponibile',
                                name: 'tariffe.intero.imponibile',
                                options: {
                                    customBodyRender: (cell) => cell ? prezziFormatter(cell) : "-",
                                    sort: false,
                                    filter: false,
                                    display: "false",
                                },
                            },
                            {
                                label: 'Disponibiiltà',
                                name: 'disponibili',
                                options: {
                                    filter: false,
                                },
                            },
                            {
                                label: 'Disponibili più di',
                                name: 'disponibili_gte',
                                options: {
                                    display: "excluded",
                                    filterType: "textField",
                                },
                            },
                            {
                                label: 'Disponibili meno di',
                                name: 'disponibili_lte',
                                options: {
                                    display: "excluded",
                                    filterType: "textField",
                                },
                            },
                            {
                                label: " ",
                                name: "_links",
                                options: {
                                    download: false,
                                    print: false,
                                    filter: false,
                                    customBodyRender: (links) => {


                                        let url = links.self
                                        /**
                                         * TODO 
                                         * 
                                         * let state = { deal: row }
                                         */
                                        let state = {}

                                        return url && <>
                                            <IconButton
                                                component={Link}
                                                to={{ pathname: url, state: state }}
                                                color="primary"
                                                variant="text"
                                                className="mr-1 d-md-inline-block"
                                                title="Accedi alla pagina del prodotto"
                                            >
                                                <EditIcon />
                                            </IconButton>
                                            {links.restore ? <RestoreServizioButton url={url} className="d-none d-md-inline-block" /> : <DeleteServizioButton url={url} className="d-none d-md-inline-block" />}
                                        </>

                                    },
                                },
                            },
                        ]}
                        options={{
                            elevation: 0, // il box-shadow
                            print: false,
                            selectableRows: 'none',
                            serverSideFiterList: ["Cestinati: cestinati"],
                        }}
                    />
                </CardContent>
            </Card>
        </React.Fragment>
    )
}

export default connect(null, { setTopbarButtons, unsetTopbarButtons })(Deals);