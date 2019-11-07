import React, { useState } from "react"
import { connect } from "react-redux"
import { Form , Card, InputGroup, Button, Spinner, FormGroup, Row, Col } from "react-bootstrap"
import { settingUpdated } from "../_actions"

const Settings = props => {
        
    const EditableField = ( { label, name , ...opts} ) => {

        const autoload = opts.autoload ? true : false

        const initialSetting = props.settings[name] ? props.settings[name] : "";

        const [editing, setEditing] = useState(false)

        const [sending, setSending] = useState(false)

        const [value, setValue] = useState(initialSetting)

        const [errors, setErrors] = useState(false)

        const dynamicProps = () => {
            
            let x = {};

            x.readOnly = !editing;

            if ( errors ) x.isInvalid = true

            return x
        }

        const showErrorsFeedback = () => {
            (errors && typeof errors.valore !== 'undefined' ) && 
                <Form.Control.Feedback type="invalid">
                    <ul>
                        {errors.valore.map( ( error, i ) => 
                            <li key={i}>{error}</li>
                        )}
                    </ul>
                </Form.Control.Feedback>
        }

        const handleSubmit = () => {
            setSending(true) ; 
            axios.put("/settings/" + name, { valore: value , autoload })
                .then( (res) => { 
                    setEditing(false); 
                    setSending("success")
                    return props.settingUpdated(res.data)

                } )
                .catch( error => { setErrors(error.response.data.errors) ; setSending(false) } )
        }

        return(
            <FormGroup as={Row} controlId={name} >                 
                { label && <Form.Label column md="3" onDoubleClick={() => setEditing(true)} >{label}</Form.Label>}
                <Col md="9">                    
                    <InputGroup onDoubleClick={() => setEditing(true)}>
                        <InputGroup.Prepend>
                            { sending && <InputGroup.Text>
                                { sending === true && <Spinner animation="border" variant="secondary" as="span" size="sm" /> }
                                { sending === "success" && <i className="fas fa-check" />  }
                            </InputGroup.Text> }
                        </InputGroup.Prepend>
                        <Form.Control {...dynamicProps()} value={value} onChange={ e => setValue(e.target.value)} onKeyPress={ e => { return e.charCode==13 ? handleSubmit() : null }} />
                        
                        { showErrorsFeedback() }
                        <InputGroup.Append>
                            { !editing && <Button variant="primary" onClick={() => setEditing(true)}><i className="fas fa-edit" /></Button> }
                            { editing && <React.Fragment>
                                <Button variant="primary" onClick={handleSubmit} ><i className="fas fa-check" /></Button>
                                <Button variant="primary" onClick={() => { setValue(initialSetting) ; setEditing(false) }} ><i className="fas fa-times" /></Button>
                            </React.Fragment>}
                        </InputGroup.Append>
                    </InputGroup>
                </Col>
            </FormGroup>
        )
    }

    return <React.Fragment> 
        <Card>
            <Card.Body> 
                <Form onSubmit={ e => e.preventDefault() }>
                    <EditableField name="base_url" autoload label="Url di base" />

                </Form>
            </Card.Body>
        </Card>
    </React.Fragment>
}

const mapStateToProps = ( { settings } )=> {
    return {
        settings : settings
    }
}


const settings = ( p ) => {
    return dispatch => {
        return dispatch( settingUpdated( p ) )
    }
};

export default connect( mapStateToProps , { settingUpdated: settingUpdated } )( Settings )