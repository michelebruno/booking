import React, { useState, useEffect } from "react"
import { Form, Button, Alert, Spinner } from "react-bootstrap"
import StepWizard from 'react-step-wizard';
import { Link } from 'react-router-dom'

const FormNuovoUtente = props => {
    
    const [api, setApi] = useState({ status: null, data: null});

    const [email, setEmail] = useState("");
    const [username, setUsername] = useState("")
    const [password, setPassword] = useState("")
    const [passwordConfirmation, setPasswordConfirmation] = useState("")
    const [ruolo, setRuolo] = useState("account_manager");
    const [nome, setNome] = useState("")
    const [cognome, setCognome] = useState("")

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
                meta : {
                    nome,
                    cognome
                }
            }).then( res => setApi({status: "success", data: res.data})
            ).catch( res => {
                setApi({status: "error", data: res.response.data })
            })
        }
    }, [api])

    const errors = ( api.status === "error" && typeof api.data.errors !== 'undefined' ) ? api.data.errors : false;

    const showErrorsFeedback = field => {
        return (errors && typeof errors[field] !== 'undefined' ) && 
        <Form.Control.Feedback type="invalid">
            <ul>
                {errors[field].map( ( error, i ) => 
                    <li key={i}>{error}</li>
                )}
            </ul>
        </Form.Control.Feedback>
    }

    const dynamicProps = field => { 
        let props = {}
        if (api.status === "success") {
            return { readOnly : true, plaintext: true }
        }
        if (errors && typeof errors[field] !== 'undefined' ) {
            props.isInvalid = true
        } 

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
                        { showErrorsFeedback("email") }
                    </Form.Group>
                    <Form.Group controlId="username">
                        <Form.Label>Username</Form.Label>
                        <Form.Control required value={username} onChange={ e => setUsername(e.target.value) } { ...dynamicProps("username")} />
                        { showErrorsFeedback("username") }
                    </Form.Group>
                    <Form.Group controlId="password">
                        <Form.Label>Password</Form.Label>
                        <Form.Control type="password" required value={password} onChange={ e => setPassword(e.target.value) } { ...dynamicProps("password") }/>
                        { showErrorsFeedback("password") }
                    </Form.Group>
                    <Form.Group controlId="password_confirmation">
                        <Form.Label>Password</Form.Label>
                        <Form.Control type="password" required value={passwordConfirmation} onChange={ e => setPasswordConfirmation(e.target.value) } { ...dynamicProps("password_confirmation") } />
                        { showErrorsFeedback("password_confirmation") }
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
                    <Form.Group controlId="cognome">
                        <Form.Label>Cognome</Form.Label>
                        <Form.Control value={cognome} onChange={ e => setCognome(e.target.value) } />
                    </Form.Group>
                    <Button type="submit" >Invia</Button>
                </React.Fragment>
            
        </Form>
    )
}

export default FormNuovoUtente