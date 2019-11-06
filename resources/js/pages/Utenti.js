import React, {useState} from "react"
import { Card, Button, Modal, Alert } from "react-bootstrap"
import BootstrapTable from "react-bootstrap-table-next"
import { Redirect } from "react-router-dom"
import paginationFactory from "react-bootstrap-table2-paginator"
import FormNuovoUtente from "../components/FormNuovoUtente"
import PreLoaderWidget from "../components/Loader"

const Utenti = ( props ) => {

    const [api, setApi] = useState({status: "loading", data: null})
    const [ redirect, setRedirect ] = useState(false)

    window.api = api

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
            dataField: 'none',
            text: ''
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

    const [aggiungiModal, setAggiungiModal] = useState(false)

    React.useEffect(() => {
        axios.get('/users')
            .then( ( response ) => { 
                setApi({ status: "loaded", data: response.data.data })
            })
            .catch( error => {
                setApi({status: "error" , response: error.response, message: error.response.data.message })
            })
    }, [])
    return(
        <Card>
            { redirect && <Redirect to={redirect} push /> }
            <Card.Body>
                <p>
                    <Button onClick={() => setAggiungiModal(true)} >Aggiungi utente</Button>
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
                    rowEvents={rowEvents}
                    pagination={ paginationFactory() }
                />}
                { api.status === "loading" && <p className="p-5"><PreLoaderWidget /></p>}
                { api.status === "error" && <Alert variant="danger">
                    { api.message }
                </Alert>}
            </Card.Body>
        </Card>
    )
}



export default Utenti;