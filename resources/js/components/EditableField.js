import React, { useState } from "react"
import PropTypes from "prop-types" 
import Button from "react-bootstrap/Button"
import Form  from "react-bootstrap/Form"
import FormGroup  from "react-bootstrap/FormGroup"
import Row  from "react-bootstrap/Row"
import Col  from "react-bootstrap/Col"
import InputGroup  from "react-bootstrap/InputGroup"
import Spinner  from "react-bootstrap/Spinner"
import dot from "dot-object" 

const EditableField = ( { label, noLabel, target , name, initialValue, onSuccess, isFile, ...props} ) => {
    
    const [editing, setEditing] = useState(false)
    
    const [sending, setSending] = useState(false)
    
    const [value, setValue] = useState(initialValue)
    
    const [errors, setErrors] = useState(false)
    
    const dynamicProps = () => {
        
        let x = {};
        
        x.readOnly = x.disabled = x.plaintext = !editing
        
        if ( errors ) x.isInvalid = true
        
        return x
    }
    
    const showErrorsFeedback = () => {

        (errors && typeof errors[target] !== 'undefined' ) && 
        <Form.Control.Feedback type="invalid">
            <ul>
                {errors[target].map( ( error, i ) => 
                    <li key={i}>{error}</li>
                    )}
            </ul>
        </Form.Control.Feedback>
    }
        
    const handleSubmit = () => {

        if ( ! editing ) return;

        setSending(true) ;

        let data = dot.str(name, value, {});
        let headers = {}

        if ( isFile ) {
            data = new FormData()
            data.append(name, value)
            headers['Content-Type'] = 'multipart/form-data'
        }
   
        axios({
            method: props.method ? props.method : "patch",
            url: props.url,
            data,
            headers
        })
            .then( (res) => { 
                setEditing(false); 
                setSending("success") 
                if ( onSuccess ) onSuccess(res.data)
                else console.warn("No onSuccess function")
                
            })  
            .catch( error => { 
                if (error.response) {

                    setSending(false)

                    if (error.response.status === 422) { // Errore di validazione
                        setErrors(error.response.data.errors)
                    } 
                    
                } else {
                    console.error(error)
                }
            } )
    }

    if (sending === "success") {
        setTimeout(() => {
            setSending(false)
        }, 3000)
    }

    const displayValue = () => {
        if ( ! props.children ) return value;

        let options = [];

        props.children.forEach(child => {
            if ( child.type === "option" ) options.push(child)
        });

        if ( options.length ) {
            return options.map( opt => {
                if (opt.props.value === value) {
                    return opt.props.children
                }
            })
        } 

    }
    
    const Control = () => {
        return <Form.Control key="1" {...props} { ...dynamicProps() } value={value} onChange={ e => setValue(e.target.value)} onKeyPress={ e => { return e.charCode == 13 ? handleSubmit() : e }} />
    }

    return <FormGroup as={Row} controlId={target} >
        { ! noLabel && <Form.Label column md="3" onDoubleClick={() => setEditing(true)} >{ label && label}</Form.Label> }
        <Col md={noLabel ? 12 : 9}>                    
            { editing && <InputGroup onDoubleClick={() => setEditing(true)}>

                <InputGroup.Prepend>
                    { sending && <InputGroup.Text>
                        { sending === true && <Spinner animation="border" variant="secondary" as="span" size="sm" /> }
                        { sending === "success" && <i className="fas fa-check" />  }
                    </InputGroup.Text> }
                </InputGroup.Prepend>

                { Control() }

                { showErrorsFeedback() }

                <InputGroup.Append>
                    <Button variant="outline-primary" onClick={ handleSubmit } ><i className="fas fa-check" /></Button>
                    <Button variant="outline-primary" onClick={() => { setValue(initialValue) ; setEditing(false) }} ><i className="fas fa-times" /></Button>
                </InputGroup.Append>
                </InputGroup> }

            { ! editing && <div className="d-flex justify-content-between"><span className="align-self-center">{displayValue()}</span><Button variant="outline-primary" onClick={() => setEditing(true)}><i className="fas fa-edit" /></Button></div> }

        </Col>
    </FormGroup>
}

EditableField.propTypes = {
    name : PropTypes.string.isRequired,
    label : PropTypes.string,
    noLabel : PropTypes.bool,
    url: PropTypes.string.isRequired,
    method: PropTypes.oneOf(['post', 'put', 'patch']),
    onSuccess: PropTypes.func
}

export default EditableField