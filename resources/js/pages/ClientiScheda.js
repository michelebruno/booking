/* eslint-disable react/prop-types */
import React, { useState, useEffect } from 'react'
import PropTypes from 'prop-types'


import Card from "@material-ui/core/Card"
import CardContent from "@material-ui/core/CardContent"
import { Col } from 'react-bootstrap'
import { Row } from 'react-bootstrap'
import MUIDataTable from 'mui-datatables'

import PreLoaderWidget from '../components/Loader'
import AxiosConfirmModal from '../components/AxiosConfirmModal'

const ClientiScheda = ({ match }) => {

    const [cliente, setCliente] = useState()
    const [permanentDeleteAxiosShow, setPermanentDeleteAxiosShow] = useState(false)

    useEffect(() => {
        if (cliente && !cliente.willBeReloaded) return;
        const source = axios.CancelToken.source()

        let url = cliente ? cliente._links.self : '/clienti/' + match.params.cliente_id

        axios.get(url, { cancelToken: source.token })
            .then(res => setCliente(res.data))
            .catch(e => e)

        return () => {
            source.cancel()
        };
    }, [cliente, match.params.cliente_id])

    if (!cliente) return <PreLoaderWidget />

    const handleDeleteAccountPermanently = e => {
        e.preventDefault()
        setPermanentDeleteAxiosShow(true)
    }

    return <>
        <Row>
            <Col sm="12" md="6" lg="4">
                <Card>
                    <CardContent>
                    </CardContent>
                </Card>
            </Col>
            <Col sm="12" md="6" lg="6">
                <Card>
                    <CardContent>
                        <h2>Ordini</h2>
                        {cliente.ordini && <MUIDataTable
                            data={cliente.ordini}
                            columns={[
                                {
                                    name: "id",
                                    label: "Id",
                                },
                                {
                                    name: "importo",
                                    label: "Importo",
                                },
                            ]}
                            options={{
                                elevation: 0,
                            }}
                        />}

                    </CardContent>
                </Card>
            </Col>
            <Col sm="12" md="6" lg="4">
                <Card>
                    <CardContent>
                        <h3>Azioni</h3>
                        <ul>
                            <li><a href="" onClick={handleDeleteAccountPermanently}>Elimina account (inclusi dati personali)</a></li>
                        </ul>
                        <AxiosConfirmModal show={permanentDeleteAxiosShow} url={cliente._links.forceDelete} onHide={() => setPermanentDeleteAxiosShow(false)} >Sei sicuro di voler cancellare definitivamente questo utente?</AxiosConfirmModal>
                    </CardContent>
                </Card>
            </Col>

        </Row>
    </>
}

export default ClientiScheda