import React, { useState } from "react"

import { connect } from "react-redux"

import Card from "react-bootstrap/Card"
import Button from "react-bootstrap/Button"
import ButtonToolbar from "react-bootstrap/ButtonToolbar"
import Modal  from "react-bootstrap/Modal"
import Alert from "react-bootstrap/Alert"

import BootstrapTable from "react-bootstrap-table-next"
import { Redirect , Link } from "react-router-dom"
import paginationFactory from "react-bootstrap-table2-paginator"
import FormNuovoUtente from "../components/FormNuovoUtente"
import PreLoaderWidget from "../components/Loader"

import { setTopbarButtons , unsetTopbarButtons } from "../_actions"

const Utenti = ( props ) => {

    const [api, setApi] = useState({status: "loading", data: null})
    const [ redirect, setRedirect ] = useState(false)
    const [aggiungiModal, setAggiungiModal] = useState(false)

    const columns = [ 
        {
            dataField: 'email',
            text : 'Email'
        },
        {
            dataField: 'username',
            text: 'Username',
            formatter: ( cell , row ) => {
                return row.denominazione ? row.denominazione : cell
            }
        },
        {
            dataField: 'ruolo',
            text: 'Tipo'
        },
        {
            dataField: 'meta',
            text: '',
            formatter: ( cell , row ) => {
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
    ]

    const rowEvents = {
        onDoubleClick: ( e, row ) => { 
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

            setRedirect(url+row.id)
        }
    }

    props.setTopbarButtons( () => <ButtonToolbar className="d-inline-block" >
        <Button onClick={() => setAggiungiModal(true)} >Aggiungi utente</Button>
    </ButtonToolbar>)

    React.useEffect(() => {

        const source = axios.CancelToken.source()
        axios.get('/users' , { cancelToken: source.token } )
            .then( ( response ) => { 
                setApi({ status: "loaded", data: response.data })
            })
            .catch( error => {
                if ( axios.isCancel(error) )  return;
                setApi({status: "error" , response: error.response, message: error.response.data.message })
            })
 
        return () => {
            source.cancel();
            props.unsetTopbarButtons()
        }

    }, [])

    return(
        <Card>
            { redirect && <Redirect to={redirect} push /> }
            <Card.Body>
                <p>
                    <Modal show={aggiungiModal} onHide={() => setAggiungiModal(false)}>
                        <Modal.Header closeButton>
                            <Modal.Title>
                                Crea nuovo utente
                            </Modal.Title>
                        </Modal.Header>
                        <Modal.Body>
                            <FormNuovoUtente />
                        </Modal.Body>
                    </Modal>
                </p>

                {api.status === "loaded" && api.data && 
                    <BootstrapTable
                        columns={columns}
                        keyField="id"
                        data={api.data}
                        // rowEvents={rowEvents}
                        pagination={ paginationFactory() }
                        hover
                        bordered={false}
                        wrapperClasses="table-responsive"
                    />
                    }
                { api.status === "loading" && <div className="p-5"><PreLoaderWidget /></div>}
                { api.status === "error" && <Alert variant="danger">
                    { api.message }
                </Alert> }
            </Card.Body>
        </Card>
    )
}



export default connect( null, { setTopbarButtons, unsetTopbarButtons })( Utenti );