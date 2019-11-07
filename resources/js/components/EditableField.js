import React, { useState } from "react"
import PropTypes from "prop-types"
import { Button, Form, FormGroup, Row, Col, InputGroup , Spinner } from "react-bootstrap"
import dot from "dot-object"

window.dot = dot

const EditableField = ( { label, target , name, initialValue, onSuccess, ...props} ) => {
    
    const [editing, setEditing] = useState(false)
    
    const [sending, setSending] = useState(false)
    
    const [value, setValue] = useState(initialValue)
    
    const [errors, setErrors] = useState(false)
    
    const dynamicProps = () => {
        
        let x = {};
        
        x.readOnly = !editing
        
        x.disabled = !editing
        x.plaintext = !editing
        
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
        setSending(true) ;

        let data = dot.str(name, value, {});
   

        axios({
            method: props.method ? props.method : "put",
            url: props.url,
            data: data
        }) 
        .then( (res) => { 
            setEditing(false); 
            setSending("success") 
            if ( onSuccess ) onSuccess(res.data)
            else console.warn("No onSucces function")
            
        })  
        .catch( error => { 
            if (error.response) {
                setErrors(error.response.data.errors)
                setSending(false) 
                
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
        if (!props.children) return value;

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
        <Form.Label column md="3" onDoubleClick={() => setEditing(true)} >{ label && label}</Form.Label>
        <Col md="9">                    
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
            { !editing && <div className="d-flex justify-content-between"><span className="align-self-center">{displayValue()}</span><Button variant="outline-primary" onClick={() => setEditing(true)}><i className="fas fa-edit" /></Button></div> }

        </Col>
    </FormGroup>
}

EditableField.propTypes = {
    name : PropTypes.string.isRequired,
    label : PropTypes.string,
    url: PropTypes.string.isRequired,
    method: PropTypes.oneOf(['post', 'put']),
    onSuccess: PropTypes.func
}

export default EditableField