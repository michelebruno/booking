import React, { useState , useEffect } from 'react'
import { connect } from 'react-redux'
import { Card } from 'react-bootstrap'

const Clienti = () => {

    const [api, setApi] = useState()

    if ( !api ) return "Loading";

    useEffect( () => {
        if ( api && ! api.willBeReloaded ) return;
        const source = axios.cancelToken.source
        axios.get('/clienti', { cancelToken : source.token })
            .then( res => setApi(res.data) )
        return () => {
            source.cancel()
        };
    }, [api])

    return <Card>
        <Card.Body>
            Clienti
        </Card.Body>
    </Card>

}


export default connect()(Clienti)