import React from "react"
import Form from "react-bootstrap/Form"

export const showErrorsFeedback = (errors, field) => {
    return (errors && typeof errors[field] !== 'undefined' ) && 
    <Form.Control.Feedback type="invalid">
        <ul>
            {errors[field].map( ( error, i ) => 
                <li key={i}>{error}</li>
            )}
        </ul>
    </Form.Control.Feedback>
}

export const isInvalid = ( errors , field ) => {
    if (errors && typeof errors[field] !== 'undefined' ) {
        return true
    }
}
