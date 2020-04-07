import React from 'react'
import { connect } from 'react-redux'

import Card from "@material-ui/core/Card"
import CardContent from "@material-ui/core/CardContent"
import ServerDataTable from '../components/ServerDataTable'

const Clienti = () => {

    return <Card>
        <CardContent>
            <ServerDataTable
                url="/clienti"
                title="Clienti"
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
        </CardContent>
    </Card>

}


export default connect()(Clienti)