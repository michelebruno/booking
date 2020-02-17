import React , {useState, useEffect} from "react"
import Row from "react-bootstrap/Row"
import Col from "react-bootstrap/Col"
import Form from "react-bootstrap/Form"

import Popover from '@material-ui/core/Popover'
import Button from "react-bootstrap/Button"
import PropTypes from "prop-types"
import { connect } from "react-redux"
import { Typography } from "@material-ui/core"


const NuovaTariffaPopover = ( {  url , tariffe, varianti , onClose , onSuccess , anchorElement, iva , ivaInclusa } ) => {
    
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
    }, [ tariffe , keys , source.cancel , varianti ])

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


    if ( ! varianti ) return null;

    const open = Boolean(anchorElement)

    const id = open ? 'simple-popover' : undefined;
    return <Popover
        id={id}
        open={ open }
        anchorEl={ anchorElement }
        onClose={onClose}
        anchorOrigin={{
            vertical: 'bottom',
            horizontal: 'center',
        }}
        transformOrigin={{
            vertical: 'top',
            horizontal: 'center',
        }}
        >
        
        { variante ? <Form onSubmit={ e => { e.preventDefault() ; handleSubmit() }} >
                <Form.Group controlId="variante" as={Row}>
                    <Form.Label column >Variante</Form.Label>
                    <Col >
                        <Form.Control as="select" value={ variante } onChange={ e => { setVariante( e.target.value ) } } required>
                            { disponibili.map( ( v ) => { return <option value={ v.id } key={ v.id } >{ v.nome }</option> })}
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
            </Form> : <Typography>Hai già impostato tutte le varianti di prezzo per questo servizio. </Typography>
        }
    </Popover>
    
}

NuovaTariffaPopover.propTypes = {
    onClose : PropTypes.func.isRequired,
    onSuccess : PropTypes.func,
    url : PropTypes.string.isRequired,
    iva : PropTypes.number,
    ivaInclusa: PropTypes.bool,
    tariffe : PropTypes.object,
    varianti : PropTypes.object
}

NuovaTariffaPopover.defaultProps = {
    ivaInclusa : true
}

export default connect( state => { return { varianti : state.settings.varianti_tariffe } } )( NuovaTariffaPopover )