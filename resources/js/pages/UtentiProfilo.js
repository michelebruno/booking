import React, {useState, useEffect } from "react"
import { Card } from "react-bootstrap"

const Profilo = (props) => {

    const { match } = props

    const [utente, setUtente] = useState({})

    useEffect(() => {
        axios.get("/users/"+match.params.id)
            .then( res => {
                console.log(res)
                setUtente(res.data.data)
            }
            )
            
    })

    return (
        <React.Fragment>
            { utente && <Card>
                <Card.Body>
                    <h1>{utente.email}</h1>
                </Card.Body>
            </Card>}
        </React.Fragment>
    )
    
}

export default Profilo;