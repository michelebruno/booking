import React , {useState, useEffect} from "react"
import Row from "react-bootstrap/Row"
import Col from "react-bootstrap/Col"
import Overlay from "react-bootstrap/Overlay"
import Popover from "react-bootstrap/Popover"
import Form from "react-bootstrap/Form"
import Button from "react-bootstrap/Button"
import PropTypes from "prop-types"
import { connect } from "react-redux"


const NuovaTariffaPopover = ( { reference , show , url , tariffe, varianti , onClose , onSuccess , iva , ivaInclusa , ...props } ) => {

    if ( ! varianti ) return null;
    
    const [disponibili, setDisponibili] = useState( Object.values(varianti) )

    const keys = Object.keys(tariffe)

    const firstOption = _.head( disponibili ) ? _.head( disponibili ).id : undefined
    const [variante, setVariante] = useState( firstOption )
    const [prezzo, setPrezzo] = useState("")
    const [error, setError] = useState(false)

    const source = axios.CancelToken.source()

    useEffect(() => {
        let disponibili = _.filter( varianti, ( o ) => _.findIndex( keys , ( c ) => o.slug == c ) == -1 ) 
        setDisponibili(disponibili)
        setVariante( _.head( disponibili ) ? _.head( disponibili ).id : undefined )
        return source.cancel
    }, [tariffe])

    const handleSubmit = ( ) => {
        let requestData = { 
            variante
        }

        if ( ivaInclusa ) requestData.importo = prezzo
        else requestData.imponibile = prezzo

        axios.post( url , requestData , { cancelToken: source.token } )
            .then( res => {
                setPrezzo("")

                setVariante( false )

                setError(false)

                onSuccess(res.data)

                onClose( res )
            })
            .catch( error => {
                if ( error.response ) setError( error.response.data.message )
            })
    }

    
    return <Overlay target={reference.current} show={ show } placement="auto">
        { ( { show , ...props } ) => <Popover id="nuovatariffa" { ...props } >
            <Popover.Title className="bg-dark text-light d-flex justify-content-between"><span>Aggiungi tariffa</span><i className="fas fa-times align-self-center p-1 pl-3" onClick={ onClose } /> </Popover.Title>
            <Popover.Content className="p-3">
                { variante ? <Form onSubmit={ e => { e.preventDefault() ; handleSubmit() }} >

                    <Form.Group controlId="variante" as={Row}>
                        <Form.Label column >Variante</Form.Label>
                        <Col >
                            <Form.Control as="select" value={ variante } onChange={ e => { setVariante( e.target.value ) } } required>
                                { disponibili.map( ( v , index ) => { return <option value={ v.id } key={ v.id } >{ v.nome }</option> })}
                            </Form.Control>
                        </Col>
                    </Form.Group>

                    <Form.Group controlId="prezzo" as={Row}>
                        <Form.Label column >{ ivaInclusa ? "Importo" : "Imponibile" }</Form.Label>
                        <Col >
                            <Form.Control type="number" min="0" value={prezzo} onChange={ e => setPrezzo(e.target.value) } required/>
                        </Col>
                    </Form.Group>

                    <Form.Text className="text-danger">{ error }</Form.Text>

                    <div className="text-right">
                        <Button type="submit" variant="success" size="sm"><i className="fas fa-check mr-1" /> <span>Salva</span></Button>
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
    ivaInclusa: PropTypes.bool
}

NuovaTariffaPopover.defaultProps = {
    ivaInclusa : true
}

export default connect( state => { return { varianti : state.settings.varianti_tariffe } } )( NuovaTariffaPopover )