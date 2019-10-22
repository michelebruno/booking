import React, { Component, Suspense } from 'react';
import { BrowserRouter , Switch } from 'react-router-dom'; 

import { routes } from './routes';

import AuthLayout from './components/AuthLayout'


// Lazy loading and code splitting - 
// Derieved idea from https://blog.logrocket.com/lazy-loading-components-in-react-16-6-6cea535c0b52
const loading = () => <div></div>

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


const App = ( props ) => {
	const getLayout = () => {
		return AuthLayout;
    }
    
	return(
		// rendering the router with layout
		<BrowserRouter>
			<React.Fragment>
				<Switch>
					{routes.map((route, index) => {
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
			</React.Fragment>
		</BrowserRouter>
	)
} 

export default App;