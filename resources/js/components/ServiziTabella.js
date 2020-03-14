/* eslint-disable react/display-name */
import React, { useState } from "react"
import { Link } from "react-router-dom"
import PropTypes from 'prop-types'

import Button from 'react-bootstrap/Button';
import MUIDataTable from "mui-datatables";

import AxiosConfirmModal from '../components/AxiosConfirmModal';
import Helpers from "../_services/helpers";
import ServerDataTable from "./ServerDataTable";

const ServiziTabella = ({ title, esercente, editable, url, ...props }) => {

    const readOnly = typeof props.readOnly === "undefined" ? !editable : props.readOnly

    const DeleteServizioButton = props => {

        const [show, setShow] = useState(false)

        return <div className={props.className}>

            <Button variant="danger" className="ml-1" onClick={() => setShow(true)}>
                <i className="fas fa-trash" />
            </Button>

            <AxiosConfirmModal url={props.url} show={show} method="delete" onHide={() => { setShow(false); reloadApi() }} title="Conferma" >
                Sei sicuro di cancellare questa fornitura?
            </AxiosConfirmModal>

        </div>
    }

    const RestoreServizioButton = props => {

        const [show, setShow] = useState(false)

        return <div className={props.className}>

            <Button variant="danger" className="ml-1" onClick={() => setShow(true)}>
                <i className="fas fa-undo" />
            </Button>

            <AxiosConfirmModal url={props.url} show={show} method="patch" onHide={() => { setShow(false); reloadApi() }} title="Conferma" >
                Sei sicuro di voler ripristinare questa fornitura?
            </AxiosConfirmModal>
        </div>
    }

    const actionButtons = (links, { rowIndex }) => {

        return <>
            <Button as={Link} to={{ pathname: links.self }} variant="success">
                {readOnly || links.restore ? <i className="fas fa-eye" /> : <i className="fas fa-edit" />}
            </Button>
            {!readOnly && (links.restore ? <RestoreServizioButton url={links.restore} method="patch" className="d-none d-md-inline-block" /> : <DeleteServizioButton url={links.self} className="d-none d-md-inline-block" />)}
        </>
    }

    return <ServerDataTable
        url={url}
        title={title}
        columns={[
            {
                name: 'stato',
                label: " ",
                options: {
                    download: false,
                    customBodyRender: cell => {
                        /**
                         * TODO come verificare che sia cestinato?
                         * * andrebbe fatto lato server
                         * if (row.cestinato) return <i className="fas fa-circle text-dark fa-lg" title="Cestinato" />
                         * 
                         */

                        switch (cell) {
                            case "pubblico":
                                return <i className="fas fa-circle text-success fa-lg" title="Pubblico" />
                                // eslint-disable-next-line no-unreachable
                                break;

                            case "privato":
                                return <i className="fas fa-circle text-secondary fa-lg" title="Privato" />
                                // eslint-disable-next-line no-unreachable
                                break;
                        }
                    }
                }
            },
            {
                name: 'codice',
                label: 'Cod.'
            },
            {
                name: 'titolo',
                label: 'Titolo'
            },
            {
                name: 'tariffe.intero.imponibile',
                label: 'Imponibile (prezzo intero)',
                options: {
                    customBodyRender: v => Helpers.prezzi.formatter(v)
                }
            },
            {
                name: 'iva',
                label: 'IVA',
                options: {
                    customBodyRender: iva => iva + "%"
                }
            },
            {
                name: 'disponibili',
                label: 'Disponibilità',
            },
            {
                name: '_links',
                label: ' ',
                options: {
                    customBodyRender: actionButtons
                }
            }
        ]}
        options={{
            print: false,
            selectableRows: editable ? "multiple" : "none"
        }}
    />

}
ServiziTabella.propTypes = {
    className: PropTypes.string,
    editable: PropTypes.bool,
    esercente: PropTypes.object.isRequired,
    readOnly: PropTypes.bool,
    servizi: PropTypes.array,
    title: PropTypes.string,
    url: PropTypes.string.isRequired
}
export default ServiziTabella