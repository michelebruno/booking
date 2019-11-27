import React from "react";
import { Link } from 'react-router-dom';
import { connect } from "react-redux"

import ProfileDropdown from './ProfileDropdown';
import logoSm from '../../images/logo-sm.png'; 

const Topbar = ( { TopbarButtons , ...props }) => { 

  	return (
		<>
			<div className="navbar-custom">
				<ul className="list-unstyled topnav-menu float-right mb-0">

					<li>
						<ProfileDropdown />
					</li>


					<li className="dropdown notification-list">
						<button className="btn btn-link nav-link right-bar-toggle waves-effect waves-light" onClick={props.rightSidebarToggle}>
							<i className="fe-settings noti-icon"></i>
						</button>
					</li>
				</ul>

				<div className="logo-box">
					<Link to="/" className="logo text-center">
						<span className="logo-lg">
						<img src="https://www.turismo.bologna.it/wp-content/uploads/2019/03/OSCARD-Turismo-Bologna-Logo_Landscape_-Png.png" alt="" height="16" />
						</span>
						<span className="logo-sm">
						<img src={logoSm} alt="" height="24" />
						</span>
					</Link>
				</div>

				<ul className="list-unstyled topnav-menu topnav-menu-left m-0">
					<li>
						<button className="button-menu-mobile disable-btn waves-effect" onClick={props.menuToggle}>
							<i className="mdi mdi-menu"></i>
						</button>
					</li>

					<li>
						<h4 className="page-title-main d-none d-md-inline-block">{props.title}</h4>
						<div className="d-inline-block h1">
							<TopbarButtons/>
						</div>
					</li>
				</ul>
				
			</div>
		</ >
	);
}


export default connect( state => { return { TopbarButtons : state.TopbarButtons } } )( Topbar );

