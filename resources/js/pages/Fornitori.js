/* eslint-disable react/prop-types */
import React, { useEffect, useRef } from 'react';
import { connect } from "react-redux"
import { Link } from 'react-router-dom'

import { Row, Col, Card, Button } from 'react-bootstrap';
import MUIDataTable from 'mui-datatables';

import { setTopbarButtons, unsetTopbarButtons } from '../_actions';
import PreLoaderWidget from '../components/Loader';
import ServerDataTable from '../components/ServerDataTable';

const Fornitori = (props) => {

    useEffect(() => {
        props.setTopbarButtons(() => {
            return <Button size="sm" as={Link} to="/fornitori/crea">Nuovo</Button>
        })
        return () => {
            props.unsetTopbarButtons()
        };
    }, [])

    const ref = useRef()

    return (
        <React.Fragment>
            { /* preloader */}
            {props.loading && <div className="px-5"><PreLoaderWidget /></div>}
            <Row>
                <Col className="mb-2" xs="12" lg="3">
                    <div className="d-flex w-100 justify-content-between ">
                        <h5>Gruppi</h5>
                        <Link to='#'><h5 className="text-muted">Nuovo</h5></Link>
                    </div>
                    <ul className="list-group">
                        <li className="list-group-item d-flex justify-content-between align-items-center">
                            Guide
                            <span className="badge badge-primary badge-pill">14</span>
                        </li>
                        <li className="list-group-item d-flex justify-content-between align-items-center">
                            Ristoranti
                            <span className="badge badge-primary badge-pill">2</span>
                        </li>
                        <li className="list-group-item d-flex justify-content-between align-items-center">
                            POS
                            <span className="badge badge-primary badge-pill">1</span>
                        </li>
                    </ul>
                </Col>
                <Col lg="9">
                    <Card>
                        <Card.Body>
                            <ServerDataTable
                                ref={ref}
                                url="/fornitori"
                                columns={[
                                    {
                                        name: 'username',
                                        label: "Username",
                                        options: {
                                            customBodyRender: (cell, { rowIndex }) => {
                                                const row = ref.current.getRow(rowIndex)
                                                return <Link to={{ pathname: row._links.self, state: { esercente: row } }} >{cell}</Link>
                                            },
                                        },

                                    },
                                    {
                                        name: 'stato',
                                        label: ' ',
                                        options: {
                                            customBodyRender: (cell, { rowIndex }) => {
                                                const row = esercenti[rowIndex]
                                                return row.abilitato && <span className="text-success"><i className="fas fa-circle" title="Abilitato" /></span>
                                            },
                                        },
                                    },
                                ]}
                                options={{
                                    selectableRows: "none",
                                }}
                            />
                        </Card.Body>
                    </Card>
                </Col>
            </Row>
        </React.Fragment>
    )

}


export default connect(null, { setTopbarButtons, unsetTopbarButtons })(Fornitori);