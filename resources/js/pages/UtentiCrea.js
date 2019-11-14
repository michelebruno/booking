import React from "react"
import { Card } from "react-bootstrap"
import FormNuovoUtente from "../components/FormNuovoUtente"

const UtentiCrea = props => {
    return <Card>
            <Card.Body>
                <FormNuovoUtente />
            </Card.Body>
        </Card>
}

export default UtentiCrea