import React , {useState} from "react"
import Row from "react-bootstrap/Row"
import Col from "react-bootstrap/Col"
import Overlay from "react-bootstrap/Overlay"
import Popover from "react-bootstrap/Popover"
import Form from "react-bootstrap/Form"
import Button from "react-bootstrap/Button"
import PropTypes from "prop-types"


const NuovaTariffaPopover = ( { reference , show , url , varianti , onClose , onSuccess , ...props } ) => {
    let defaulVariant = ( varianti && varianti[0] ) ? varianti[0].id : false
    const [variante, setVariante] = useState(defaulVariant)
    const [imponibile, setImponibile] = useState("")
    const [error, setError] = useState(false)

    const handleSubmit = ( ) => {
        axios.post( url , { imponibile , variante } )
            .then( res => {
                onSuccess(res.data)
                onClose( res )
            })
            .catch( error => {
                if ( error.response ) setError( error.response.data.message )
            })
    }

    return <Overlay target={reference.current} show={ show } placement="auto">
        { ( { show , ...props }) => <Popover id="nuovatariffa" { ...props } >
            <Popover.Title className="bg-dark text-light d-flex justify-content-between"><span>Aggiungi tariffa</span><i className="fas fa-times align-self-center p-1 pl-3" onClick={ onClose } /> </Popover.Title>
            <Popover.Content className="p-3">
                { defaulVariant ?  <Form onSubmit={ e => {e.preventDefault() ; handleSubmit() }} >

                    <Form.Group controlId="variante" as={Row}>
                        <Form.Label column >Variante</Form.Label>
                        <Col >
                            <Form.Control as="select" value={ variante } onChange={ e => setVariante(e.target.value ) } required>
                                { varianti.map( (variante , index ) => <option value={variante.id} key={variante.id} defaultValue={index === 0} >{ variante.nome }</option> )}
                            </Form.Control>
                        </Col>
                    </Form.Group>
                    <Form.Group controlId="imponibile" as={Row}>
                        <Form.Label column >Imponibile</Form.Label>
                        <Col >
                            <Form.Control type="number" step="0.01" min="0" value={imponibile} onChange={ e => setImponibile(e.target.value) } required/>
                        </Col>
                    </Form.Group>
                    <div className="text-right">
                        <Button type="submit" variant="success" size="sm"><i className="fas fa-check mr-1" />  <span>Salva</span></Button>
                    </div>
                </Form> : "Hai già impostato tutte le varianti di prezzo per questo servizio." }

            </Popover.Content>
        </Popover>}
    </Overlay>
}

NuovaTariffaPopover.propTypes = {
    onClose : PropTypes.func.isRequired,
    onSuccess : PropTypes.func,
    show : PropTypes.bool.isRequired,
    url : PropTypes.string.isRequired,
    varianti : PropTypes.arrayOf( PropTypes.object ).isRequired
}

export default NuovaTariffaPopover