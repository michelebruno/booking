import React, { useState , useEffect } from "react"

import { connect } from "react-redux"

import Form from "react-bootstrap/Form" 
import Row from "react-bootstrap/Row" 
import Col from "react-bootstrap/Col" 
import Card from "react-bootstrap/Card"

import { settingUpdated } from "../_actions"

import EditableField from "../components/EditableField"

const Settings = props => {

    const [data, setData] = useState(null);

    useEffect(() => {
        const source = axios.CancelToken.source()

        axios.get('settings', { cancelToken : source.token })
            .then( result => setData( result.data ) )
            .catch( error =>{
                if ( axios.isCancel(error) ) return;
            })
        
        return () => {
            source.cancel()
        };
    }, [])

    return <React.Fragment>
        <Row>
            <Col lg="6">
                <Card>
                    <Card.Body> 
                        <Form onSubmit={ e => e.preventDefault() }>
                            <EditableField name="base_url" label="Url di base" url="/settings" method="put" />
                        </Form>
                    </Card.Body>
                </Card>
            </Col>
        </Row>
    </React.Fragment>
}

const mapStateToProps = ( { settings } )=> {
    return {
        settings : settings
    }
}


const settings = ( p ) => {
    return dispatch => {
        return dispatch( settingUpdated( p ) )
    }
};

export default connect( mapStateToProps , { settingUpdated: settingUpdated } )( Settings )