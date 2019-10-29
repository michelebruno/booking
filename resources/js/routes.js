import React from 'react';
import { Redirect } from "react-router-dom";
import { Route } from 'react-router-dom';
import Button from 'react-bootstrap/Button'
import { ButtonGroup, DropdownButton, Dropdown, ButtonToolbar, Form } from 'react-bootstrap';

// lazy load all the views
const Dashboard = React.lazy(() => import('./pages/Dashboard'));
const Esercenti = React.lazy(() => import('./pages/Esercenti'));
const EsercentiProfilo = React.lazy(() => import('./pages/EsercentiProfilo'));
const EsercentiModifica = React.lazy(() => import('./pages/EsercentiModifica'));
const Ordini = React.lazy(() => import('./pages/Ordini'));
const OrdiniScheda = React.lazy(() => import('./pages/OrdiniScheda'));
const Deals = React.lazy(() => import('./pages/Deals'));
const DealsScheda = React.lazy(() => import('./pages/DealsScheda'));
const ServiziScheda = React.lazy(() => import('./pages/ServiziScheda'));
const DealsModifica = React.lazy(() => import('./pages/DealsModifica'));
const Tickets = React.lazy( () => import('./pages/Tickets'));
const Utenti = React.lazy( () => import('./pages/Utenti'));
const UtentiCrea = React.lazy( () => import('./pages/UtentiCrea'));
// handle auth and authorization

const PrivateRoute = ({ component: Component, roles, ...rest }) => (
	<Route {...rest} render={props => {
		/* const loggedInUser = getLoggedInUser(); */
		// check if route is restricted by role
		if (false && roles) {
		// role not authorised so redirect to home page
		return <Redirect to={{ pathname: '/' }} />
		}

		// authorised so return component
		return <Component {...props} />
	}} />
)

const SalvaEsercente = props => <React.Fragment>
	<Button variant="success">Salva</Button>
</React.Fragment>

const SchedaProdottoTasti = ( props ) => <React.Fragment> 
	{/* props.match.params.id è disponibile */}
	<ButtonToolbar className="d-inline-block">
		<Button  className="d-inline-block mr-2" variant="dark" size="sm">Modifica</Button> 
		<Dropdown as={ButtonGroup}  className="d-inline-block" variant="success" size="sm" title="Aggiorna Wordpress">

				<Button variant="success" >Aggiorna il server Wordpress</Button>

				<Dropdown.Toggle split variant="success" className="align-middle" ><i className="fas fa-lg fa-sort-down h-100" /></Dropdown.Toggle>

				<Dropdown.Menu>
					<Dropdown.Item as="button">Cambia l'id associato</Dropdown.Item>
				</Dropdown.Menu>
		</Dropdown>
	</ButtonToolbar>
	</React.Fragment>

const ProfiloEsercenteTasti = ( props ) => <ButtonToolbar className="d-inline-block">
	<Button className="d-inline-block mr-2">Modifica</Button> 
		<Form.Switch className="d-inline-block" name="abilitato" label="Abilitato" /> 
</ButtonToolbar>
 
const routes = [

  // other pages
  { path: '/dashboard', name: 'Dashboard', component: Dashboard, route: PrivateRoute, roles: ['Admin'], title: 'La mia dashboard' },
  { path: '/esercenti/modifica/:id', name: 'Esercenti', component: EsercentiModifica, route: PrivateRoute, roles: ['Admin'], title: 'Modifica esercente', tastimenu: SalvaEsercente },
  { path: '/esercenti/:id', name: 'Esercenti', component: EsercentiProfilo, route: PrivateRoute, roles: ['Admin'], title: 'Profilo esercente' ,tastimenu: ProfiloEsercenteTasti },
  { path: '/esercenti', exact: true, name: 'Esercenti', component: Esercenti, route: PrivateRoute, roles: ['Admin'], title: 'Esercenti' },
  { path: '/ordini/:id', exact: true, name: 'Ordini', component: OrdiniScheda, route: PrivateRoute, roles: ['Admin'], title: 'Dettagli ordine' },
  { path: '/ordini', exact: true, name: 'Ordini', component: Ordini, route: PrivateRoute, roles: ['Admin'], title: 'Ordini' },
  { path: '/deals/modifica/:id', name: 'Scheda deal', component: DealsModifica, route: PrivateRoute, roles: ['Admin'], title: 'Scheda deal', tastimenu : SchedaProdottoTasti },
  { path: '/deals/:id', name: 'Scheda deal', component: DealsScheda, route: PrivateRoute, roles: ['Admin'], title: 'Scheda deal', tastimenu : SchedaProdottoTasti },
  { path: '/deals', exact: true, name: 'Deals', component: Deals, route: PrivateRoute, roles: ['Admin'], title: 'Deals' },
  { path: '/servizi/:id', name: 'Scheda servizi', component: ServiziScheda, route: PrivateRoute, roles: ['Admin'], title: 'Scheda servizio', tastimenu : SchedaProdottoTasti },
  { path: '/tickets', name: 'Tickets', component: Tickets, route: PrivateRoute, roles: ['Admin'], title: 'Scheda deal', tastimenu : SchedaProdottoTasti },
  { path: '/utenti/crea', name: 'UtentiCrea', component: UtentiCrea, route: PrivateRoute, roles: ['Admin'], title: 'Utenti' },
  { path: '/utenti', name: 'Utenti', component: Utenti, route: PrivateRoute, roles: ['Admin'], title: 'Utenti' },
  {
	path: "/",
	exact: true,
	component: () => <Redirect to="/dashboard" />,
	route: PrivateRoute
  }
]

export { routes, PrivateRoute };