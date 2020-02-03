import React, { useState , useEffect } from "react"
import PropTypes from "prop-types" 
import Button from "react-bootstrap/Button"
import Form  from "react-bootstrap/Form"
import FormGroup  from "react-bootstrap/FormGroup"
import Row  from "react-bootstrap/Row"
import Col  from "react-bootstrap/Col"
import InputGroup  from "react-bootstrap/InputGroup"
import Spinner  from "react-bootstrap/Spinner"
import dot from "dot-object" 

const EditableField = ( { label, noLabel, initialValue, method, name, onSuccess, textMutator, append, prepend , url, isImage, ...props} ) => {
    
    const [editing, setEditing] = useState(false)
    
    const [sending, setSending] = useState(false)
    
    const [value, setValue] = useState(initialValue)

    const [file, setFile] = useState()
    
    const [errors, setErrors] = useState(false)

    const source = axios.CancelToken.source()

    useEffect(() => {
        return source.cancel
    }, [] )
    
    const dynamicProps = () => {
        
        let x = {};
        
        x.readOnly = x.disabled = x.plaintext = !editing
        
        if ( errors ) x.isInvalid = true

        return x
    }
    
    const showErrorsFeedback = () => {

        (errors && typeof errors[name] !== 'undefined' ) && 
        <Form.Control.Feedback type="invalid">
            <ul>
                {errors[name].map( ( error, i ) => 
                    <li key={i}>{error}</li>
                    )}
            </ul>
        </Form.Control.Feedback>
    }
        
    const handleSubmit = () => {

        if ( ! editing ) return;

        setSending(true) ;
    
        let actual_method = method

        let data;
        let headers = { }

        if ( props.type && props.type === "file" ) {

            let formData = new FormData();

            formData.append(name, file);

            formData.append("_method", method)

            data = formData 

            actual_method = "POST"

            headers = {
                'Content-Type': 'multipart/form-data'
            }

        } else data = dot.str(name, value, {});

        axios({
            method : actual_method,
            url,
            data,
            headers,
            cancelToken : source.token
        })
            .then( (res) => {

                setSending("success") 
                
                if (res.data && res.data[name] ) {
                    setValue(res.data[name])
                }
                
                setTimeout( () => {                    
                    
                    setSending(false)
                    setEditing(false); 

                    if ( onSuccess ) onSuccess(res.data)
                    else process.env == "local" && console.warn("No onSuccess function")
                }, 3000)
                
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


    const displayValue = () => {
        
        if ( ! props.children ) return( prepend + value + append );

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

        const onChange = ( e ) => {

            if ( props.type && props.type === "file" ) {
                setFile(e.target.files[0])
            } 

            setValue( textMutator(e.target.value) )

        }
        
        return <Form.Control { ...props } value={ ( isImage ) ? undefined : value } onChange={ onChange } onKeyPress={ e => { return e.charCode == 13 ? handleSubmit() : e } } { ...dynamicProps() }  />
    }

    return <FormGroup as={Row} controlId={name} >
        { ! noLabel && <Form.Label column xs={12} md="3" onDoubleClick={() => setEditing(true)} >{ label && label}</Form.Label> }
        <Col xs={12} md={noLabel ? 12 : 9} >                    
            { editing && <InputGroup>

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
                    <Button variant="outline-primary" onClick={() => { if ( props.readOnly ) return; setValue(initialValue) ; setEditing(false) }} ><i className="fas fa-times" /></Button>
                </InputGroup.Append>
                </InputGroup> }

            { ! editing && <div className="d-flex justify-content-between h-100">
                <span className="align-self-center">{ displayValue() }</span>
                { !props.readOnly && <Button variant="outline-primary" onClick={() => setEditing(true)}><i className="fas fa-edit" /></Button> }
            </div> }

        </Col>
    </FormGroup>
}

EditableField.propTypes = {
    append : PropTypes.oneOfType([
        PropTypes.string,
        PropTypes.number
    ]),
    children : PropTypes.node,
    isFile : PropTypes.bool,
    isImage : PropTypes.bool,
    initialValue : PropTypes.oneOfType([
        PropTypes.string,
        PropTypes.number
    ]),
    name : PropTypes.string,
    label : PropTypes.string,
    method: PropTypes.oneOf(['POST', 'PUT', 'PATCH']),    
    noLabel : PropTypes.bool,
    onSuccess: PropTypes.func,
    prepend : PropTypes.oneOfType([
        PropTypes.string,
        PropTypes.number
    ]),
    readOnly : PropTypes.bool,
    textMutator : PropTypes.func,
    type : PropTypes.string,
    url: PropTypes.string.isRequired
}

EditableField.defaultProps = {
    textMutator : str => str,
    method: "PUT",
    append : "",
    prepend: ""
}


export default EditableField
