/* eslint-disable react/prop-types */
import React , { useState, useEffect } from 'react';

import Loader from '../components/Loader';

import { Row , Col , Card, Button  } from 'react-bootstrap';
import { connect } from "react-redux"
import { Link } from 'react-router-dom'
import BootstrapTable from 'react-bootstrap-table-next';
import PreLoaderWidget from '../components/Loader';
import { setTopbarButtons, unsetTopbarButtons } from '../_actions';

const Esercenti = ( props ) => {

    const [api, setApi] = useState({ status: "loading", data: null})

    useEffect(() => {
        props.setTopbarButtons( () => {
            return <Button size="sm" as={Link} to="/esercenti/crea">Nuovo</Button>
        })
        return () => {
            props.unsetTopbarButtons()
        };
    })

    useEffect(() => {

        const source = axios.CancelToken.source()

        axios.get("/esercenti", { cancelToken: source.token })
            .then( res => {
                setApi({ status : "success" , data: res.data })
            })
            .catch( error => {
                if ( axios.isCancel(error) )  return;
            })

        return () => source.cancel();

    }, [])

    const columns = [
        {
            dataField: 'username',
            text: "Username",
            // eslint-disable-next-line react/display-name
            formatter : (cell, row) => <Link to={{ pathname : "/esercenti/" + row.id , state : { esercente : row }} } >{cell}</Link>
        },
        {
            dataField: 'stato',
            text: '',
            formatter: ( cell , row ) => {
                { row.abilitato && <span className="text-success"><i className="fas fa-circle" title="Abilitato" /></span>}
            }
        }
    ]

    return(
        <React.Fragment>
            { /* preloader */}
            {props.loading && <div className="px-5"><Loader /></div>}
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
                            { api.status == "loading" && <PreLoaderWidget />}         
                            { api.data && <BootstrapTable
                                keyField="id"
                                data={api.data}
                                columns={columns}
                                bordered={ false }
                                hover
                                /> }
                        </Card.Body>
                    </Card>
                </Col>
            </Row>
        </React.Fragment>
    )

}


export default connect(null, { setTopbarButtons, unsetTopbarButtons })(Esercenti);