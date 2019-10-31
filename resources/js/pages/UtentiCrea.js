import React, { useState, useEffect  } from "react"
import { Card , Form, Button, Alert, Spinner} from "react-bootstrap"

const UtentiCrea = props => {
    
    const [api, setApi] = useState({ status: null, data: null});

    const [email, setEmail] = useState("");
    const [ruolo, setRuolo] = useState("account_manager");
    const [nome, setNome] = useState("")
    const [cognome, setCognome] = useState("")


    useEffect(() => {
        if (api.status == "sending") {
            console.log('Sending...')
            axios.post("/users", {
                email: email,
                ruolo: ruolo,
                meta : {
                    nome: nome,
                    cognome: cognome
                }
            }).then( res => setApi({status: "success", data: res.data})
            ).catch( res => setApi({status: 'error', data: res }) )
        }
    }, [api])

    console.log(api.data)

    return(
        <Card>
            <Card.Body>
                { api.status === "sending" && <Alert variant="secondary">
                    <Spinner animation="border" role="status">
                    <span className="sr-only">Loading...</span>
                    </Spinner>Invio...
                </Alert>}
                { api.status === "error" && <Alert variant="danger">
                    Sono stati riscontrati degli errori
                </Alert>}
                {<Form onSubmit={ e => { e.preventDefault(); return setApi({status: "sending"}); }} >
                    <Form.Group controlId="email">
                        <Form.Label>Email</Form.Label>
                        <Form.Control type="email" value={email} onChange={ e => setEmail(e.target.value) } />
                    </Form.Group>
                    <Form.Group controlId="ruolo">
                        <Form.Label>Ruolo account</Form.Label>
                        <Form.Control as="select" value={ruolo} onChange={ e => setRuolo(e.target.value) }>
                            <option value="admin">Admin</option>
                            <option value="account_manager">Account manager</option>
                            {/* <option value="esercente">Esercente</option>
                            <option value="cliente">Cliente</option> */}
                        </Form.Control>
                    </Form.Group>
                    
                    <Form.Group controlId="nome">
                        <Form.Label>Nome</Form.Label>
                        <Form.Control type="text" value={nome} onChange={ e => setNome(e.target.value) } />
                    </Form.Group>
                    <Form.Group controlId="cognome">
                        <Form.Label>Cognome</Form.Label>
                        <Form.Control value={cognome} onChange={ e => setCognome(e.target.value) } />
                    </Form.Group>
                    <Button variant="primary" type="submit" >Invia</Button>
                </Form>}
            </Card.Body>
        </Card>
    )
}

export default UtentiCrea