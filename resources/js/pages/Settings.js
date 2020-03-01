/* eslint-disable react/prop-types */
import React, { useState, useEffect } from "react"

import { connect } from "react-redux"

import Row from "react-bootstrap/Row"
import Col from "react-bootstrap/Col"
import Card from "react-bootstrap/Card"

import { settingUpdated } from "../_actions"

import EditableField from "../components/EditableField"

const Settings = ({ settings }) => {

    const [data, setData] = useState(settings);

    useEffect(() => {
        const source = axios.CancelToken.source()

        axios.get('settings', { cancelToken: source.token })
            .then(result => setData(result.data))
            .catch(error => {
                if (axios.isCancel(error)) return;
            })

        return () => {
            source.cancel()
        };
    }, [])

    const SettingsEditableField = (props) => {

        const name = props.name || props.url.split('/')[2];

        let initialValue = props.initialValue

        if (typeof initialValue === "undefined") {
            initialValue = (settings && settings[name]) ? settings[name] : undefined
        }
        return <EditableField onSuccess={settingUpdated} name={name} initialValue={initialValue} {...props} />

    }

    return <React.Fragment>
        <Row>
            <Col lg="6">
                <Card>
                    <Card.Body>
                        <SettingsEditableField name="favicon" type="file" accept=".ico" url="/settings/favicon" isImage label="Favicon" />
                        <SettingsEditableField name="PAYPAL_CLIENT_ID" url="/settings/PAYPAL_CLIENT_ID" label="PayPal Client ID" type="textarea" />
                        <SettingsEditableField name="PAYPAL_CLIENT_SECRET" url="/settings/PAYPAL_CLIENT_SECRET" label="PayPal Client Secret" type="password" />
                    </Card.Body>
                </Card>
            </Col>
        </Row>
    </React.Fragment>
}

const mapStateToProps = ({ settings }) => {
    return {
        settings: settings
    }
}

export default connect(mapStateToProps, { settingUpdated: settingUpdated })(Settings)