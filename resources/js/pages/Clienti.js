import React from 'react'
import { connect } from 'react-redux'

import { Link } from 'react-router-dom'

import { Card } from 'react-bootstrap'
import ServerDataTable from '../components/ServerDataTable'

const Clienti = () => {

    return <Card>
        <Card.Body>
            <ServerDataTable
                url="/clienti"
                columns={[
                    {
                        name: "email",
                        label: "Email",
                    },
                ]}
                options={{
                    selectableRows: "none",
                }}
            />
        </Card.Body>
    </Card>

}


export default connect()(Clienti)