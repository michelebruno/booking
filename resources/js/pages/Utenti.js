import React, {useState} from "react"
import { Card, Button, Modal } from "react-bootstrap"
import BootstrapTable from "react-bootstrap-table-next"
import { Redirect } from "react-router-dom"
import paginationFactory from "react-bootstrap-table2-paginator"
import FormNuovoUtente from "../components/FormNuovoUtente"

const Utenti = ( props ) => {

    const [ fetched, setFetched ] = useState(false)
    const [ redirect, setRedirect ] = useState(false)

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
            .then( ({ data }) => { 
                setFetched( data )
            })
    }, [])
    return(
        <Card>
            { redirect && <Redirect to={redirect} push /> }
            <Card.Body>
                
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

                {fetched && fetched.data && 
                <BootstrapTable
                    columns={columns}
                    keyField="id"
                    data={fetched.data}
                    rowEvents={rowEvents}
                    pagination={ paginationFactory() }
                />}
                {!fetched && <span>Carico i risultati...</span>}
            </Card.Body>
        </Card>
    )
}



export default Utenti;