/* eslint-disable react/prop-types */
import React from 'react';
import { Route , Redirect } from 'react-router-dom';
import store from "./_store";

// lazy load all the views
const Account = React.lazy(() => import('./pages/Account'));
const AccountModifica = React.lazy(() => import('./pages/AccountModifica'));
const Clienti = React.lazy(() => import('./pages/Clienti'));
const ClientiScheda = React.lazy(() => import('./pages/ClientiScheda'));
const Dashboard = React.lazy(() => import('./pages/Dashboard'));
const Esercenti = React.lazy(() => import('./pages/Esercenti'));
const EsercentiProfilo = React.lazy(() => import('./pages/EsercentiProfilo'));
const EsercentiCrea = React.lazy(() => import('./pages/EsercentiCrea'));
const Ordini = React.lazy(() => import('./pages/Ordini'));
const OrdiniCrea = React.lazy(() => import('./pages/OrdiniCrea'));
const OrdiniScheda = React.lazy(() => import('./pages/OrdiniScheda'));
const Deals = React.lazy(() => import('./pages/Deals'));
const DealsCrea = React.lazy(() => import('./pages/DealsCrea'));
const DealsScheda = React.lazy(() => import('./pages/DealsScheda'));
const ServiziScheda = React.lazy(() => import('./pages/ServiziScheda'));
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
 
const routes = [

  // other pages
  { path: '/account', exact:true, component: Account, route: PrivateRoute, title: 'Il tuo profilo' },
  { path: '/logout', exact:true, component: () => window.location = "/logout" && "", route: PrivateRoute, title: 'Il tuo profilo' },
  { path: '/account/modifica', component: AccountModifica, route: PrivateRoute, title: 'Il tuo profilo' },
  { path: '/clienti/:cliente_id', component: ClientiScheda, route: PrivateRoute, title: 'Clienti' },
  { path: '/clienti', component: Clienti, route: PrivateRoute, title: 'Clienti' },
  { path: '/dashboard', component: Dashboard, route: PrivateRoute, title: 'La mia dashboard' },
  { path: '/esercenti/crea', component: EsercentiCrea, route: PrivateRoute, roles: ['admin' , 'account_manager'], title: 'Nuovo esercente' },
  { path: '/esercenti/:id/modifica', component: EsercentiCrea, route: PrivateRoute, roles: ['admin' , 'account_manager'], title: 'Modifica esercente' },  
  { path: '/esercenti/:esercente/servizi/:servizio', exact: true, component: ServiziScheda, route: PrivateRoute, roles: ['admin' , 'account_manager' ,'esercente' ], title: 'Scheda servizio' },
  { path: '/esercenti/:id', exact: true, component: EsercentiProfilo, route: PrivateRoute, roles: ['admin' , 'account_manager'], title: 'Profilo esercente' },  
  { path: '/esercenti', exact: true, component: Esercenti, route: PrivateRoute, roles: ['admin', 'account_manager'], title: 'Esercenti' },
  { path: '/ordini/crea', exact: true, component: OrdiniCrea, route: PrivateRoute, roles: ['admin' , 'account_manager'], title: 'Dettagli ordine' },
  { path: '/ordini/:ordine_id', exact: true, component: OrdiniScheda, route: PrivateRoute, roles: [ 'admin' , 'account_manager' ], title: 'Dettagli ordine' },
  { path: '/ordini', exact: true, component: Ordini, route: PrivateRoute, roles: [ 'admin' , 'account_manager' ], title: 'Ordini' },
  { path: '/deals/crea', component: DealsCrea, route: PrivateRoute, roles: [ 'admin' , 'account_manager'], title: 'Scheda deal' },
  { path: '/deals/:deal' , component: DealsScheda, route: PrivateRoute, roles: [ 'admin' , 'account_manager', 'esercente' ], title: 'Scheda deal' },
  { path: '/deals', exact: true, component: Deals, route: PrivateRoute, roles: ['admin'], title: 'Deals' },
  { path: '/servizi/:id', component: ServiziScheda, route: PrivateRoute, roles: ['admin'], title: 'Scheda servizio' },
  { path: '/settings', component: Settings, route: PrivateRoute, roles: ['admin'], title: 'Impostazioni' },
  { path: '/tickets', component: Tickets, route: PrivateRoute, roles: ['admin' , 'account_manager'], title: 'Scheda deal' },
  { path: '/utenti/:id', component: UtentiProfilo, route: PrivateRoute, roles: ['admin' , 'account_manager'], title: 'Profilo Utente' },
  { path: '/utenti', component: Utenti, route: PrivateRoute, roles: ['admin' , 'account_manager'], title: 'Utenti' },
  {
	path: "/",
	exact: true,
	// eslint-disable-next-line react/display-name
	component: () => <Redirect to="/dashboard" />,
	route: PrivateRoute
  }
]

export { routes, PrivateRoute };