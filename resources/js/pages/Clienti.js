import React from 'react'
import { connect } from 'react-redux'

import { Link } from 'react-router-dom'

import { Card } from 'react-bootstrap'
import MUIDataTable from 'mui-datatables'

import useServerSideCollection from '../_services/useServerSideCollection'

const Clienti = () => {

    const [ collection, serverSideOptions ] = useServerSideCollection( "/clienti", ) 
    const clienti =  collection && collection.data

    let columns 
    return <Card>
        <Card.Body>
            { clienti && clienti && <MUIDataTable 
                data={ clienti }
                columns={ columns = [
                    {
                        name: "email",
                        label : "Email",
                        options :{ 
                            customBodyRender : ( cell , { rowIndex } ) => {
                                const row = clienti[rowIndex]
                                return <Link to={ row._links.self }>{ cell }</Link>
                            },
                        }
                    }
                ] }
                options={{
                    ...serverSideOptions( columns ),
                    selectableRows : "none"
                }}
                />}
        </Card.Body>
    </Card>

}


export default connect()(Clienti)