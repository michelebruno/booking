import React, {useState} from "react"
import { Card } from "react-bootstrap"
import BootstrapTable from "react-bootstrap-table-next"
import { Redirect } from "react-router-dom"

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
            text: 'Username'
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
            console.log(row)
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
                {fetched && fetched.data && 
                <BootstrapTable
                    columns={columns}
                    keyField="id"
                    data={fetched.data}
                    rowEvents={rowEvents}
                />}
                {!fetched && <span>Carico i risultati...</span>}
            </Card.Body>
        </Card>
    )
}



export default Utenti;