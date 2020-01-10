import React , { useState , useEffect } from 'react'
import { Card , Button } from 'react-bootstrap'  
import BootstrapTable from 'react-bootstrap-table-next'
import { Redirect , Link } from 'react-router-dom'
import AxiosConfirmModal from '../components/AxiosConfirmModal'

const Deals = ( props ) => {
    const [ redirectTo, setRedirectTo ] = React.useState(false);

    const [ deals , setDeals ] = useState()

    const loadDeals = ( ) => {

        const source = axios.CancelToken.source()

        axios.get("/deals", { cancelToken : source.token })
            .then( res => setDeals(res.data))
            .catch( error => axios.isCancel(error) || console.error( error ))
        return () => {
            source.cancel()
        };
    }

    useEffect(() => {

        return loadDeals()

    }, [])

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
            <i title={cell} className={ "fa fa-circle " + color } ></i>
        )
    }


    const rowEvents = {
        onDoubleClick : ( e , row , rowIndex ) => {
            setRedirectTo('/deals/' + row.id);
        }
    }   
    
    const DeleteServizioButton = props => {
        const [show, setShow] = useState(false)

        return <div className={props.className}>
        
            <Button variant="danger" className="ml-1" onClick={ () => setShow(true) }>
                <i className="fas fa-trash" />
            </Button>

            <AxiosConfirmModal url={ props.url } show={show} method="delete" onHide={() => { setShow(false); loadDeals()}} title="Conferma" >
                Sei sicuro di cancellare questo servizio?
            </AxiosConfirmModal>
        </div>
    }

    const RestoreServizioButton = props => {
        const [show, setShow] = useState(false)

        return <div className={props.className}>
        
            <Button variant="danger" className="ml-1" onClick={ () => setShow(true) }>
                <i className="fas fa-undo" />
            </Button>

            <AxiosConfirmModal url={ props.url } show={show} method="patch" onHide={() => { setShow(false); loadDeals() }} title="Conferma" >
                Sei sicuro di voler ripristinare questo servizio?
            </AxiosConfirmModal>
        </div>
    }

    const prezziFormatter = new Intl.NumberFormat('en-US', { style : 'currency' , currency: 'EUR' } ).format

    return(
        <React.Fragment>
            { redirectTo && <Redirect push to={ redirectTo } />} 
            <Card>
                <Card.Body> 
                    <h1>Deals</h1>
                    { process.env.NODE_ENV === "development" && <p>Azioni di filtraggio varie...</p>}
                    { deals && <BootstrapTable 
                        keyField="id"
                        noDataIndication="Non ci sono prodotti collegati."
                        data={deals}
                        columns={[
                            {
                                text: 'Cod.',
                                dataField: 'codice'
                            },
                            {
                                text: 'Titolo',
                                dataField: 'titolo'
                            },
                            {
                                text: 'Importo',
                                dataField: 'tariffe.intero.importo',
                                formatter : ( cell , row ) => cell ? prezziFormatter(cell) : "-"
                            },
                            {
                                text: 'Disponibiiltà',
                                dataField: 'disponibili'
                            },
                            {
                                text : "",
                                dataField: "azioni",
                                formatter : ( cell, row ) =>{
                                    const Buttons = ( props ) => {

                                        let url = row._links.self
                                        let state = { deal : row }

                                        
                                        return <>
                                            <Button as={ Link } to={ { pathname: row._links.self , state: state} } variant="primary" className="mr-1" title="Accedi alla pagina del prodotto" className=" d-md-inline-block" ><i className="fas fa-edit"/></Button>
                                            <DeleteServizioButton url={ row._links.self } className="d-none d-md-inline-block" />
                                        </>

                                    }

                                    return <Buttons />
                                }
                            }
                        ]}
                        hover
                        bordered={ false }
                        />}
                </Card.Body>
            </Card>
        </React.Fragment> 
    )
}

export default Deals;