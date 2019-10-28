import React, { Component, Suspense } from 'react';
import { BrowserRouter , Switch } from 'react-router-dom'; 
import authenticate from "./_services/auth";
import { routes } from './routes';
import AuthLayout from './components/AuthLayout'
import { connect } from "react-redux"
import PreLoaderWidget from './components/Loader';

// Lazy loading and code splitting - 
// Derieved idea from https://blog.logrocket.com/lazy-loading-components-in-react-16-6-6cea535c0b52
const loading = () => <PreLoaderWidget></PreLoaderWidget>

/**
* Exports the component with layout wrapped to it
* @param {} WrappedComponent 
*/
const withLayout = (WrappedComponent) => {
	const HOC = class extends Component {
		render() {
			return <WrappedComponent {...this.props} />;
		}
	};
	
	return HOC;
}


const App = ( { currentUser, ...props} ) => {
	const getLayout = () => {
		return AuthLayout;
	}
	
	React.useEffect( () => {
		props.authenticate();
	}, [])
    
	return(
		// rendering the router with layout 
		<BrowserRouter>	
			{ !currentUser && loading()}
			{ currentUser && <React.Fragment>
				<Switch>
					{ routes.map( ( route, index ) => {
						return (
							<route.route
								key={index}
								path={route.path}
								exact={route.exact}
								roles={route.roles}
								component={withLayout(props => { 
									return (
										<Suspense fallback={loading()}>
											<AuthLayout {...props} title={route.title}>
												<route.component {...props} />
											</AuthLayout>
										</Suspense>
										);
									})} 
							/>
						);
					})}
				</Switch>
			</React.Fragment>}
		</BrowserRouter> 
	)
} 
const mapStateToProps = state => {
	return {
		currentUser: state.currentUser
	}
}
export default connect( mapStateToProps , { authenticate: authenticate.login } )(App);