import React from 'react'
import { Card, Table } from 'react-bootstrap'  
import BootstrapTable from 'react-bootstrap-table-next'
import { Redirect } from 'react-router-dom'

const Deals = ( props ) => {
    const [ redirectTo, setRedirectTo ] = React.useState(false);

    const colonne = [
        {
            dataField: 'stato',
            text: '',
            headerStyle: {
                width: '4em'
            },
            formatter: ( cell ) => formattaStato(cell)
        },
        {
            dataField: 'id',
            text: 'ID',
            headerStyle: {
                width: '12em'
            }
        },
        {
            dataField: 'titolo',
            text: 'Titolo'
        },
        {
            dataField: 'disponibilità',
            text: 'Disponibilità',
            headerStyle: {
                width: '12em'
            }
        },
        {
            dataField: 'collegati',
            text: 'Servizi collegati',
            headerStyle: {
                width: '12em'
            }
        }
    ]

    let data = [
        {
            id: '21',
            titolo: 'Pranzo tipico a Bologna',
            link: '/deals/22',
            disponibilità: '12',
            stato: 'abilitato',
            collegati: '8'
        },
        {
            id: '2s1',
            titolo: 'Tour-tellino',
            link: '/deals/22',
            disponibilità: '12',
            stato: 'abilitato',
            collegati: 7
        },
        {
            id: '2h1',
            titolo: 'Tour-divino',
            link: '/deals/22',
            disponibilità: '12',
            stato: 'abilitato',
            collegati: 4
        },
        {
            id: '221',
            titolo: 'Corso di cucina bolognese',
            link: '/deals/22',
            disponibilità: '12',
            stato: 'disabilitato',
            collegati: 12
        },
    ]

    /* Formattatori */

    const formattaStato = ( cell, row, rowIndex ) => {
        let color;

        switch (cell) {
            case 'abilitato':
                color = 'text-success'
                break;

            default:
                break;
        }

        return(
            <i title={cell} className={"fa fa-circle " + color } ></i>
        )
    }


    const rowEvents = {
        onDoubleClick : ( e , row , rowIndex ) => {
            setRedirectTo('/deals/' + row.id);
        }
    }

    return(
        <React.Fragment>
            { redirectTo && <Redirect push to={ redirectTo } />} 
            <Card>
                <Card.Body> 
                    <h1>Deals</h1>
                    <p> {"<!--Azioni di filtraggio varie -->"}</p>
                    <BootstrapTable
                        style={ { 'table-layout' : 'auto' } }
                        hover
                        keyField='id'
                        data={ data }
                        columns={ colonne }
                        rowEvents={ rowEvents }
                        bordered={ false }
                    />
                </Card.Body>
            </Card>
        </React.Fragment>
    )
}

export default Deals;