/* eslint-disable react/prop-types */
import React, { useState , useEffect } from "react"

import { connect } from "react-redux"

import Form from "react-bootstrap/Form" 
import Row from "react-bootstrap/Row" 
import Col from "react-bootstrap/Col" 
import Card from "react-bootstrap/Card"

import { settingUpdated } from "../_actions"

import EditableField from "../components/EditableField"

const SettingEditableField = ( props ) => {
    return <EditableField method="post" url="/settings" { ...props } />
}

const Settings = props => {

    const [ data, setData ] = useState(null);

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
                        <EditableField name="base_url" label="Url di base" url="/settings" method="put" />
                        <EditableField name="favicon" type="file" accept=".ico" url="/settings" method="put" isFile label="Favicon"/>
                        <Form onSubmit={ e => e.preventDefault}>
                            <Form.Group as={Row} controlId="favicon">
                                <Form.Label md="3" column >Favicon</Form.Label>
                                <Col md="9" >
                                    <Form.Control type="file" accept=".ico" />
                                </Col>
                            </Form.Group>

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