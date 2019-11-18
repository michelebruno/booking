import React, {useState, useEffect } from "react"
import { Card, Form, Row, Col , Button, Spinner } from "react-bootstrap"
import PreLoaderWidget from "../components/Loader"
import EditableField from "../components/EditableField"

const Profilo = ( { match , ...props }) => {

    let u = false;

    if ( props.location.state && props.location.state.utente ) u = props.location.state.utente
    else if ( typeof props.utente == 'object' ) u = props.utente

    const [ utente, setUtente ] = useState(u)

    const [ resettingPassword, setResettingPassword ] = useState(false)

        
    useEffect(() => {

        if ( ! utente ) {
            
            const source = axios.CancelToken.source()
    
            axios.get("/users/"+match.params.id, { cancelToken: source.token })
                .then( res => {
                    setUtente(res.data)
                })
                .catch( error => {
                    if ( axios.isCancel(error) )  return;
                })
    
            return () => source.cancel();

        }

    }, [match.params.id] )

    const fieldValue = field => ( typeof utente[field] !== 'undefined' && utente[field] ) ? utente[field] : "" 

    const APIurl = "/users/" + utente.id;

    return (
        <React.Fragment>
            <Row>
                <Col lg="6">
                    <Card>
                        <Card.Body>
                            { !utente && <div className="py-5"><PreLoaderWidget /></div>}
                            { utente && <Form onSubmit={ e => e.preventDefault() }> 
                                <EditableField name={"email"} initialValue={ fieldValue("email")} url={APIurl} label="Email" onSuccess={ setUtente } />
                                <EditableField name={"username"} initialValue={ fieldValue("username")} url={APIurl} label="Username" onSuccess={ setUtente } />

                                <Form.Group as={Row} controlId="password">
                                    <Form.Label column md="3">Password</Form.Label>
                                    <Col md="8" >{ resettingPassword !== "success" && <Button onClick={ () => {
                                            setResettingPassword("resetting")
                                            axios.post("/password/email", { email: utente.email }, { baseURL: "" })
                                                .then(
                                                    res => { setResettingPassword("success") }
                                                ).catch( error => {
                                                    setResettingPassword(false)
                                                    window.alert(
                                                        error.response.message
                                                    )
                                                })
                                        }} >Reset della password { resettingPassword === "resetting" && <Spinner animation="border" variant="secondary" as="span" size="sm" role="status" /> }</Button>
                                    }
                                    { resettingPassword === "success" && <Button variant="outline-success" disabled>Link per il reset inviato.</Button>  }
                                    </Col>
                                </Form.Group>
                                <EditableField name="nome" initialValue={ fieldValue("nome") } url={APIurl} label="Nome" onSuccess={setUtente}/>

                                <EditableField as="select" name={"ruolo"} initialValue={ fieldValue("ruolo")} url={APIurl} label="Ruolo" onSuccess={setUtente}>
                                    <option value="admin" defaultValue>Admin</option>
                                    <option value="account_manager" defaultValue>Account Manager</option>
                                </EditableField> 

                            </Form> }
                        </Card.Body>
                    </Card>
                </Col>

            </Row>
        </React.Fragment>
    )
    
}

export default Profilo;