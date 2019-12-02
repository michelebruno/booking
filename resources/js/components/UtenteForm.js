import React, { useState, useEffect } from "react"
import { Form, Button, Alert, Spinner } from "react-bootstrap"
import { Link } from 'react-router-dom'
import { showErrorsFeedback , isInvalid } from "../_services/formValidation";

const UtenteForm =  props => {
    
    const [api, setApi] = useState({ status: null, data: null});

    const [email, setEmail] = useState("");
    const [username, setUsername] = useState("")
    const [password, setPassword] = useState("")
    const [passwordConfirmation, setPasswordConfirmation] = useState("")
    const [ruolo, setRuolo] = useState("account_manager");
    const [nome, setNome] = useState("")

    const handleSubmit = e => {
        e.preventDefault()
        return setApi({status: "sending"})
    }

    useEffect(() => {
        if (api.status == "sending") { 
            axios.post("/users", {
                email,
                username,
                ruolo,
                password,
                password_confirmation: passwordConfirmation,
                nome
            }).then( res => setApi({status: "success", data: res.data})
            ).catch( res => {
                setApi({status: "error", data: res.response.data })
            })
        }
    }, [api])

    const errors = ( api.status === "error" && typeof api.data.errors !== 'undefined' ) ? api.data.errors : false;


    const dynamicProps = field => { 
        let props = {}
        if (api.status === "success") {
            return { readOnly : true, plaintext: true }
        }

        props.isInvalid = isInvalid(errors, field)

        return props
    }

    return(
        <Form onSubmit={handleSubmit} > 
        { api.status === "sending" && <Alert variant="secondary">
            <Spinner animation="border" role="status"> 
            <span className="sr-only"> Loading...</span>
            </Spinner>Invio...
        </Alert> }
        { api.status === "error" && <Alert variant="danger">
            Sono stati riscontrati degli errori.
        </Alert>} 
        { api.status === "success" && <Alert variant="success">
            <Link to={"/utenti/" + api.data.id }>Profilo</Link> creato correttamente.
        </Alert>}  
                <React.Fragment>
                    <Form.Group controlId="email">
                        <Form.Label>Email</Form.Label>
                        <Form.Control type="email" required {...dynamicProps("email") } value={email} onChange={ e => setEmail(e.target.value) } />
                        { showErrorsFeedback(errors, "email") }
                    </Form.Group>
                    <Form.Group controlId="username">
                        <Form.Label>Username</Form.Label>
                        <Form.Control required value={username} onChange={ e => setUsername(e.target.value) } { ...dynamicProps("username")} />
                        { showErrorsFeedback(errors, "username") }
                    </Form.Group>
                    <Form.Group controlId="password">
                        <Form.Label>Password</Form.Label>
                        <Form.Control type="password" required value={password} onChange={ e => setPassword(e.target.value) } { ...dynamicProps("password") }/>
                        { showErrorsFeedback(errors, "password") }
                    </Form.Group>
                    <Form.Group controlId="password_confirmation">
                        <Form.Label>Password</Form.Label>
                        <Form.Control type="password" required value={passwordConfirmation} onChange={ e => setPasswordConfirmation(e.target.value) } { ...dynamicProps("password_confirmation") } />
                        { showErrorsFeedback(errors, "password_confirmation") }
                    </Form.Group>
                    <Form.Group controlId="ruolo">
                        <Form.Label>Ruolo account</Form.Label>
                        <Form.Control as="select" required value={ruolo} onChange={ e => setRuolo(e.target.value) } { ...dynamicProps("ruolo")}>
                            <option value="admin">Admin</option>
                            <option value="account_manager">Account manager</option>
                            {/* <option value="esercente">Esercente</option>
                            <option value="cliente">Cliente</option> */}
                        </Form.Control>
                    </Form.Group> 
                </React.Fragment>
                <React.Fragment>
                    <Form.Group controlId="nome">
                        <Form.Label>Nome</Form.Label>
                        <Form.Control type="text" value={nome} onChange={ e => setNome(e.target.value) } />
                    </Form.Group>
                    <Button type="submit" >Invia</Button>
                </React.Fragment>
            
        </Form>
    )
}

export default UtenteForm