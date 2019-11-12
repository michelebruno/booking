import React from 'react';
import { Redirect } from "react-router-dom";
import { Route } from 'react-router-dom';
import Button from 'react-bootstrap/Button'
import { ButtonGroup, Dropdown, ButtonToolbar, Form } from 'react-bootstrap';
import store from "./_store";
// lazy load all the views
const Dashboard = React.lazy(() => import('./pages/Dashboard'));
const Esercenti = React.lazy(() => import('./pages/Esercenti'));
const EsercentiProfilo = React.lazy(() => import('./pages/EsercentiProfilo'));
const EsercentiCrea = React.lazy(() => import('./pages/EsercentiCrea'));
const Ordini = React.lazy(() => import('./pages/Ordini'));
const OrdiniScheda = React.lazy(() => import('./pages/OrdiniScheda'));
const Deals = React.lazy(() => import('./pages/Deals'));
const DealsScheda = React.lazy(() => import('./pages/DealsScheda'));
const ServiziScheda = React.lazy(() => import('./pages/ServiziScheda'));
const DealsModifica = React.lazy(() => import('./pages/DealsModifica'));
const Settings = React.lazy(() => import('./pages/Settings'));
const Tickets = React.lazy( () => import('./pages/Tickets'));
const Utenti = React.lazy( () => import('./pages/Utenti')); 
const UtentiProfilo = React.lazy( () => import('./pages/UtentiProfilo'));
// handle auth and authorization

const PrivateRoute = ({ component: Component, roles, ...rest }) => (
	<Route {...rest} render={props => {
		
		const loggedInUser = store.getState().currentUser;
 
		// check if route is restricted by role
		if ( roles && roles.indexOf(loggedInUser.ruolo) == -1 ) {
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

const NuovoEsercente = props => <Button as={Link} to="/esercenti/crea" >Aggiungi esercente</Button>

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
  { path: '/dashboard', component: Dashboard, route: PrivateRoute, title: 'La mia dashboard' },
  { path: '/esercenti/crea', component: EsercentiCrea, route: PrivateRoute, roles: ['admin' , 'account_manager'], title: 'Nuovo esercente', tastimenu: NuovoEsercente },
  { path: '/esercenti/:id/modifica', component: EsercentiCrea, route: PrivateRoute, roles: ['admin' , 'account_manager'], title: 'Modifica esercente', tastimenu: NuovoEsercente },
  { path: '/esercenti/:id', component: EsercentiProfilo, route: PrivateRoute, roles: ['admin' , 'account_manager'], title: 'Profilo esercente' ,tastimenu: ProfiloEsercenteTasti },
  { path: '/esercenti', exact: true, component: Esercenti, route: PrivateRoute, roles: ['admin', 'account_manager'], title: 'Esercenti' },
  { path: '/ordini/:id', exact: true, component: OrdiniScheda, route: PrivateRoute, roles: ['admin' , 'account_manager'], title: 'Dettagli ordine' },
  { path: '/ordini', exact: true, component: Ordini, route: PrivateRoute, roles: ['admin' , 'account_manager'], title: 'Ordini' },
  { path: '/deals/modifica/:id', component: DealsModifica, route: PrivateRoute, roles: ['admin' , 'account_manager'], title: 'Scheda deal', tastimenu : SchedaProdottoTasti },
  { path: '/deals/:id' , component: DealsScheda, route: PrivateRoute, roles: ['admin' , 'account_manager'], title: 'Scheda deal', tastimenu : SchedaProdottoTasti },
  { path: '/deals', exact: true, component: Deals, route: PrivateRoute, roles: ['admin'], title: 'Deals' },
  { path: '/servizi/:id', component: ServiziScheda, route: PrivateRoute, roles: ['admin'], title: 'Scheda servizio', tastimenu : SchedaProdottoTasti },
  { path: '/settings', component: Settings, route: PrivateRoute, roles: ['admin'], title: 'Impostazioni' },
  { path: '/tickets', component: Tickets, route: PrivateRoute, roles: ['admin' , 'account_manager'], title: 'Scheda deal', tastimenu : SchedaProdottoTasti },
  { path: '/utenti/:id', component: UtentiProfilo, route: PrivateRoute, roles: ['admin' , 'account_manager'], title: 'Profilo Utente' },
  { path: '/utenti', component: Utenti, route: PrivateRoute, roles: ['admin' , 'account_manager'], title: 'Utenti' },
  {
	path: "/",
	exact: true,
	component: () => <Redirect to="/dashboard" />,
	route: PrivateRoute
  }
]

export { routes, PrivateRoute };