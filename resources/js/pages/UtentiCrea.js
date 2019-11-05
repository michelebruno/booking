import React, { useState, useEffect  } from "react"
import { Card , Form, Button, Alert, Spinner} from "react-bootstrap"
import FormNuovoUtente from "../components/FormNuovoUtente"

const UtentiCrea = props => {
    return <Card>
            <Card.Body>
                <FormNuovoUtente />
            </Card.Body>
        </Card>
}

export default UtentiCrea